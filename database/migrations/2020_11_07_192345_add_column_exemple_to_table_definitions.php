<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnExempleToTableDefinitions extends Migration
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
            $table->mediumText('exemple')->default('');

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
            $table->dropColumn(['exemple']);
        });
    }
}
