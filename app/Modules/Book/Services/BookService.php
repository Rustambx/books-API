<?php

namespace App\Modules\Book\Services;

use App\Modules\Book\Models\Audio;
use App\Modules\Book\Models\Book;
use App\Modules\Book\Models\Ebook;
use App\Modules\Book\Requests\BookRequest;
use App\Modules\Resize\CImage;
use App\Traits\MediaTrait;

class BookService
{
    use MediaTrait;

    /**
    * return all books
    *
    */
    public function all()
    {
        $books = Book::all();
        foreach ($books as $book) {
            $book->resized_image = CImage::resize($book->image, 100, 150);
        }

        return $books;
    }

    public function create(BookRequest $request)
    {
        $image = $request->file('image');
        if ($this->checkImageMimeType($image)) {
            $save_image = $this->uploadImage($image);
        } else {
            return ['error' => 'Доступны только jpg и png форматы изображений'];
        }

        $book = Book::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->description,
            'language' => $request->input('language'),
            'ref_id' => 0,
            'image' => $save_image
        ]);

        $genres = $request->input('genres');
        if ($genres) {
            $book->genres()->attach($genres);
        }

        $authors = $request->input('authors');
        if ($genres) {
            $book->authors()->attach($authors);
        }

        if ($request->file('ebooks')) {
            $ebooks = $request->file('ebooks');
            foreach ($ebooks as $ebook) {
                if ($this->checkEbookMimeType($ebook)) {
                    $save = $this->saveFiles($ebook);
                } else {
                    return ['error' => 'Доступны только docx, doc и pdf форматы'];
                }
                Ebook::create([
                    'file' => $save,
                    'book_id' => $book->id
                ]);
            }
        } elseif ($request->file('audios')) {
            $audios = $request->file('audios');
            foreach ($audios as $audio) {
                if ($this->checkAudioMimeType($audio)) {
                    $save = $this->saveFiles($audio);
                } else {
                    return ['error' => 'Доступно только mp3 формат'];
                }
                Audio::create([
                    'file' => $save,
                    'book_id' => $book->id
                ]);
            }
        }
        return ['status' => 'Книга успешно добавлена'];
    }

    /**
    * Delete book by the given id.
    *
    * @param int
    * @return status
    */
    public function delete($id)
    {
        $book = Book::where('id', $id)->first();
        $parent = Book::where('ref_id', $id)->first();
        if ($parent) {
            $parent->update([
               'ref_id' => 0
            ]);
        }

        $book->genres()->detach();
        $book->authors()->detach();

        $audios = Audio::all()->where('book_id', $book->id);
        if (isset($audios)) {
            foreach ($audios as $audio) {
                $realPath = storage_path().'/app/public/upload/files/'.$audio->file;
                if (file_exists($realPath)){
                    unlink($realPath);
                }
                if (preg_match('/^(\w+)\//', $audio->file, $matches)) {
                    $dir = storage_path().'/app/public/upload/files/'.$matches[1];
                    if (!empty($dir)) {
                        if (file_exists($dir)) {
                            rmdir($dir);
                        }
                    }
                }
            }
        }

        $ebooks = Ebook::all()->where('book_id', $book->id);
        if (isset($ebooks)) {
            foreach ($ebooks as $ebook) {
                $realPath = storage_path() . '/app/public/upload/files/' . $ebook->file;
                if (file_exists($realPath)){
                    unlink($realPath);
                }
                if (preg_match('/^(\w+)\//', $ebook->file, $matches)) {
                    $dir = storage_path() . '/app/public/upload/files/' . $matches[1];
                    if (!empty($dir)) {
                        if (file_exists($dir)) {
                            rmdir($dir);
                        }
                    }
                }
            }
        }

        if ($book->delete()) {
            return ['status' => 'Книга удалена'];
        } else {
            return ['error' => 'Ошибка при удалении'];
        }
    }

    /**
     * Searches for a book by the given id.
     *
     * @param int
     * @return book
     */
    public function find($id)
    {
        return Book::find($id);
    }

    public function save(BookRequest $request)
    {
        ////            Обновления Книги
        if ($request->has('edit')) {
            return $this->update($request);
        } elseif ($request->has('translate')) {
            return $this->translate($request);
        } else {
            return $this->create($request);
        }
    }

    public function translate(BookRequest $request)
    {
        $image = $request->file('image');
        if ($this->checkImageMimeType($image)) {
            $save_image = $this->uploadImage($image);
        } else {
            return ['error' => 'Доступны только jpg и png форматы изображений'];
        }

        $book = Book::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->description,
            'language' => $request->input('language'),
            'ref_id' => $request->input('ref_id'),
            'image' => $save_image
        ]);

        $genres = $request->input('genres');
        if ($genres) {
            $book->genres()->attach($genres);
        }

        $authors = $request->input('authors');
        if ($genres) {
            $book->authors()->attach($authors);
        }

        if ($request->file('ebooks')) {
            $ebooks = $request->file('ebooks');
            foreach ($ebooks as $ebook) {
                if ($this->checkEbookMimeType($ebook)) {
                    $save = $this->saveFiles($ebook);
                } else {
                    return ['error' => 'Доступны только docx, doc и pdf форматы'];
                }
                Ebook::create([
                    'file' => $save,
                    'book_id' => $book->id
                ]);
            }
        } elseif ($request->file('audios')) {
            $audios = $request->file('audios');
            foreach ($audios as $audio) {
                if ($this->checkAudioMimeType($audio)) {
                    $save = $this->saveFiles($audio);
                } else {
                    return ['error' => 'Доступно только mp3 формат'];
                }
                Audio::create([
                    'file' => $save,
                    'book_id' => $book->id
                ]);
            }
        }

        return ['status' => 'Книга успешно переведена'];
    }

    public function update(BookRequest $request)
    {
        $book = Book::find($request->id);
        $data = $request->only(['name', 'price', 'description', 'language']);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($this->checkImageMimeType($image)) {
                $data['image'] = $this->uploadImage($image);
            } else {
                return ['error' => 'Доступны только jpg и png форматы изображений'];
            }
            $data['image'] = $this->uploadImage($image);
            $realPath = storage_path() . '/app/public/upload/images/' . $book->image;
            if (file_exists($realPath)) {
                if (preg_match('/(.*?)(\w+)\.(\w+)$/', $book->image, $matches)) {
                    $files = glob(storage_path() . '/app/public/upload/images/' . $matches[1] . $matches[2] . '_resize_*');
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            unlink($file);
                        }
                    }
                }
                unlink($realPath);

                if (preg_match('/^(\w+)\//', $book->image, $matches)) {
                    $dir = storage_path() . '/app/public/upload/images/' . $matches[1];
                    if (!empty($dir)) {
                        rmdir($dir);
                    }
                }
            }
        }

        $book->update($data);

        if ($request->input('genres')) {
            $genres = $request->input('genres');
            if ($genres) {
                $book->genres()->sync($genres);
            }
        }

        if ($request->input('authors')) {
            $authors = $request->input('authors');
            if ($authors) {
                $book->authors()->sync($authors);
            }
        }

        if ($request->hasFile('audios')) {
            $audios = Audio::all()->where('book_id', $book->id);
            if ($audios) {
                foreach ($audios as $audio) {
                    $realPath = storage_path() . '/app/public/upload/files/' . $audio->file;
                    if (file_exists($realPath)) {
                        unlink($realPath);
                    }
                    if (preg_match('/^(\w+)\//', $audio->file, $matches)) {
                        $dir = storage_path() . '/app/public/upload/files/' . $matches[1];
                        if (!empty($dir)) {
                            if (file_exists($dir)) {
                                rmdir($dir);
                            }
                        }
                    }
                    $audio->delete();
                }
            }
        }

        if ($request->hasFile('ebooks')) {
            $ebooks = Ebook::all()->where('book_id', $book->id);
            if ($ebooks) {
                foreach ($ebooks as $ebook) {
                    $realPath = storage_path() . '/app/public/upload/files/' . $ebook->file;
                    if (file_exists($realPath)) {
                        unlink($realPath);
                    }
                    if (preg_match('/^(\w+)\//', $ebook->file, $matches)) {
                        $dir = storage_path() . '/app/public/upload/files/' . $matches[1];
                        if (!empty($dir)) {
                            if (file_exists($dir)) {
                                rmdir($dir);
                            }
                        }
                    }
                    $ebook->delete();

                }
            }
        }

        if ($request->hasFile('ebooks')) {
            $ebooks = $request->file('ebooks');
            foreach ($ebooks as $ebook) {
                if ($this->checkEbookMimeType($ebook)) {
                    $save = $this->saveFiles($ebook);
                } else {
                    return ['error' => 'Доступны только docx, doc и pdf форматы'];
                }
                Ebook::create([
                    'file' => $save,
                    'book_id' => $book->id
                ]);
            }
        } elseif ($request->hasFile('audios')) {
            $audios = $request->file('audios');
            foreach ($audios as $audio) {
                if ($this->checkAudioMimeType($audio)) {
                    $save = $this->saveFiles($audio);
                } else {
                    return ['error' => 'Доступно только mp3 формат'];
                }
                Audio::create([
                    'file' => $save,
                    'book_id' => $book->id
                ]);
            }
        }

        return ['status' => 'Книга обновлена'];
    }
}
