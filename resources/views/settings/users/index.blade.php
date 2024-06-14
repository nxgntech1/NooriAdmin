@extends('layouts.app')

@section('content')
    <div class="page-wrapper">

        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.user_plural')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item active">Users</li>
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
                                 style="display: none;">{{trans('lang.processing')}}
                            </div>
                            <div class="userlist-topsearch d-flex mb-3">
                                <div class="userlist-top-left">
                                    <a class="nav-link" href="{!! route('users.create') !!}"><i
                                                class="fa fa-plus mr-2"></i>{{trans('lang.user_create')}}</a>
                                </div>
                                <div id="users-table_filter" class="ml-auto">
                                    <label>{{ trans('lang.search_by')}}
                                    <div class="form-group mb-0">
                                        <form action="{{ route('users') }}" method="get">

                                            @if(isset($_GET['selected_search']) &&  $_GET['selected_search'] != '')
                                                <?php //dd($_GET['selected_search']);?>
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="prenom" @if ($_GET['selected_search']=='prenom')
                                                    selected="selected" @endif >{{ trans('lang.user_name')}}</option>

                                                    <option value="email" @if ($_GET['selected_search']=='email')
                                                    selected="selected" @endif>{{trans('lang.email')}}</option>

                                                    <option value="phone" @if ($_GET['selected_search']=='phone')
                                                    selected="selected" @endif>{{trans('lang.user_phone')}}</option>
                                                </select>
                                            @else
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="prenom">{{ trans('lang.user_name')}}</option>
                                                    <option value="email">{{trans('lang.email')}}</option>
                                                    <option value="phone">{{trans('lang.user_phone')}}</option>
                                                </select>
                                            @endif
                                            <div class="search-box position-relative">
                                                @if(isset($_GET['search']) &&  $_GET['search'] != '')
                                                    <input type="text" class="search form-control" name="search"
                                                           id="search" value="{{$_GET['search']}}">
                                                @else
                                                    <input type="text" class="search form-control" name="search"
                                                           id="search">
                                                @endif
                                                <button type="submit" class="btn-flat position-absolute"><i
                                                            class="fa fa-search"></i></button>
                                                <!-- <input type="search" id="search" class="search form-control" placeholder="Search" aria-controls="users-table"></label>&nbsp;<button onclick="searchtext();" class="btn btn-warning btn-flat">Search</button>&nbsp; -->
                                                <!-- <button onclick="searchclear();" class="btn btn-warning btn-flat">Clear</button> -->
                                                <a class="btn btn-warning btn-flat" href="{{url('users')}}">Clear</a>
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
                                        <th>{{trans('lang.extra_image')}}</th>
                                        <th>{{trans('lang.user_name')}}</th>
                                        <th>{{trans('lang.email')}}</th>
                                        <th>{{trans('lang.wallet_history')}}</th>
                                        <th>{{trans('lang.user_phone')}}</th>
                                        <th>{{trans('lang.status')}}</th>
                                        <th>{{trans('lang.actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="append_list12">
                                     @if(count($users) > 0)
                                    @foreach($users as $customer)
                                        <tr>
                                            <td class="delete-all"><input type="checkbox"
                                                                          id="is_open_{{$customer->id}}"
                                                                          class="is_open"
                                                                          dataid="{{$customer->id}}"><label
                                                        class="col-3 control-label"
                                                        for="is_open_{{$customer->id}}"></label></td>

                                            
                                            {{--@if (file_exists('assets/images/users'.'/'.$customer->photo_path) && !empty($customer->photo_path))--}}
                                            @if (file_exists(public_path('assets/images/users'.'/'.$customer->photo_path)) && !empty($customer->photo_path))
                                                <td><img class="rounded" style="width:50px"
                                                         src="{{asset('assets/images/users').'/'.$customer->photo_path}}"
                                                         alt="image"></td>
                                            @else
                                                <td><img class="rounded" style="width:50px"
                                                         src="{{asset('assets/images/placeholder_image.jpg')}}"
                                                         alt="image"></td>

                                            @endif
                                            <td><a href="{{route('users.show', ['id' => $customer->id])}}">{{ $customer->prenom}} {{ $customer->nom}}</a>
                                            </td>
                                            <td>{{ $customer->email}}</td>
                                            <td><a href="{{route('users.walletstransaction',['id'=>$customer->id])}}">{{trans("lang.wallet_history")}}</a></td>
                                            <td>{{ $customer->phone}}</td>

                                                @if ($customer->statut=="yes")
                                              <td>  <label class="switch"><input type="checkbox" checked id="{{$customer->id}}" name="publish"><span class="slider round"></span></label></td>

                                                @else
                                                  <td><label class="switch"><input type="checkbox" id="{{$customer->id}}" name="publish"><span class="slider round"></span></label></td>
                                                @endif

                                            <td class="action-btn">
                                              <a href="{{route('users.show', ['id' => $customer->id])}}" class=""
                                                 data-toggle="tooltip" data-original-title="Details"><i
                                                          class="fa fa-eye"></i></a>
                                              <a
                                                        href="{{route('users.edit', ['id' => $customer->id])}}"><i
                                                            class="fa fa-edit"></i></a><a id="'+val.id+'"
                                                                                          class="do_not_delete"
                                                                                          name="user-delete"
                                                                                          href="{{route('user.delete', ['id' => $customer->id])}}"><i
                                                            class="fa fa-trash"></i></a>
                                                </td>
                                        </tr>
                                    @endforeach
                                    @else
                                	<tr><td colspan="11" align="center">{{trans("lang.no_result")}}</td></tr>
                                @endif
                                    </tbody>
                                </table>


                            <!-- {{ $users->onEachSide(5)->links() }} -->
                                <nav aria-label="Page navigation example" class="custom-pagination">
                                    {{ $users->appends(request()->query())->links() }}
                                </nav>
                                {{ $users->links('pagination.pagination') }}
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
                    var url = "{{url('user/delete', 'id')}}";
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
       console.log(id);
       $.ajax({
         headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
          url : '/switch',
          method:"POST",
          data:{'ischeck':ischeck,'id':id},
          success: function(data){

          },
       });

   });

   /*toggal publish action code end*/
    </script>

@endsection
