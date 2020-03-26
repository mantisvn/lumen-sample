<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function index() {
        return response()->json([
            "id" => 1,
            "total_wager_value" => 20,
            "odds" => 2,
            "selling_percentage" => 30,
            "selling_price" => 55,
            "current_selling_price" => 55,
            "percentage_sold" => 100,
            "amount_sold" => 100,
            "placed_at" => date('Y-m-d H:i:s'),
        ]); 
    }

    public function create() {
        return response()->json(
            [

            ]
        );
    }

    /**
     * Buy wager
     */
    public function buy(Request $request, $wager_id) {
        return response()->json(
            [
                "wager_id" => $wager_id,
            ]
        );
    }
}
