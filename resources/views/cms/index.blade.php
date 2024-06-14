@extends('layouts.app')

@section('content')

<div class="page-wrapper">

    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.cms_plural')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">
                    <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                </li>

                <li class="breadcrumb-item active">
                    {{trans('lang.cms_plural')}}
                </li>

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

                        <div id="data-table_processing" class="dataTables_processing panel panel-default"
                             style="display: none;">
                            {{trans('lang.processing')}}
                        </div>

                        <div class="userlist-topsearch d-flex mb-3">

                            <div class="userlist-top-left">
                                <a class="nav-link do_not_create" href="{!! route('cms.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.create_page')}}</a>
                            </div>

                            <div id="users-table_filter" class="ml-auto">
                                <label>{{ trans('lang.search_by')}}
                                <div class="form-group mb-0">

                                    <form action="{{ route('cms') }}" method="get">
                                        @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="cms_name" @if ($_GET[
                                            'selected_search']=='cms_name')
                                            selected="selected" @endif>{{trans('Name')}}</option>
                                        </select>
                                        @else
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="cms_name">{{trans('Name')}}</option>
                                        </select>
                                        @endif
                                        <div class="search-box position-relative">
                                            @if(isset($_GET['search']) && $_GET['search'] != '')
                                            <input type="text" class="search form-control" name="search" id="search"
                                                   value="{{$_GET['search']}}">
                                            @else
                                            <input type="text" class="search form-control" name="search" id="search">
                                            @endif
                                            <button type="submit" class="btn-flat position-absolute">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <!-- <input type="search" id="search" class="search form-control" placeholder="Search" aria-controls="users-table"></label>&nbsp;<button onclick="searchtext();" class="btn btn-warning btn-flat">Search</button>&nbsp; -->
                                            <!-- <button onclick="searchclear();" class="btn btn-warning btn-flat">Clear</button> -->
                                            <a class="btn btn-warning btn-flat" href="{{url('cms')}}">Clear</a>
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
                                    <th>{{trans('lang.page_name')}}</th>
                                    <th>{{trans('lang.page_slug')}}</th>
                                    <th>{{trans('lang.status')}}</th>
                                    <th>{{trans('lang.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody id="append_list12">
                                @if(count($cmss) > 0)
                                @foreach($cmss as $cmss12)
                                <tr>
                                    <td class="delete-all"><input type="checkbox"
                                                                  id="is_open_{{$cmss12->cms_id}}"
                                                                  class="is_open"
                                                                  dataid="{{$cmss12->cms_id}}"><label
                                                class="col-3 control-label"
                                                for="is_open_{{$cmss12->cms_id}}"></label></td>

                                    <td>{{ $cmss12->cms_name}}</td>
                                    <td>{{ $cmss12->cms_slug}}</td>
                                    <td>
                                        @if ($cmss12->cms_status=="1")
                                        <label class="switch"><input type="checkbox" checked id="{{$cmss12->cms_id}}" name="publish"><span class="slider round"></span></label>
                                        @elseif ($cmss12->cms_status=="on")
                                        <label class="switch"><input type="checkbox" checked id="{{$cmss12->cms_id}}" name="publish"><span class="slider round"></span></label>
                                        @elseif ($cmss12->cms_status=="yes")
                                      <label class="switch"><input type="checkbox" checked id="{{$cmss12->cms_id}}" name="publish"><span class="slider round"></span></label>
                                        @elseif ($cmss12->cms_status=="Publish")
                                        <label class="switch"><input type="checkbox" checked id="{{$cmss12->cms_id}}" name="publish"><span class="slider round"></span></label>
                                        @elseif ($cmss12->cms_status=="0")
                                        <label class="switch"><input type="checkbox"  id="{{$cmss12->cms_id}}" name="publish"><span class="slider round"></span></label>
                                        @else
                                      <label class="switch"><input type="checkbox"  id="{{$cmss12->cms_id}}" name="publish"><span class="slider round"></span></label>
                                        @endif
                                    </td>

                                    <td class="action-btn">
                                        <a href="{{route('cms.edit', ['id' => $cmss12->cms_id])}}" class="do_not_edit"><i
                                                    class="fa fa-edit"></i></a>
                                        <a id="'+val.id+'" class="do_not_delete" name="user-delete"
                                           href="{{route('cms.destroycms', ['id' => $cmss12->cms_id])}}"><i
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
                                {{ $cmss->appends(request()->query())->links() }}
                            </nav>
                            {{ $cmss->links('pagination.pagination') }}
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


    $(document).ready(function () {
        $(".shadow-sm").hide();
    })

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
                var url = "{{url('cms/destroycms', 'id')}}";
                url = url.replace('id', arrayUsers);

                $(this).attr('href', url);
            }
        } else {
            alert('Please Select Any One Record .');
        }
    });
    /* toggal publish action code start*/
$(document).on("click", "input[name='publish']", function (e) {
     
    var ischeck = $(this).is(':checked');
    var id = this.id;

    $.ajax({
      headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },
       url : 'cms/switch',
       method:"POST",
       data:{'ischeck':ischeck,'id':id},
       success: function(data){

       },
    });

});

/*toggal publish action code end*/
</script>

@endsection
