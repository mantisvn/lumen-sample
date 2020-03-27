<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wager', function(Blueprint $table)
		{
			$table->bigIncrements('id');
            $table->unsignedInteger('total_wager_value');
            $table->unsignedInteger('odds');
			$table->unsignedTinyInteger('selling_percentage');
			$table->unsignedDecimal('selling_price', 8, 2);
			$table->unsignedDecimal('current_selling_price', 8, 2);
			$table->unsignedDecimal('percentage_sold', 8, 2)->nullable();
			$table->unsignedDecimal('amount_sold', 8, 2)->nullable();
			$table->timestamp('placed_at')->useCurrent();
            $table->timestamps();
            $table->index(['placed_at']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wager');
    }
}
