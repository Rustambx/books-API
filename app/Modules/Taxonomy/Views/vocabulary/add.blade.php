@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-fw fa-plus"></i>
                    {{ __('Add vocabulary') }}
                </div>

                @include('includes.error_messages')

                <div class="card-body">
                    {!! Form::open(['route' => 'taxonomy.vocabulary.save', 'method' => 'post']) !!}

                    <div class="form-group required row">
                        <label for="name" class="control-label col-form-label col-sm-3">
                            {{ __('Name') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('name', old('name'), [
                                'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''),
                                'id' => 'name'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group required row">
                        <label for="slug" class="control-label col-form-label col-sm-3">
                            {{ __('Machine name') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('slug', old('slug'), [
                                'class' => 'form-control'.($errors->has('slug') ? ' is-invalid' : ''),
                                'id' => 'slug'
                            ]) !!}
                            <small class="form-text text-muted">
                                {{ __('Only latin letters and underscores.') }}
                            </small>
                        </div>
                    </div>

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


                    <div class="form-actions row">
                        <div class="col">
                            <button class="btn btn-success" type="submit">
                                <i class="fa fa-check"></i>
                                {{ __('Save') }}
                            </button>
                            <a href="{{ route('taxonomy') }}" class="btn btn-outline-secondary">
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
