<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{
    public function getFeaturedBooks ()
    {
        $user = Auth::user();


        $books = $user->featureds;

        return response()->json([
                'user' => $user,
                'books' => $books
            ]
        );
    }
}
