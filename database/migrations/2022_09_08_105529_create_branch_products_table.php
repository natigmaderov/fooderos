<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_products', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id');
            $table->string('path');
            $table->integer('product_id');
            $table->integer('unit_price');
            $table->integer('weight');
            $table->integer('order_id');
            $table->integer('isPublic');
            $table->integer('status');
            $table->softDeletes();
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
        Schema::dropIfExists('branch_products');
    }
};
