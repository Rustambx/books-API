@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-fw fa-plus"></i>
                    {{ __('Permissions') }}
                </div>

                <div class="card-body">
                    <form action="{{ route('user.permission.save') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                <tr>
                                    <th>Привилегии</th>
                                    @if(!$roles->isEmpty())
                                        @foreach($roles as $item)
                                            <th>{{ $item->name }}</th>
                                        @endforeach
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @if(!$permissions->isEmpty())
                                    @foreach($permissions as $perm)
                                        <tr>
                                            <td>{{ $perm->name }}</td>
                                            @foreach($roles as $role)
                                                <td>
                                                    @if($role->hasPermission($perm->name))
                                                        <input checked type="checkbox" name="{{ $role->id }}[]" value="{{ $perm->id }}">
                                                    @else
                                                        <input type="checkbox" name="{{ $role->id }}[]" value="{{ $perm->id }}">
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="form-actions row">
                                <div class="col">
                                    <button class="btn btn-success" type="submit">
                                        <i class="fa fa-check"></i>
                                        {{ __('Save') }}
                                    </button>
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                        <i class="fa fa-times"></i>
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                    </form>
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
