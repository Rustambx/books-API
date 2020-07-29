@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Vocabularies list -->
            <div class="card">
                <div class="card-header">
                    {{ __('Books') }}

                    @if(auth()->user()->can('ADD_BOOK'))
                        <div class="nav-actions float-right">
                            <a href="{{ route('book.add') }}" class="btn btn-sm btn-outline-primary">
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
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Language</th>
                            <th scope="col">Image</th>
                            <th scope="col">Authors</th>
                            <th scope="col">Genres</th>
                            <th scope="col">Description</th>
                            @if(auth()->user()->can('EDIT_BOOK'))
                                <th scope="col">Edit</th>
                            @endif
                            @if(auth()->user()->can('TRANSLATE_BOOK'))
                                <th scope="col">Translate</th>
                            @endif
                            @if(auth()->user()->can('DELETE_BOOK'))
                                <th scope="col">Delete</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($books as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>${{ $item->price_int }}</td>
                                <td>{{ $item->language }}</td>
                                <td>
                                    <img src="{{ $item->resized_image}}" alt="">
                                </td>
                                <td>
                                    @foreach($item->authors as $author)
                                        {{ $author->name }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($item->genres as $genre)
                                        {{ $genre->name }}<br>
                                    @endforeach
                                </td>
                                <td>{!! mb_substr($item->description, 0, 100) !!}</td>
                                @if(auth()->user()->can('EDIT_BOOK'))
                                    <td>
                                        <a href="{{ route('book.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                                    </td>
                                @endif
                                @if(auth()->user()->can('TRANSLATE_BOOK'))
                                    <td>
                                        <a href="{{ route('book.translate', $item->id) }}" class="btn btn-success">Translate</a>
                                    </td>
                                @endif
                                @if(auth()->user()->can('DELETE_BOOK'))
                                    <td>
                                        <form action="{{ route('book.delete', $item->id) }}" method="post">
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

