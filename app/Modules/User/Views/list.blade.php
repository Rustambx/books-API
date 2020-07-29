@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Vocabularies list -->
            <div class="card">
                <div class="card-header">
                    {{ __('Users') }}

                    @if(auth()->user()->can('ADD_USER'))
                        <div class="nav-actions float-right">
                            <a href="{{ route('user.add') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-plus"></i>
                                {{ __('Add') }}
                            </a>
                        </div>
                    @endif
                </div>

                @include('includes.result_messages')

                <div class="card-body">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Roles</th>
                            <th scope="col">Photo</th>
                            @if(auth()->user()->can('EDIT_USER'))
                                <th scope="col">Edit</th>
                            @endif
                            @if(auth()->user()->can('DELETE_USER'))
                                <th scope="col">Delete</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>
                                    {{ $item->name }}
                                </td>
                                <td>
                                    {{ $item->email }}
                                </td>
                                <td>
                                    @foreach($item->roles as $role)
                                        {{ $role->name }}
                                    @endforeach
                                </td>
                                <td>
                                    <img src="{{ $item->resized_photo }}" alt="">
                                </td>
                                @if(auth()->user()->can('EDIT_USER'))
                                    <td>
                                        <a href="{{ route('user.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                                    </td>
                                @endif
                                @if(auth()->user()->can('DELETE_USER'))
                                    <td>
                                        <form action="{{ route('user.delete', $item->id) }}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#delete-modal').on('show.bs.modal', function (e) {
            var button = $(e.relatedTarget);

            $('#v-name').text(button.attr('data-name'));
            $('#delete-modal input[name="vid"]').val(button.attr('data-id'));
        }).on('hide.bs.modal', function () {
            $('#v-name').empty();
        });
    </script>
@endpush
