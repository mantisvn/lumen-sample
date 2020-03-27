<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Wager extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wager';

    public function toArray() {
        return [
            "id" => $this->id,
            "total_wager_value" => $this->total_wager_value,
            "odds" => $this->odds,
            "selling_percentage" => $this->selling_percentage,
            "selling_price" => $this->selling_price,
            "current_selling_price" => $this->current_selling_price,
            "percentage_sold" => $this->percentage_sold,
            "amount_sold" => $this->amount_sold,
            "placed_at" => $this->placed_at
        ];
    }
}