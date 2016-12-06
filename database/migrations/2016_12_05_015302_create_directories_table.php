<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('link');
            $table->string('image_code', 14);

            $table->text('description');
            $table->string('address');
            $table->string('email');
            $table->string('phone');
            $table->string('cellphone');
            $table->string('website_url');
            $table->string('youtube');
            $table->string('facebook');
            $table->double('latitude');
            $table->double('longitude');

            $table->timestamps();
            $table->softDeletes();
            $table->unique('link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directories');
    }
}
