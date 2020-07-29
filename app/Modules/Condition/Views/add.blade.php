@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-fw fa-plus"></i>
                    {{ __('Add Condition') }}
                </div>

                @include('includes.error_messages')

                <div class="card-body">
                    {!! Form::open(['route' => 'condition.save', 'method' => 'post']) !!}

                    <div class="form-group required row">
                        <label for="name" class="control-label col-form-label col-sm-3">
                            {{ __('Title') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('title', old('title'), [
                                'class' => 'form-control'.($errors->has('title') ? ' is-invalid' : ''),
                                'id' => 'title'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="control-label col-form-label col-sm-3">
                            {{ __('Text') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::textarea('text', old('text'), [
                                'class' => 'form-control resize-vertical'.($errors->has('text') ? ' is-invalid' : ''),
                                'id' => 'text'
                            ]) !!}
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


@endpush

