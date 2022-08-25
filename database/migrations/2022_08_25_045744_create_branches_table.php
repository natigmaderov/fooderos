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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('store_id');
            $table->string('address');
            $table->string('country');
            $table->string('city');
            $table->string('lat');
            $table->string('long');
            $table->string('phone');
            $table->string('profile');
            $table->string('cover');
            $table->string('currency');
            $table->string('payment');
            $table->string('cash_limit');
            $table->string('amount');
            $table->string('payload');
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
        Schema::dropIfExists('branches');
    }
};
