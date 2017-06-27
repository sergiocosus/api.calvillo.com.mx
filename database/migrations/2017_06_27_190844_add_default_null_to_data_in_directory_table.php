<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultNullToDataInDirectoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directories', function(Blueprint $table) {
            $table->string('address')->nullable(true)->change();
            $table->string('email')->nullable(true)->change();
            $table->string('phone')->nullable(true)->change();
            $table->string('cellphone')->nullable(true)->change();
            $table->string('website_url')->nullable(true)->change();
            $table->string('youtube_url')->nullable(true)->change();
            $table->string('facebook_url')->nullable(true)->change();
        });

        DB::statement('ALTER TABLE directories MODIFY latitude DOUBLE NULL;');
        DB::statement('ALTER TABLE directories MODIFY longitude DOUBLE NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directories', function(Blueprint $table) {
            $table->string('address')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('cellphone')->nullable(false)->change();
            $table->string('website_url')->nullable(false)->change();
            $table->string('youtube_url')->nullable(false)->change();
            $table->string('facebook_url')->nullable(false)->change();
        });

        DB::statement('ALTER TABLE directories MODIFY latitude DOUBLE NOT NULL;');
        DB::statement('ALTER TABLE directories MODIFY longitude DOUBLE NOT NULL;');
    }
}
