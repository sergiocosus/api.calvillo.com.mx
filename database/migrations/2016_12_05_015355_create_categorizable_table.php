<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategorizableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorizables', function(Blueprint $table) {
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('categorizable_id');
            $table->string('categorizable_type');
            $table->unsignedInteger('priority');

            $table->primary([
                'category_id',
                'categorizable_id',
                'categorizable_type',
            ], 'categorizables_pk');

            $table->foreign('category_id')->references('id')->on('categories')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->index([
                'category_id',
                'categorizable_id',
                'categorizable_type',
                'priority'
            ], 'categorizables_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categorizables');
    }
}
