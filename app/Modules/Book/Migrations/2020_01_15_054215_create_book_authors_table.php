<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_authors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('book_id')->unsigned();
            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');

            $table->bigInteger('term_id')->unsigned();
            $table->foreign('term_id')
                ->references('id')
                ->on('terms')
                ->onDelete('cascade');

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
        Schema::dropIfExists('book_authors');
    }
}
