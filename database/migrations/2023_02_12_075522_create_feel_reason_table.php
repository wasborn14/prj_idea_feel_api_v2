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
        Schema::create('feel_reasons', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->uuid('id')->primary();
            $table->string('title')->nullable()->comment('タイトル');
            $table->timestamps();
            // $table->unsignedBigInteger('user_id');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feel_reasons');
    }
};
