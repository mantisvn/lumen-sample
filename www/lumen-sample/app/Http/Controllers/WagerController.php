<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use Carbon\Carbon;
use App\Http\Models\Wager;
use Illuminate\Http\Request;
use App\Http\Models\WagerBuying;
use Illuminate\Support\Facades\DB;

class WagerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * List of wagers
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $limit = $request->limit ? $request->limit : 15;
        $wagers = Wager::orderBy('id', 'DESC')->simplePaginate($limit);

        return $this->responseSuccess($wagers->items());
    }

    /**
     * Create new Wager
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate input data
        $validator = Validator::make($request->all(), [
            'total_wager_value' => 'required|integer|gt:0',
            'odds' => 'required|integer|gt:0',
            'selling_percentage' => 'required|integer|between:1,100',
            'selling_price' => 'required|numeric',
        ]);

        $validator->after(function ($validator) use ($request) {
            $max_selling_price = intval($request->total_wager_value)*($request->selling_percentage/100);
            
            if ($request->selling_price <= $max_selling_price){
                $validator->errors()->add('selling_price', 'Selling price must be greater than '.$max_selling_price);
            }
        });

        if ($validator->fails()) {
            return $this->responseError('validation_failed', $validator->errors());
        }

        try {
            //Store wager
            $wager = new Wager();
            $wager->total_wager_value = $request->total_wager_value;
            $wager->odds = $request->odds;
            $wager->selling_percentage = $request->selling_percentage;
            $wager->selling_price = $request->selling_price;
            $wager->current_selling_price = $wager->selling_price;
            $wager->placed_at = Carbon::now()->toDateTimeString();
        
            if ($wager->save()) {
                return $this->responseSuccess(Wager::find($wager->id), 201);
            } else {
                return $this->responseError('cannot_save', null);
            }
        } catch (Exception $e) {
            return $this->responseError('cannot_save', $e->getMessage(), 500);
            
        }
    }

    /**
     * Buy wager
     */
    public function buy(Request $request, $wager_id) {
        //Validate input data
        $validator = Validator::make($request->all(), [
            'buying_price' => 'required|numeric',
        ]);
        

        if ($validator->fails()) {
            return $this->responseError('validation_failed', $validator->errors());
        }

        $responseError = null;

        //Start buying transaction
        DB::beginTransaction();
        try {
            //Locking row to process buying
            $wager = DB::table('wager')->where('id', $wager_id)->lockForUpdate()->first();

            if ($wager) {
                //Check wager available for sell
                if ($wager->percentage_sold < 100) {
                    if ($request->buying_price <= $wager->current_selling_price) {
                        $wagerBuying = new WagerBuying();
                        $wagerBuying->wager_id = $wager_id;
                        $wagerBuying->buying_price = $request->buying_price;
                        $wagerBuying->bought_at = Carbon::now()->toDateTimeString();

                        if ($wagerBuying->save()) {

                            //Update to Wager 
                            $wagerUpdate = Wager::find($wager_id);

                            $wagerUpdate->current_selling_price = $wager->current_selling_price - $request->buying_price;
                            $wagerUpdate->amount_sold = $wagerUpdate->amount_sold + $request->buying_price;
                            $wagerUpdate->percentage_sold =  ($wagerUpdate->amount_sold / $wager->selling_price) * 100;
                            
                            $wagerUpdate->save();

                            DB::commit();
                            return $this->responseSuccess($wagerBuying, 201);
                        } else {
                            $responseError = $this->responseError('cannot_save', null);
                        }
                        
                    } else {
                        $responseError = $this->responseError('validation_failed', [
                            'buying_price' => 'Buying price must be lesser or equal to '.$wager->current_selling_price,
                        ]);
                    }
                } else {
                    $responseError = $this->responseError('completely_sold', [
                        'This wager was completely sold'
                    ]);
                }
            } else {
                $responseError = $this->responseError('validation_failed', [
                    'wager_id' => 'The wager with #'.$wager_id.' does exist.',
                ]);
            }
        } catch (Exception $e) {
            $responseError = $this->responseError('cannot_save', $e->getMessage(), 500);
        }

        if ($responseError) {
            DB::rollBack();
            return $responseError;
        }
    }
}
