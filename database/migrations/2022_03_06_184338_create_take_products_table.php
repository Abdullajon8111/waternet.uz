<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTakeProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('take_products', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->integer('received_id');
            $table->integer('sent_id');
            $table->integer('product_id');
            $table->integer('product_count');
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
        Schema::dropIfExists('take_products');
    }
}
