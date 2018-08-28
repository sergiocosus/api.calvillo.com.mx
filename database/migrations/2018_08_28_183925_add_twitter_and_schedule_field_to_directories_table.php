<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwitterAndScheduleFieldToDirectoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directories', function(Blueprint $table) {
            $table->string('schedule')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directories', function(Blueprint $table) {
            $table->dropColumn('schedule');
            $table->dropColumn('twitter_url');
            $table->dropColumn('type');
        });
    }
}
