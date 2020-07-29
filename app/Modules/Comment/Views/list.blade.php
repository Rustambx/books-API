@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Vocabularies list -->
            <div class="card">
                <div class="card-header">
                    {{ __('Comments') }}

                    @if(auth()->user()->can('ADD_COMMENT'))
                        <div class="nav-actions float-right">
                            <a href="{{ route('comment.add') }}" class="btn btn-sm btn-outline-primary">
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
                            <th scope="col">User</th>
                            <th scope="col">Book</th>
                            <th scope="col">Description</th>
                            @if(auth()->user()->can('EDIT_COMMENT'))
                                <th scope="col">Edit</th>
                            @endif
                            @if(auth()->user()->can('DELETE_COMMENT'))
                                <th scope="col">Delete</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($comments as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>
                                    {{ $item->user->name }}
                                </td>
                                <td>
                                    {{ $item->book->name }}
                                </td>
                                <td>{{ substr($item->description, 0, 150) }}</td>

                                @if(auth()->user()->can('EDIT_COMMENT'))
                                    <td>
                                        <a href="{{ route('comment.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                                    </td>
                                @endif
                                @if(auth()->user()->can('DELETE_COMMENT'))
                                    <td>
                                        <form action="{{ route('comment.delete', $item->id) }}" method="post">
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
