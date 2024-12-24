@extends('layouts.app')

@section('content')

<div class="page-wrapper">


    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.all_drivers')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">
                    <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                </li>

                <li class="breadcrumb-item active">
                    {{trans('lang.driver_plural')}}
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

                    <!--     <div class="card-header">
                    <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                    <li class="nav-item">
                    <a class="nav-link active" href="{!! route('drivers') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.driver_table')}}</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{!! route('drivers.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.drivers_create')}}</a>
                    </li>
                    </ul>
                    </div> -->

                    <div class="card-body">

                        <div id="data-table_processing" class="dataTables_processing panel panel-default"
                             style="display: none;">
                            {{trans('lang.processing')}}
                        </div>

                        <div class="userlist-topsearch d-flex mb-3">

                            <div class="userlist-top-left">
                                <a class="nav-link" href="{!! route('drivers.create') !!}"><i
                                            class="fa fa-plus mr-2"></i>{{trans('lang.drivers_create')}}</a>
                            </div>

                            <div id="users-table_filter" class="ml-auto">
                                <label>{{ trans('lang.search_by')}}
                                <div class="form-group mb-0">

                                    <form action="{{ route('drivers') }}" method="get">
                                        @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="prenom" @if ($_GET[
                                            'selected_search']=='prenom')
                                            selected="selected" @endif>{{trans('lang.user_name')}}</option>

                                            <option value="phone" @if ($_GET[
                                            'selected_search']=='phone')
                                            selected="selected" @endif>{{trans('lang.user_phone')}}</option>

                                            <option value="email" @if ($_GET[
                                            'selected_search']=='email')
                                            selected="selected" @endif>{{ trans('lang.email')}}</option>

                                        </select>
                                        @else
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="prenom">{{trans('lang.user_name')}}</option>
                                            <option value="phone">{{trans('lang.user_phone')}}</option>
                                            <option value="email">{{ trans('lang.email')}}</option>
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
                                            <!-- <input type="search" id="search" class="search form-control" placeholder="Search" aria-controls="users-table"></label>-->
                                             &nbsp;<button onclick="searchtext();" class="btn btn-warning">Search</button>&nbsp; 
                                            <!-- <button onclick="searchclear();" class="btn btn-warning btn-flat">Clear</button> -->
                                            <a class="btn btn-warning btn-flat" href="{{url('drivers')}}">Clear</a>
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
                                    <th>{{trans('lang.driver_name')}}</th>
                                    <!-- <th>{{trans('lang.approval_status')}}</th> -->
                                    <th>{{trans('lang.documents')}}</th>
                                    <th>{{trans('lang.email')}}</th>
                                    <th>{{trans('lang.user_phone')}}</th>
                                    <!-- <th>{{trans('lang.vehicle_type')}}</th> -->
                                    <th>{{trans('lang.total_ride')}}</th>
                                    <th>{{trans('lang.wallet_history')}}</th>
                                    <th>{{trans('lang.status')}}</th>
                                    <th>{{trans('lang.online_status')}}</th>
                                    <th>{{trans('lang.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody id="append_list12">
                                @if(count($drivers) > 0)
                                @foreach($drivers as $driver)

                                <tr>
                                    <td class="delete-all"><input type="checkbox"
                                                                  id="is_open_{{$driver->id}}"
                                                                  class="is_open"
                                                                  dataid="{{$driver->id}}"><label
                                                class="col-3 control-label"
                                                for="is_open_{{$driver->id}}"></label></td>

                                    @if (file_exists(public_path('assets/images/driver'.'/'.$driver->photo_path)) &&
                                    !empty($driver->photo_path))
                                    <td> <img class="rounded" style="width:50px"
                                             src="{{asset('assets/images/driver').'/'.$driver->photo_path}}"
                                             alt="image"></td>
                                    @else
                                    <td><img class="rounded" style="width:50px"
                                             src="{{ asset('assets/images/placeholder_image.jpg')}}" alt="image"></td>

                                    @endif
                                    <!-- <td style="display:none;" class="id">{{ $driver->id}}</td> -->
                                    <td><a href="{{route('driver.show', ['id' => $driver->id])}}"> {{ $driver->prenom}} {{ $driver->nom}}</a></td>
                                    {{--@if($driver->status_approval == 'approved')
                                    <td><span class="btn btn-sm btn-success status_approval" data-status="disapproved" id="{{ $driver->id}}" name="approvalStatus">Approved</span></td>

                                    @else
                                    <td><span class="btn btn-sm btn-danger status_approval" data-status="approved" id="{{ $driver->id}}" href="#" name="approvalStatus" value="approved">Disapproved</span></td>
                                    @endif --}}
                                    <td><a href="{{route('driver.documentView', ['id' => $driver->id])}}"><i class="fa fa-file-text"></i></a></td>
                                    <td>{{ $driver->email}}</td>
                                    <td>{{ $driver->phone}}</td>
                                    <!-- <td>{{$driver->libelle}}</td> -->
                                    <?php $count=0;?>
                                    <td>@foreach($totalRide as $ride)
                                            @if($ride->id_conducteur==$driver->id)
                                                <?php $count++; ?>
                                            @endif
                                        @endforeach
                                        <a href="{{route('rides.all',['id'=>$driver->id])}}"><?php echo $count;?></a>
                                    </td>
                                    <td><a href="{{route('walletstransactions.driver',['id'=>$driver->id])}}">{{trans("lang.wallet_history")}}</a></td>

                                    <td>@if ($driver->statut=="yes") <label class="switch"><input type="checkbox" checked id="{{$driver->id}}" name="publish"><span class="slider round"></span></label>
                                      @else <label class="switch"><input type="checkbox" id="{{ $driver->id }}" name="publish"><span class="slider round"></span></label><span>
                                      @endif
                                    </td>
                                    <td>@if ($driver->online=="yes") <span class="btn btn-success btn-sm" id="{{ $driver->id }}">{{trans('lang.online')}} </span>
                                      @else <span class="btn btn-warning btn-sm" id="{{ $driver->id }}"> {{trans('lang.offline')}} </span>
                                      @endif
                                    </td>
                                    <td class="action-btn">
                                      <a data-toggle="dropdown" data-original-title="View détails">
                                      <i
                                      class="fa fa-eye"></i></a>
                                      <a href="{{route('drivers.edit', ['id' => $driver->id])}}"><i
                                                    class="fa fa-edit"></i></a><a id="'+val.id+'" class="do_not_delete"
                                                                                  name="user-delete"
                                                                                  href="{{route('driver.delete', ['id' => $driver->id])}}"><i
                                                    class="fa fa-trash"></i></a>
                                                    <!-- <a
                                                href="{{route('driver.show', ['id' => $driver->id])}}" class=""
                                                data-toggle="dropdown" data-original-title="View détails"><i
                                                    class="fa fa-ellipsis-h"></i></a> -->

                                                  <!-- <a href="{{route('driver.show', ['id' => $driver->id])}}" class="" data-toggle="tooltip" data-original-title="Details"><i class="fa fa-ellipsis-h"></i></a> -->


                                                    <ul class="dropdown-menu">
                                                        <li><a href="{{route('driver.show', ['id' => $driver->id])}}">View détails</a></li>
                                                        <li><a href="{{route('driver.documentView', ['id' => $driver->id])}}">Approved</a></li>
                                                        <!-- <li><a href="#">Disapproved</a></li> -->
                                                    </ul>

                                                    </td>
                                </tr>
                                @endforeach
                                @else
                                	<tr><td colspan="11" align="center">{{trans("lang.no_result")}}</td></tr>
                                @endif
                                </tbody>
                            </table>

                            <nav aria-label="Page navigation example" class="custom-pagination">
                               {{$drivers->appends(request()->query())->links()}}
                            </nav>
{{ $drivers->links('pagination.pagination') }}
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
                var url = "{{url('driver/delete', 'id')}}";
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
           url : 'driver/switch',
           method:"POST",
           data:{'ischeck':ischeck,'id':id},
           success: function(data){

           },
        });

    });

    /*toggal publish action code end*/
</script>

@endsection
