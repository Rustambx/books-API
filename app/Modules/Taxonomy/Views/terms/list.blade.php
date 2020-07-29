@extends('layouts.site')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {!! __('Terms in <em>:vocabulary</em> vocabulary', ['vocabulary' => $vocabulary->name]) !!}

                    @if(auth()->user()->can('ADD_TERM'))
                        <div class="nav-actions float-right ml-2">
                            <a href="{{ route('taxonomy.term.add', $vocabulary->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-plus"></i>
                                {{ __('Add') }}
                            </a>
                        </div>
                    @endif

                    <div class="nav-actions float-right">
                        <a href="{{ route('taxonomy') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-undo-alt"></i>
                            {{ __('Back to vocabularies') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            @if(auth()->user()->can('EDIT_TERM'))
                                <th scope="col">Update</th>
                            @endif
                            @if(auth()->user()->can('DELETE_TERM'))
                                <th scope="col">Delete</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody id="sortable">
                            @foreach($tree as $item)
                                <tr class="ui-state-default">
                                    @if($item->parent_id != null)
                                        <th style="padding-left: 70px" scope="row">{{ $item->id }}</th>
                                    @else
                                        <th scope="row">{{ $item->id }}</th>
                                    @endif
                                    <td >{{ $item->name }}</td>
                                    <td>{!! mb_substr($item->description, 0, 100) !!}</td>
                                    @if(auth()->user()->can('EDIT_TERM'))
                                        <td>
                                            <a href="{{ route('taxonomy.term.edit', $item->id) }}" class="btn btn-primary">
                                                Edit
                                            </a>
                                        </td>
                                    @endif
                                    @if(auth()->user()->can('DELETE_TERM'))
                                        <td>
                                            <form action="{{ route('taxonomy.term.delete', $item->id) }}" method="post">
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

                    <div class="cf nestable-lists">
                        <div>
                            <button class="btn btn-outline-secondary mb-2" type="button" id="toggle-collapse">
                                Свернуть/развернуть все
                            </button>
                        </div>

                        <div class="dd nestable" id="nestable">
                            <ol class="dd-list dd3-list">
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('styles')
    <style>
        .dd {
            max-width: 100% !important;
        }
        .button-delete {
            position: absolute;
            top: 3px;
            right: 0;
            padding: 3px 7px !important;
        }

        .button-edit {
            position: absolute;
            top: 1px;
            right: 25px;
        }
        .dd-empty {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
<script>
    $( function() {
        $( "#sortable" ).sortable({
            revert: true
        });
        $( "#draggable" ).draggable({
            connectToSortable: "#sortable",
            helper: "clone",
            revert: "invalid"
        });
        $( "ul, li" ).disableSelection();
    } );

    $(document).ready(function() {
        var obj = '{!! json_encode($tree, JSON_HEX_APOS) !!}';
        var output = '';

        function buildItem(item, child) {
            var html = "<li class='dd-item' data-id='" + item.id + "' data-name='" + item.name + "'>";
            if (child === true) {
                html += '<button class="collapse_btn" data-action="collapse" type="button">Collapse</button><button class="expand_btn" data-action="expand" type="button" style="display: none;">Expand</button>';
            }
            html += "<div class='dd-handle'>" + item.name + "</div>";
            html += '<a href="#delete-modal" data-toggle="modal" data-id="' + item.id + '" data-name="' + item.name + '" class="button-delete text-danger btn-sm float-right" data-owner-id="' + item.id + '">' +
                '<i class="fas fa-times" aria-hidden="true"></i></a>';
            html += '<a href="/cpanel/structure/taxonomy/term/' + item.id + '/edit" class="button-edit text-success btn-sm float-right" data-owner-id="' + item.id +'">' +
                '<i class="fas fa-edit" aria-hidden="true"></i></a>';

            if (item.children) {
                html += "<ol class='dd-list'>";
                $.each(item.children, function (index, sub) {
                    html += buildItem(sub);
                });
                html += "</ol>";
            }

            html += "</li>";

            return html;
        }

        $.each(JSON.parse(obj), function (index, item) {
            output += buildItem(item);
        });

        $('#nestable').nestable({
            group: 1,
            maxDepth: 7
        }).on('change', updateTree);
        $('.dd-list').html(output);

        function updateTree(e)
        {
            var list = e.length ? e : $(e.target), output = list.data('output');
            var data = window.JSON.stringify(list.nestable('serialize'));

            axios.post('{{ route('taxonomy.web-api.rebuild-tree') }}', {
                data: data
            }).then(function (response) {
                toastr.success(response.data.message);
            }).catch(function (error) {
                toastr.error(error.response.data.message);
            });
        }

        $(document).on("click", "#nestable .button-edit", function (e) {
            window.location.href($(this).attr('href'));
        });

        $('#delete-modal').on('show.bs.modal', function (e) {
            var button = $(e.relatedTarget);

            $('#v-name').text(button.attr('data-name'));
            $('#delete-modal input[name="vid"]').val(button.attr('data-id'));
        }).on('hide.bs.modal', function () {
            $('#v-name').empty();
        });

        $('#toggle-collapse').on('click', function () {
            $('.dd3-list > .dd-item').toggleClass('dd-collapsed');
        });
    });
</script>
@endpush
