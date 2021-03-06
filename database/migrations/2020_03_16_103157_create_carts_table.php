<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->string('cart_uuid');
            $table->unsignedBigInteger('drug_id');
            $table->integer('quantity');
            $table->float('price');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('drug_id')->references('id')->on('pharmacy_drugs');
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
        Schema::dropIfExists('carts');
    }
}
