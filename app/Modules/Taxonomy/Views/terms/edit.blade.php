@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-fw fa-edit"></i>
                    {{ __('Edit term') }}
                </div>

                <div class="card-body">
                    {!! Form::open(['route' => 'taxonomy.term.save', 'method' => 'post']) !!}
                    {!! Form::hidden('id', $term->id) !!}
                    {!! Form::hidden('edit', 1) !!}
                    {!! Form::hidden('vid', $vocabulary->id) !!}

                    <div class="form-group required row">
                        <label for="name" class="control-label col-form-label col-sm-3">
                            {{ __('Name') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('name', old('name') ?: $term->name, [
                                'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''),
                                'id' => 'name'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="control-label col-form-label col-sm-3">
                            {{ __('Description') }}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::textarea('description', old('description') ?: $term->description, [
                                'class' => 'form-control resize-vertical'.($errors->has('description') ? ' is-invalid' : ''),
                                'id' => 'description'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="parent" class="control-label col-form-label col-sm-3">
                            {{ __('Parent') }}
                        </label>
                        <div class="col-sm-9">
                            <select name="parent" id="parent" class="form-control">
                                <option value="0">{{ __('Root') }}</option>
                                @if ($tree->isNotEmpty())
                                    @foreach ($tree as $row)
                                        @if ($row->id !== $term->id)
                                            <option value="{{ $row->id }}" {!! $term->parent_id == $row->id ? ' selected' :'' !!}>
                                                {{ str_repeat('-', $row->depth) }}&nbsp;{{ $row->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
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
