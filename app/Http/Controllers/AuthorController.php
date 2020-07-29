<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function showAuthors()
    {
        $authors = Author::all();

        $this->title('Authors');

        return view('authors')->with(compact('authors'));
    }
}
