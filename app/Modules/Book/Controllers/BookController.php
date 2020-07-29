<?php

namespace App\Modules\Book\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Resize\CImage;
use App\Modules\Taxonomy\Models\Term;
use Book;
use App\Modules\Book\Requests\BookRequest;

class BookController extends Controller
{
    public function editBook($id)
    {
        if (!auth()->user()->can('EDIT_BOOK')) {
            return view('403');
        }

        $this->title('Book edit');

        $book = Book::find($id);
        $book->resized_image = CImage::resize($book->image, 120, 150);

        $genres = Term::all()
            ->where('vocabulary_id', 1)
            ->where('parent_id', null);

        $authors_id = $book->authors->pluck('id')->toArray();
        $vocabulary = $book->genres->pluck('vocabulary_id')->toArray();
        /*$terms = $book->genres->pluck();*/
        $book->vocabulary_id = $vocabulary[0];

        $authors = Term::all()->where('vocabulary_id', 2);
        foreach ($authors as $author) {
            if (in_array($author->id, $authors_id)) {
                $author->option = true;
            }
        }

        $this->view('book::edit');

        return $this->render(compact('book', 'genres', 'authors'));
    }

    public function showList()
    {
        if (!auth()->user()->can('VIEW_BOOK_LIST')) {
            return view('403');
        }

        $books = Book::all();

        $this->title('Books');

        $this->view('book::list');

        return $this->render(compact('books'));
    }

    public function addForm()
    {
        if (!auth()->user()->can('ADD_BOOK')) {
            return view('403');
        }

        $books = Book::all();

        $genres = Term::all()
            ->where('vocabulary_id', 1)
            ->where('parent_id', null);

        $authors = Term::all()
            ->where('vocabulary_id', 2)
            ->where('parent_id', null);

        $this->title(__('Add book'));

        $this->view('book::add');

        return $this->render(compact('books', 'genres', 'authors'));
    }

    public function translateBook($id)
    {
        if (!auth()->user()->can('TRANSLATE_BOOK')) {
            return view('403');
        }

        $this->title('Book translate');

        $book = Book::find($id);
        $genres = Term::all()
            ->where('vocabulary_id', 1)
            ->where('parent_id', null);

        $authors_id = $book->authors->pluck('id')->toArray();

        $authors = Term::all()
            ->where('vocabulary_id', 2)
            ->where('parent_id', null);

        foreach ($authors as $author) {
            if (in_array($author->id, $authors_id)) {
                $author->option = true;
            }
        }

        $this->view('book::translate');

        return $this->render(compact('book', 'genres', 'authors'));
    }

    public function save(BookRequest $request)
    {
        $result = Book::save($request);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect()->route('book')->with($result);
    }

    public function deleteBook($id)
    {
        if (!auth()->user()->can('DELETE_BOOK')) {
            return view('403');
        }
        $result = Book::delete($id);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect()->route('book')->with($result);
    }
}
