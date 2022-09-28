<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('meal_id')
                ->constrained()
                // meal_IDが変更された場合、likeデーブルのmeal_IDも変更するため
                ->cascadeOnUpdate()
                // お気に入り登録をしたmealが削除された場合、そのユーザーのお気に入りも削除するため
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained()
                // user_IDが変更された場合、likeデーブルのuser_IDも変更するため
                ->cascadeOnUpdate()
                // お気に入り登録をしたユーザーが削除された場合、そのユーザーのお気に入りも削除するため
                ->cascadeOnDelete();
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
        Schema::dropIfExists('likes');
    }
}
