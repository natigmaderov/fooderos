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
        Schema::create('catagory_models', function (Blueprint $table) {
            $table->id();
            $table->integer('catagory_id');
            $table->integer('branch_count');
            $table->string('image');
            $table->integer('status');
            $table->integer('rest_id');
            $table->integer('store_id');
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
        Schema::dropIfExists('catagory_models');
    }
};
