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
        Schema::create('feels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('date')->comment('日付');
            $table->TinyInteger('feel')->comment('感情数値'); // 1, 2, 3

            // TODO:feel_reasonとの関連付け予定

            $table->string('memo')->nullable()->comment('メモ');
            $table->boolean('is_predict')->comment('予測判定'); // True:予測、False:記録 
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feels');
    }
};
