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
            $table->uuid('id')->primary();
            $table->datetime('date')->comment('日付');
            $table->TinyInteger('feel')->comment('感情数値'); // 1, 2, 3
            $table->string('memo')->nullable()->comment('メモ');
            $table->boolean('is_predict')->comment('予測判定'); // True:予測、False:記録 
            $table->timestamps();

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->uuid('reason_id')->nullable();
            $table->foreign('reason_id')->references('id')->on('feel_reasons')->nullOnDelete();
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
