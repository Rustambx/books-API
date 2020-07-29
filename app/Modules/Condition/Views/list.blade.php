@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Vocabularies list -->
            <div class="card">
                <div class="card-header">
                    {{ __('Terms and Conditions') }}

                    @if(auth()->user()->can('ADD_CONDITION'))
                        <div class="nav-actions float-right">
                            <a href="{{ route('condition.add') }}" class="btn btn-sm btn-outline-primary">
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
                            <th scope="col">Title</th>
                            <th scope="col">Text</th>
                            @if(auth()->user()->can('EDIT_CONDITION'))
                                <th scope="col">Edit</th>
                            @endif
                            @if(auth()->user()->can('DELETE_CONDITION'))
                                <th scope="col">Delete</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($conditions as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>
                                    {{ $item->title }}
                                </td>
                                <td>
                                    {{ substr($item->text, 0, 150) }}
                                </td>
                                @if(auth()->user()->can('EDIT_CONDITION'))
                                    <td>
                                        <a href="{{ route('condition.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                                    </td>
                                @endif
                                @if(auth()->user()->can('DELETE_CONDITION'))
                                    <td>
                                        <form action="{{ route('condition.delete', $item->id) }}" method="post">
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

