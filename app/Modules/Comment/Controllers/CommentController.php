<?php

namespace App\Modules\Comment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Book\Models\Book;
use App\Modules\User\Models\User;
use Comment;
use App\Modules\Comment\Requests\CommentRequest;

class CommentController extends Controller
{
    public function editComment($id)
    {
        if (!auth()->user()->can('EDIT_COMMENT')) {
            return view('403');
        }

        $this->title('Comment edit');

        $comment = Comment::find($id);
        $users = User::all();
        $books = Book::all();

        $this->view('comment::edit');

        return $this->render(compact('comment', 'users', 'books'));
    }

    public function showList()
    {
        if (!auth()->user()->can('VIEW_COMMENT_LIST')) {
            return view('403');
        }

        $comments = Comment::all();
        $this->title('Comments');

        $this->view('comment::list');

        return $this->render(compact('comments'));
    }

    public function addForm()
    {
        if (!auth()->user()->can('ADD_COMMENT')) {
            return view('403');
        }

        $books = Book::all();
        $users = User::all();
        $this->title(__('Add comment'));

        $this->view('comment::add');

        return $this->render(compact('books', 'users'));
    }

    public function save(CommentRequest $request)
    {
        $result = Comment::save($request);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect()->route('comment')->with($result);
    }

    public function deleteComment($id)
    {
        if (!auth()->user()->can('DELETE_COMMENT')) {
            return view('403');
        }

        $result = Comment::delete($id);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect()->route('comment')->with($result);
    }
}
