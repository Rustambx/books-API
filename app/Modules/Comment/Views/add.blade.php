@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-fw fa-plus"></i>
                    {{ __('Add comment') }}
                </div>

                @include('includes.error_messages')

                <div class="card-body">
                    {!! Form::open(['route' => 'comment.save', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

                    <div class="form-group row">
                        <label for="description" class="control-label col-form-label col-sm-3">
                            {{ __('Description') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::textarea('description', old('description'), [
                                'class' => 'form-control resize-vertical'.($errors->has('description') ? ' is-invalid' : ''),
                                'id' => 'description'
                            ]) !!}
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="book_id" class="control-label col-form-label col-sm-3">
                            {{ __('Book') }}
                        </label>
                        <div class="col-sm-9">
                            <select name="book_id" id="book_id" class="form-control">
                                <option value="0">{{ __('Select book') }}</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="user_id" class="control-label col-form-label col-sm-3">
                            {{ __('User') }}
                        </label>
                        <div class="col-sm-9">
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="0">{{ __('Select user') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-actions row">
                        <div class="col">
                            <button class="btn btn-success" type="submit">
                                <i class="fa fa-check"></i>
                                {{ __('Save') }}
                            </button>
                            <a href="{{ route('comment') }}" class="btn btn-outline-secondary">
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
        $(function () {
            $.get(Laravel.base_url + 'static/images/def.json', function (data) {
                $('#icon').fontIconPicker({
                    theme: 'fip-bootstrap',
                    source: data
                });
            });
        });

@endpush

