<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentHistoricsTable extends Migration
{
    public function up()
    {
        Schema::create('comment_historics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->references('id')->on('comments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comment_historics');
    }
}
