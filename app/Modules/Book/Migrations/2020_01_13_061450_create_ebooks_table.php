<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file');
            $table->bigInteger('book_id')->unsigned();

            $table->foreign('book_id')
                ->references('id')
                ->on('books')
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
        Schema::dropIfExists('ebooks');
    }
}
