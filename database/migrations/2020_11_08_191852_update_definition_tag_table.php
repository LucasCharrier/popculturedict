<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDefinitionTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('definition_tag', function (Blueprint $table) {
            // $table->increments('id');
            $table->dropForeign('tag_id');
            $table->dropForeign('definition_id');
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')->onDelete('cascade');
            $table->foreign('definition_id')->change()
                ->references('id')
                ->on('definitions')->onDelete('cascade');
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
    }
}
