<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vocabulary_id')->unsigned();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('weight')->default(0);

            $table->nestedSet();

            $table->timestamps();

            $table->foreign('vocabulary_id')
                ->references('id')
                ->on('vocabularies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terms');
    }
}
