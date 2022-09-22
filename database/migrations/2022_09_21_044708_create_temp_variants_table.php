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
        Schema::create('temp_variants', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('sku');
            $table->string('barcode');
            $table->integer('price');
            $table->string('weight');
            $table->integer('status');
            $table->integer('variant_id');
            $table->integer('branch_product_id');
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
        Schema::dropIfExists('temp_variants');
    }
};
