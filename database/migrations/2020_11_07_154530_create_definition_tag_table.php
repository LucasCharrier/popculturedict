<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefinitionTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('definition_tag', function (Blueprint $table) {
            // $table->increments('id');
            $table->bigInteger('tag_id')->unsigned();
            $table->bigInteger('definition_id')->unsigned();
            $table->primary(['tag_id','definition_id']);
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags');
             $table->foreign('definition_id')
                ->references('id')
                ->on('definitions');
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
        Schema::dropIfExists('definition_tag');
    }
}
