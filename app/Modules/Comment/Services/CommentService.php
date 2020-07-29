<?php

namespace App\Modules\Comment\Services;

use App\Modules\Comment\Models\Comment;
use App\Modules\Comment\Requests\CommentRequest;

class CommentService
{
    public function all()
    {
        return Comment::all();
    }

    public function delete($id)
    {
        $comment = Comment::find($id);
        if ($comment->delete()) {
            return ['status' => 'Комментария удалена'];
        } else {
            return ['error' => 'Ошибка при удалении'];
        }
    }

    public function find($id)
    {
        return Comment::find($id);
    }

    public function save(CommentRequest $request)
    {
        if ($request->has('edit')) {
            $data = $request->except('_token', 'edit', 'id');
            $comment = Comment::find($request->id);

            if ($comment->update($data)) {
                return ['status' => 'Комментария обновлена'];
            } else {
                return ['error' => 'Ошибка при обновлени'];
            }
        } else {
            $data = $request->except('_token');
            if (Comment::create($data)) {
                return ['status' => 'Комментария добавлена'];
            } else {
                return ['error' => 'Ошибка при добавлении'];
            }
        }

    }
}
