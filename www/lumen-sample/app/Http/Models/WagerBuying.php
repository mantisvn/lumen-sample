<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class WagerBuying extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wager_buying';

    public function toArray() {
        return [
            "id" => $this->id,
            "wager_id" => $this->wager_id,
            "buying_price" => $this->buying_price,
            "bought_at" => $this->bought_at
        ];
    }
}