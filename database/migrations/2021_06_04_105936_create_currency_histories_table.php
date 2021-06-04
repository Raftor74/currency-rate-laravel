<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('cascade');
            $table->decimal('rate', 8, 2);
            $table->timestamp('relevant_on');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_histories');
    }
}
