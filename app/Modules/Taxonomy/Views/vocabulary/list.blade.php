@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Vocabularies list -->
            <div class="card">
                <div class="card-header">
                    {{ __('Vocabularies') }}

                    @if(auth()->user()->can('ADD_VOCABULARY'))
                        <div class="nav-actions float-right">
                            <a href="{{ route('taxonomy.vocabulary.add') }}" class="btn btn-sm btn-outline-primary">
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
                                <th scope="col">Slug</th>
                                <th scope="col">Description</th>
                                @if(auth()->user()->can('EDIT_VOCABULARY'))
                                    <th scope="col">Update</th>
                                @endif
                                @if(auth()->user()->can('DELETE_VOCABULARY'))
                                    <th scope="col">Delete</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($vocabularies as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                @if(auth()->user()->can('VIEW_TERM_LIST'))
                                    <td><a href="{{ route('taxonomy.terms.list', $item->id) }}">{{ $item->name }}</a></td>
                                @else
                                    <td>{{ $item->name }}</td>
                                @endif
                                <td>{{ $item->slug }}</td>
                                <td>{{ substr($item->description, 0, 150) }}</td>
                                @if(auth()->user()->can('EDIT_VOCABULARY'))
                                    <td>
                                        <a href="{{ route('taxonomy.vocabulary.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                                    </td>
                                @endif
                                @if(auth()->user()->can('DELETE_VOCABULARY'))
                                    <td>
                                        <form action="{{ route('taxonomy.vocabulary.delete', $item->id) }}" method="post">
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
