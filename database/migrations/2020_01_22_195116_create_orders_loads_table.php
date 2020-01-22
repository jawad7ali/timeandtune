<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersLoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_loads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('load_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
             $table->string('shipper_offer');
            $table->string('mybidoffer');
            $table->string('commission');
           $table->string('total');
           
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
        Schema::dropIfExists('orders_loads');
    }
}
