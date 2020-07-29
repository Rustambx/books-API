<?php

namespace App\Modules\Book\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Book\Models\Book;
use App\Modules\Book\Models\Featured;
use App\Modules\Book\Models\Like;
use App\Modules\Comment\Models\Comment;
use App\Modules\Taxonomy\Models\Term;
use App\Traits\MediaTrait;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use MediaTrait;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['signin']]);
    }

    public function getLatestBooks()
    {
        $books = Book::take(5)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 1,
            'books' => $books
        ]);
    }

    public function getBooksByCategory()
    {
        $genres = Term::all()->where('vocabulary_id', 1)
            ->where('parent_id', 0);

        $subGenres = Term::all()->where('vocabulary_id', 1)
            ->where('parent_id', '>', 0);

        $all = Term::all()->where('vocabulary_id', 1);

        return response()->json([
            'status' => 1,
            'genres' => $genres,
            'subGenres' => $subGenres,
            'all' => $all
        ]);
    }

    public function getBookComments($id)
    {
        $user = auth()->user();
        $book = Book::find($id);
        $comments = $book->comments;
        foreach ($comments as $comment) {
            $comment->likes = Like::all()->where('comment_id', $comment->id);
            $comment->like_count = Like::where('comment_id', $comment->id)->count();
        }

        Featured::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        return response()->json([
            'status' => 1,
            'comments' => $comments,
        ]);
    }

    public function getBookChapters($id)
    {
        $book = Book::find($id);
        if ($book->ebooks){
            return response()->json([
                'status' => 1,
                'chapters' => $book->ebooks,
            ]);
        } else {
            return response()->json([
                'status' => 1,
                'chapters' => $book->audios,
            ]);
        }
    }

    public function postBookComment(Request $request)
    {
        Comment::create([
            'description' => $request->input('description'),
            'user_id' => $request->input('user_id'),
            'book_id' => $request->input('book_id'),
        ]);

        return response()->json([
            'status' => 1,
            'success' => 'Комментария успешно добавлена'
        ]);
    }

    public function getFeaturedBooks()
    {
        $user = auth()->user();
        $featured = Featured::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
        $book = Book::find($featured->book_id);
        $genres = $book->genres;
        $books = [];
        $isAddedIds = [];
        foreach ($genres as $genre) {
            foreach ($genre->books as $book) {
                if (!in_array($book->id, $isAddedIds)) {
                    $books[] = $book;
                    $isAddedIds[] = $book->id;
                }
            }
        }

        return response()->json([
            'status' => 1,
            'books' => $books
        ]);

    }

    public function postLikeComment(Request $request)
    {
        Like::create([
            'comment_id' => $request->input('comment_id'),
            'user_id' => $request->input('user_id')
        ]);

        return response()->json([
            'status' => 1,
        ]);
    }

    public function postBooksBySearchQuery()
    {
        $name = request('name');
        $books = Book::query()->where('name', 'LIKE', "%{$name}%")->get();
        return response()->json([
            'status' => 1,
            'books' => $books
        ]);
    }
}
