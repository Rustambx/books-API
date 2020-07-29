<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturedBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('featured_books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('book_id')->unsigned();
            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('featured_books');
    }
}
