@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-fw fa-plus"></i>
                    {{ __('Edit User') }}
                </div>

                @include('includes.error_messages')

                <div class="card-body">
                    {!! Form::open(['route' => 'user.save', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                    {!! Form::hidden('edit', 1) !!}
                    {!! Form::hidden('id', $user->id) !!}

                    <div class="form-group required row">
                        <label for="name" class="control-label col-form-label col-sm-3">
                            {{ __('Name') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('name', old('name') ?: $user->name, [
                                'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''),
                                'id' => 'name'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group required row">
                        <label for="name" class="control-label col-form-label col-sm-3">
                            {{ __('Email') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('email', old('email') ?: $user->email, [
                                'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''),
                                'id' => 'email'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group required row">
                        <label for="name" class="control-label col-form-label col-sm-3">
                            {{ __('Password') }}
                        </label>
                        <div class="col-sm-9">
                            <input type="password" id="password" name="password" {{ old('password') }} class="form-control">
                        </div>
                    </div>

                    <div class="form-group required row">
                        <label for="name" class="control-label col-form-label col-sm-3">
                            {{ __('Password confirmation') }}
                        </label>
                        <div class="col-sm-9">
                            <input type="password" id="password" name="password_confirmation" {{ old('password_confirmation') }} class="form-control">
                        </div>
                    </div>

                    <div class="form-group required row">
                        <label for="name" class="control-label col-form-label col-sm-3">
                            {{ __('Roles') }}
                        </label>
                        <div class="col-sm-9">
                            <select name="role_id" id="roles">
                                <option value="0">Выберите роль</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @if($user->role_id == $role->id) selected @endif>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="control-label col-form-label col-sm-3">
                            {{ __('Actual Photo') }}
                        </label>
                        <div class="col-sm-9">
                            <img src="{{ $user->resized_photo }}" alt="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="control-label col-form-label col-sm-3">
                            {{ __('Photo') }}
                        </label>
                        <div class="col-sm-9">
                            <input type="file" name="photo" id="photo" class="form-control resize-vertical">
                        </div>
                    </div>


                    <div class="form-actions row">
                        <div class="col">
                            <button class="btn btn-success" type="submit">
                                <i class="fa fa-check"></i>
                                {{ __('Save') }}
                            </button>
                            <a href="{{ route('user') }}" class="btn btn-outline-secondary">
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

    </script>
@endpush
