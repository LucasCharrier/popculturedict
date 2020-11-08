<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('comments', function (Blueprint $table) {
            $table->text('text')->nullable(false);
            $table->foreignId('user_id')->constrained();
            $table->foreignId('word_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('defintions', function (Blueprint $table) {
            $table->dropColumn(['text', 'user_id', 'word_id']);
        });
    }
}
