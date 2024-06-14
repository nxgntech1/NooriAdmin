@extends('layouts.app')

@section('content')
<div class="page-wrapper">


    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.administration_tools_driver_document')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.administration_tools')}}</li>
                <li class="breadcrumb-item active">{{trans('lang.administration_tools_driver_document')}}</li>
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
                                <a class="nav-link do_not_create" href="{{ route('driver_document.create')}}" ><i class="fa fa-plus mr-2"></i>{{trans('lang.driver_document_create')}}</a>
                            </div>
                            <div id="users-table_filter" class="ml-auto">
                              <label>{{ trans('lang.search_by')}}
                                <div class="form-group mb-0">

                                    <form action="{{ route('driver_document') }}" method="get">
                                        @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="title" @if ($_GET[
                                            'selected_search']=='title')
                                            selected="selected" @endif>{{ trans('lang.title')}}</option>

                                        </select>
                                        @else
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="title">{{ trans('lang.title')}}</option>

                                        </select>
                                        @endif
                                        <div class="search-box position-relative">
                                            @if(isset($_GET['search']) && $_GET['search'] != '')
                                            <input type="text" class="search form-control" name="search" id="search"
                                                   value="{{$_GET['search']}}">
                                            @else
                                            <input type="text" class="search form-control" name="search" id="search">
                                            @endif

                                            <button type="submit" class="btn-flat position-absolute"><i
                                                        class="fa fa-search"></i></button>
                                            <a class="btn btn-warning btn-flat"
                                               href="{{url('administration_tools/driver_document')}}">Clear</a>
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
                                                                                            class="do_not_delete"
                                                                                            href="javascript:void(0)"><i
                                                     class="fa fa-trash"></i> All</a></label></th>
                                    <th>{{trans('lang.document_title')}}</th>
                                    <th>{{trans('lang.document_status')}}</th>
                                    <th>{{trans('lang.actions')}}</th>

                                </tr>

                                </thead>

                                <tbody id="append_list1">
                                @if(count($document) > 0)
                                @foreach($document as $doc)
                                <tr>
                                  <td class="delete-all"><input type="checkbox"
                                                                 id="is_open_{{$doc->id}}"
                                                                 class="is_open"
                                                                 dataid="{{$doc->id}}"><label
                                               class="col-3 control-label"
                                               for="is_open_{{$doc->id}}"></label></td>
                                    <td>{{ $doc->title}}</td>

                                    <td>@if ($doc->is_enabled=="Yes") <label class="switch"><input type="checkbox"
                                                                                                    checked
                                                                                                    id="{{$doc->id}}"
                                                                                                    name="publish" class="switchToggal"><span
                                                    class="slider round"></span></label>
                                        @else <label class="switch"><input type="checkbox" id="{{$doc->id}}"
                                                                           name="publish" class="switchToggal"><span
                                                    class="slider round"></span></label><span>
                                      @endif
                                    </td>


                                    <td class="action-btn">
                                        <a href="{{route('driver_document.edit', ['id' => $doc->id])}}" class="do_not_edit"><i
                                                    class="fa fa-edit"></i></a>
                                        <a href="{{route('driver_document.delete', ['id' => $doc->id])}}" class="do_not_delete"><i
                                                                class="fa fa-trash"></i></a>
                                    </td>

                                </tr>
                                @endforeach
                                 @else
                                	<tr><td colspan="11" align="center">{{trans("lang.no_result")}}</td></tr>
                                @endif

                                </tbody>

                            </table>

                            <nav aria-label="Page navigation example" class="custom-pagination">
                                {{ $document->appends(request()->query())->links() }}
                            </nav>
{{ $document->Links('pagination.pagination') }}
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
<script>
    /* toggal publish action code start*/
    $(document).on("click", "input[name='publish']", function (e) {

        var ischeck = $(this).is(':checked');
        var id = this.id;
        var url = "{{ route('driver_document.switch') }}";

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            method: "POST",
            data: {'ischeck': ischeck, 'id': id},
            success: function (data) {
            },
        });


    });
    $("#is_active").click(function () {
        $("#example24 .is_open").prop('checked', $(this).prop('checked'));

    });
    $("#deleteAll").click(function () {
        if ($('#example24 .is_open:checked').length) {
            
            if (confirm('Are You Sure want to Delete Selected Data ?')) {
                var arrayUsers = [];
                $('#example24 .is_open:checked').each(function () {
                    var dataId = $(this).attr('dataId');
                    arrayUsers.push(dataId);

                });

                arrayUsers = JSON.stringify(arrayUsers);
                var url = "{{url('administration_tools/driver_document/delete', 'id')}}";
                url = url.replace('id', arrayUsers);

                $(this).attr('href', url);
            }
        } else {
            alert('Please Select Any One Record .');
        }
    });
   
</script>

@endsection
