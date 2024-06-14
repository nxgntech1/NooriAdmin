@extends('layouts.app')

@section('content')

    <div class="page-wrapper">

        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor">{{ trans('lang.zone') }}</h3>

            </div>

            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('lang.zone') }}</li>
                </ol>
            </div>

            <div>

            </div>

        </div>


        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-body">

                            <div class="userlist-topsearch d-flex mb-3">
                                <div class="userlist-top-left">
                                    <a class="nav-link do_not_create" href="{{ route('zone.create') }}"><i
                                            class="fa fa-plus mr-2"></i>{{ trans('lang.create_zone') }}</a>
                                </div>
                                <div id="users-table_filter" class="ml-auto">
                                    <label>{{ trans('lang.search_by') }}
                                        <div class="form-group mb-0">

                                            <form action="{{ route('zone') }}" method="get">
                                                @if (isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                                    <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                        <option value="name"
                                                            @if ($_GET['selected_search'] == 'name') selected="selected" @endif>
                                                            {{ trans('lang.Name') }}</option>

                                                    </select>
                                                @else
                                                    <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                        <option value="name">{{ trans('lang.Name') }}</option>

                                                    </select>
                                                @endif
                                                <div class="search-box position-relative">
                                                    @if (isset($_GET['search']) && $_GET['search'] != '')
                                                        <input type = "text" class="search form-control" name="search"
                                                            id = "search" value="{{ $_GET['search'] }}">
                                                    @else
                                                        <input type = "text" class="search form-control" name="search"
                                                            id = "search">
                                                    @endif

                                                    <button type="submit" class="btn-flat position-absolute"><i
                                                            class="fa fa-search"></i></button>
                                                    <a class="btn btn-warning btn-flat"
                                                        href="{{ url('zone') }}">Clear</a>
                                                </div>
                                            </form>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="table-responsive m-t-10">


                                <table id="example24"
                                    class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                    cellspacing="0" width="100%">

                                    <thead>

                                        <tr>
                                            <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                    class="col-3 control-label" for="is_active"><a id="deleteAll"
                                                        class="do_not_delete" href="javascript:void(0)"><i
                                                            class="fa fa-trash"></i> All</a></label></th>
                                            <th>{{ trans('lang.currency_name') }}</th>
                                            <!-- <th>{{ trans('lang.vehicle_type') }}</th> -->
                                            <th>{{ trans('lang.currency_status') }}</th>
                                            <th>{{ trans('lang.currency_created') }}</th>
                                            <th>{{ trans('lang.currency_modified') }}</th>
                                            <th>{{ trans('lang.actions') }}</th>

                                        </tr>

                                    </thead>

                                    <tbody id="append_list1">
                                        @if (count($zones) > 0)
                                            @foreach ($zones as $zone)
                                                <tr>
                                                    <td class="delete-all"><input type="checkbox"
                                                            id="is_open_{{ $zone->id }}" class="is_open"
                                                            dataid="{{ $zone->id }}"><label class="col-3 control-label"
                                                            for="is_open_{{ $zone->id }}"></label></td>
                                                    <td><a
                                                            href="{{ route('zone.edit', ['id' => $zone->id]) }}">{{ $zone->name }}</a>
                                                    </td>

                                                    <td>
                                                        @if ($zone->status == 'yes')
                                                            <label class="switch"><input type="checkbox"
                                                                    id="{{ $zone->id }}" name="publish" checked><span
                                                                    class="slider round"></span></label>
                                                        @else
                                                            <label class="switch"><input type="checkbox"
                                                                    id="{{ $zone->id }}" name="publish"><span
                                                                    class="slider round"></span></label>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="date">{{ date('d F Y', strtotime($zone->created_at)) }}</span>
                                                        <span
                                                            class="time">{{ date('h:i A', strtotime($zone->created_at)) }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="date">{{ date('d F Y', strtotime($zone->updated_at)) }}</span>
                                                        <span
                                                            class="time">{{ date('h:i A', strtotime($zone->updated_at)) }}</span>
                                                    </td>
                                                    <td class="action-btn"><a
                                                            href="{{ route('zone.edit', ['id' => $zone->id]) }}"
                                                            class="do_not_edit"><i class="fa fa-edit"></i></a>
                                                        <a href="{{ route('zone.delete', ['id' => $zone->id]) }}"><i
                                                                class="fa fa-trash"></i></a>
                                                        {{-- <a href="{{route('zone.show', ['id' => $zone->id])}}" class="" data-toggle="tooltip" data-original-title="View détails"><i class="fa fa-ellipsis-h"></i></a></td> --}}
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="11" align="center">{{ trans('lang.no_result') }}</td>
                                            </tr>
                                        @endif
                                    </tbody>

                                </table>

                                <nav aria-label="Page navigation example" class="custom-pagination">
                                    {{ $zones->appends(request()->query())->links() }}
                                </nav>
                                {{ $zones->Links('pagination.pagination') }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    </div>
    </div>


@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".shadow-sm").hide();
        })

        $("#is_active").click(function() {
            $("#example24 .is_open").prop('checked', $(this).prop('checked'));

        });

        $("#deleteAll").click(function() {
            if ($('#example24 .is_open:checked').length) {
                if (confirm('Are you sure ? You want to delete these records ?')) {
                    var arrayUsers = [];
                    $('#example24 .is_open:checked').each(function() {
                        var dataId = $(this).attr('dataId');
                        arrayUsers.push(dataId);

                    });
                    arrayUsers = JSON.stringify(arrayUsers);
                    var url = "{{ url('zone/delete', 'id') }}";
                    url = url.replace('id', arrayUsers);

                    $(this).attr('href', url);
                }
            } else {
                alert('Please select atleast any one record');
            }
        });

        $(document).on("click", "input[name='publish']", function(e) {

            var ischeck = $(this).is(':checked');
            var id = this.id;
            console.log(id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route("zone.switch")}}',
                method: "POST",
                data: {
                    'ischeck': ischeck,
                    'id': id
                },
                success: function(data) {

                },
            });

        });

    </script>

@endsection
