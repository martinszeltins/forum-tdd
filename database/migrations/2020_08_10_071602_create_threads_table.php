<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->integer('user_id');
            $table->integer('channel_id');
            $table->integer('replies_count')->default(0);
            $table->string('title');
            $table->text('body');
            $table->integer('visits');
            $table->integer('best_reply_id')->nullable();
            $table->timestamps();

            $table->foreign('best_reply_id')
                  ->references('id')
                  ->on('replies')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
