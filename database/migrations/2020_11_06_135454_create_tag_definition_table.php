<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagDefinitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_definition', function (Blueprint $table) {
            $table->primary(['tag_id','definition_id']);
            $table->bigInteger('tag_id')->unsigned();
            $table->bigInteger('definition_id')->unsigned();
            $table->timestamps();
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')->onDelete('cascade');
             $table->foreign('definition_id')
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
        Schema::dropIfExists('tag_definition');
    }
}
