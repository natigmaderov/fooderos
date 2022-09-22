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
        Schema::create('branch_product_addons', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('barcode');
            $table->string('unit_price');
            $table->string('weigth');
            $table->integer('status');
            $table->integer('branch_product_id');
            $table->integer('addon_id');
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
        Schema::dropIfExists('branch_product_addons');
    }
};
