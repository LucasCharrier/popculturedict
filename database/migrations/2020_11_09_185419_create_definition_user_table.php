<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefinitionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('definition_user', function (Blueprint $table) {
            // $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('definition_id')->unsigned();
            $table->primary(['user_id','definition_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('definition_id')
                ->references('id')
                ->on('definitions')
                ->onDelete('cascade');
            $table->timestamps();
            $table->string('reaction_type', 100);	
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('definition_user');
    }
}
