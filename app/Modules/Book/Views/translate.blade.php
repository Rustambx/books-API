@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-fw fa-plus"></i>
                    {{ __('Add vocabulary') }}
                </div>

                <div class="card-body">
                    {!! Form::open(['route' => 'book.save', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                    {!! Form::hidden('translate', 1) !!}
                    {!! Form::hidden('ref_id', $book->id) !!}

                    <div class="form-group required row">
                        <label for="name" class="control-label col-form-label col-sm-3">
                            {{ __('Name') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('name', old('name') ?: $book->name, [
                                'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''),
                                'id' => 'name'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group required row">
                        <label for="slug" class="control-label col-form-label col-sm-3">
                            {{ __('Price') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('price', old('price') ?: $book->price, [
                                'class' => 'form-control'.($errors->has('price') ? ' is-invalid' : ''),
                                'id' => 'price'
                            ]) !!}
                            <small class="form-text text-muted">
                                {{ __('In cents.') }}
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="control-label col-form-label col-sm-3">
                            {{ __('Description') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::textarea('description', old('description') ?: $book->description, [
                                'class' => 'form-control resize-vertical'.($errors->has('description') ? ' is-invalid' : ''),
                                'id' => 'description'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="control-label col-form-label col-sm-3">
                            {{ __('Image') }}
                        </label>
                        <div class="col-sm-9">
                            <input type="file" name="image" id="image" class="form-control resize-vertical">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="term_id" class="control-label col-form-label col-sm-3">
                            {{ __('Author') }}
                        </label>
                        <div class="col-sm-9">
                            <select name="authors[]" id="authors" class="form-control" multiple>
                                <option value="0">{{ __('Select author') }}</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" @if($author->option == true) selected @endif>{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="control-label col-form-label col-sm-3">
                            Файл
                        </label>
                        <div class="col-sm-9">
                            <div>
                                <input id="book_file" type="radio" name="file_type" value="ebook" checked>
                                <label for="book_file">Несколько глав электронные книги</label>
                                |
                                <input id="chapters_file" type="radio" name="file_type" value="audio">
                                <label for="chapters_file">Несколько глав аудио файлы</label>
                            </div>
                            <div id="chapters" style="display: none">
                                <div id="audio_block" style="margin-bottom: 15px">
                                    <div class="audio-block">
                                        <input type="file" name="audios[]" class ='form-control resize-vertical' id='chapter'>
                                        <span class="delete">x</span>
                                    </div>
                                    {{--{!! Form::file('audios[]', old('description'), [
                                        'class' => 'form-control resize-vertical'.($errors->has('chapter') ? ' is-invalid' : ''),
                                        'id' => 'chapter'
                                    ]) !!}--}}
                                    <br>
                                </div>
                                <button id="add_audio" class="btn btn-info">Добавить еще аудио</button>
                            </div>
                            <div id="ebooks">
                                <div id="ebook_block" style="margin-bottom: 15px">
                                    <div class="file-block">
                                        <input type="file" name="ebooks[]" class ='form-control resize-vertical' id='file'>
                                        <span class="delete">x</span>
                                    </div>
                                    {{--{!! Form::file('ebooks[]', old('description'), [
                                        'class' => 'form-control resize-vertical'.($errors->has('file') ? ' is-invalid' : ''),
                                        'id' => 'file'
                                    ]) !!}--}}
                                </div>
                            </div>
                            <button id="add_ebook" class="btn btn-info">Добавить файл</button>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="control-label col-form-label col-sm-3">
                            {{ __('Language') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::select('language', ['ru'=>'ru', 'en'=>'en'], [
                                'class' => 'form-control resize-vertical'.($errors->has('language') ? ' is-invalid' : ''),
                                'id' => 'language'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="parent_id" class="control-label col-form-label col-sm-3">
                            {{ __('Genres') }}
                        </label>
                        <div class="col-sm-9">
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="0">{{ __('Select genres') }}</option>
                                @if ($genres->isNotEmpty())
                                    @foreach ($genres as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="term_id" class="control-label col-form-label col-sm-3">
                            {{ __('Subgenres') }}
                        </label>
                        <div class="col-sm-9">
                            <select name="genres[]" id="term_id" class="form-control" multiple>
                                <option value="0">{{ __('Select Subgenres') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions row">
                        <div class="col">
                            <button class="btn btn-success" type="submit">
                                <i class="fa fa-check"></i>
                                {{ __('Save') }}
                            </button>
                            <a href="{{ route('book') }}" class="btn btn-outline-secondary">
                                <i class="fa fa-times"></i>
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        // Удаления input
        $(document).on('click', '.delete', function () {
            $(this).parent().remove();
        });

        $(function () {
            var ebookFile = $('#ebooks');
            var chaptersFile = $('#chapters');
            var audio_block = $('#audio_block');
            var add_audio_btn = $('#add_audio');
            var ebook_block = $('#ebook_block');
            var add_ebook_btn = $('#add_ebook');
            var parentSelect = $('#parent_id');
            var termSelect = $('#term_id');
            $('input[name="file_type"]').change(function () {
                if ($(this).val() == "ebooks") {
                    ebookFile.show();
                    chaptersFile.hide();
                    add_audio_btn.hide();
                    add_ebook_btn.show();
                } else {
                    ebookFile.hide();
                    chaptersFile.show();
                    add_ebook_btn.hide();
                    add_audio_btn.show();
                }
            });
            // many files

            add_audio_btn.click(function (event) {
                event.preventDefault();
                var fileInput = '' +
                    '<div class="audio-block">\n' +
                    '<input type="file" name="audios[]" class =\'form-control resize-vertical\' id=\'file\'>\n' +
                    '<span class="delete">x</span>\n' +
                    '</div>';
                audio_block.append(fileInput);
            });

            add_ebook_btn.click(function (event) {
                event.preventDefault();
                var fileInput = '' +
                    '<div class="file-block">\n' +
                    '<input type="file" name="ebooks[]" class =\'form-control resize-vertical\' id=\'file\'>\n' +
                    '<span class="delete">x</span>\n' +
                    '</div>';
                ebook_block.append(fileInput);
            });


            parentSelect.change(function () {
                var parentId =$(this).val();
                var html = "<option value=''>Select Subgenres</option>";
                if (parentId > 0) {
                    $.post("/ajax", {
                        "parent_id" : parentId,
                        "_token" : $('meta[name="csrf-token"]').attr('content')
                    }).done(function (data) {
                        if (data) {
                            $.each(data, function (k, obGenres) {
                                html += '<option value="' + obGenres.id + '">' + obGenres.name + '</option>'
                            });
                            termSelect.html(html)
                        }
                    }).fail(function () {
                        alert("Невозможно подключиться к серверу, повторите попытку позже.");
                    });
                } else {
                    termSelect.html(html);
                }
            });


            $.get(Laravel.base_url + 'static/images/def.json', function (data) {
                $('#icon').fontIconPicker({
                    theme: 'fip-bootstrap',
                    source: data
                });
            });
        });
    </script>
@endpush
