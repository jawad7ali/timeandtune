<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('from');
            $table->string('to');
            $table->integer('categories');
	        $table->string('price');
            $table->string('pickup_date');
            $table->string('pickup_time');
            $table->string('distance');
            $table->string('model');
            $table->string('loadtype');
		    $table->string('weight');
		    $table->string('length');
		    $table->string('width');
		    $table->integer('user_id')->unsigned()->nullable();
		    $table->integer('role_id')->unsigned()->nullable();
		   	$table->string('status');
		     
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
        Schema::dropIfExists('loads');
    }
}
