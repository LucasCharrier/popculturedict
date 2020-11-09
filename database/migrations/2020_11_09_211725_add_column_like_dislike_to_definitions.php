<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLikeDislikeToDefinitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('definitions', function (Blueprint $table) {
            //
            $table->bigInteger('like')->unsigned()->default(0);
            $table->bigInteger('dislike')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('definitions', function (Blueprint $table) {
            //
            $table->dropColumn(['like','dislike']);
        });
    }
}
