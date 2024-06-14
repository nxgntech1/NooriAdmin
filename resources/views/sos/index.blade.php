@extends('layouts.app')

@section('content')
<div class="page-wrapper">

    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.sos')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{trans('lang.sos')}}
                </li>
            </ol>
        </div>

    </div>

    <div class="container-fluid">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        <div class="userlist-topsearch d-flex mb-3">

                            <div id="users-table_filter" class="ml-auto">

                                <label>{{ trans('lang.search_by')}}
                                    <div class="form-group">
                                        <form action="{{ route('sos') }}" method="get">
                                            @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                            <select name="selected_search" id="selected_search"
                                                    class="form-control input-sm">
                                                <option value="status" @if ($_GET[
                                            'selected_search']=='status')
                                                            selected="selected" @endif>{{trans('lang.status')}}</option>

                                            </select>
                                            @else
                                            <select name="selected_search" id="selected_search"
                                                    class="form-control input-sm">
                                                <option value="status">{{ trans('lang.status')}}</option>

                                            </select>
                                            @endif
                                            <div class="search-box position-relative">
                                                @if(isset($_GET['search']) && $_GET['search'] != '')
                                                <input type="text" class="search form-control" name="search"
                                                       id="search" value="{{$_GET['search']}}">
                                                <select id="status" class="form-control" name="status" style="display: none">
                                                    <option value="completed">{{ trans('lang.completed')}}</option>
                                                    <option value="processing">{{ trans('lang.processings')}}</option>
                                                    <option value="initiated">{{ trans('lang.initiated')}}</option>
                                                    <option value="user feel">{{ trans('lang.user_feel_do_not_safe')}}</option>
                                                </select>
                                                @elseif(isset($_GET['status']) && $_GET['status'] != '')
                                                    <select id="status" class="form-control" name="status">
                                                        <option class="completed"
                                                            @if($_GET['status']=='completed')selected="selected"
                                                                        @endif value="completed">{{ trans('lang.completed')}}</option>
                                                        <option value="processing"
                                                            @if($_GET['status']=='processing')selected="selected" @endif>{{ trans('lang.processings')}}</option>
                                                        <option value="initiated"
                                                                @if($_GET['status']=='initiated')selected="selected" @endif>{{ trans('lang.initiated')}}</option>
                                                        <option value="user feel"
                                                                @if($_GET['status']=='user feel do not safe')selected="selected" @endif>{{ trans('lang.user_feel_do_not_safe')}}</option>
                                                    </select>
                                                    <input type="text" id="search" name="search" class="search form-control" placeholder="Search" style="display: none">
                                                @else
                                                <select id="status" class="form-control" name="status">
                                                    <option value="completed">{{ trans('lang.completed')}}</option>
                                                    <option value="processing">{{ trans('lang.processings')}}</option>
                                                    <option value="initiated">{{ trans('lang.initiated')}}</option>
                                                    <option value="user feel">{{ trans('lang.user_feel_do_not_safe')}}</option>
                                                </select>
                                                @endif

                                                <button onclick="searchtext();" class="btn btn-warning">{{trans('lang.search')}}</button>

                                                <!-- <button type="submit" class="btn-flat position-absolute"><i
                                                            class="fa fa-search"></i></button> -->

                                                <a class="btn btn-warning btn-flat" href="{{url('sos')}}">Clear</a>
                                            </div>
                                        </form>
                                    </div>
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
                                    <th>{{trans('lang.ride_id')}}</th>
                                    {{--
                                    <th>{{trans('lang.sos_latitude')}}</th>
                                    <th>{{trans('lang.sos_longitude')}}</th>
                                    --}}
                                    <th>{{trans('lang.user_name')}}</th>
                                    <th>{{trans('lang.driver_name')}}</th>

                                    <th>{{trans('lang.sos_status')}}</th>
                                    <th>{{trans('lang.created')}}</th>

                                    <th>{{trans('lang.actions')}}</th>
                                </tr>

                                </thead>

                                <tbody id="append_list1">
								@if(count($sos) > 0)
                                @foreach($sos as $so)
                                <tr>
                                    <td class="delete-all"><input type="checkbox"
                                                                  id="is_open_{{$so->id}}"
                                                                  class="is_open"
                                                                  dataid="{{$so->id}}"><label
                                                class="col-3 control-label"
                                                for="is_open_{{$so->id}}"></label></td>

                                    <td>
                                       {{-- <a href="{{route('ride.show', ['id' => $so->ride_id])}}">{{ $so->ride_id}}</a> --}}
                                       <a href="{{route('sos.show',['id'=>$so->id])}}">{{ $so->ride_id}}</a>
                                    </td>
                                    {{--
                                    <td>{{ $so->latitude}}</td>
                                    <td>{{ $so->longitude}}</td>
                                    --}}
                                    <td><a href="{{route('users.show', $so->id_user_app)}}">{{$so->userPreNom}} {{$so->userNom}}<a></td>
                                    <td><a href="{{route('driver.show', $so->id_conducteur)}}">{{$so->driverPreNom}} {{$so->driverNom}}</a></td>


                                    <td>
                                        @if($so->status=='initiated')
                                        <span class="badge badge-warning">{{ $so->status }}</span>
                                        @elseif($so->status=='processing')
                                        <span class="badge badge-primary">{{$so->status}}</span>
                                        @elseif($so->status == 'user feel not safe')
                                        <span class="badge badge-danger">User Feel do not Safe</span>
                                        @elseif($so->status == 'driver feel not safe')
                                        <span class="badge badge-danger">Driver Feel do not Safe</span>
                                        @else
                                        <span class="badge badge-success">{{$so->status}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="date">{{ date('d F Y',strtotime($so->creer))}}</span>
                                        <span class="time">{{ date('h:i A',strtotime($so->creer))}}</span>
                                    </td>

                                    <td class="action-btn">
                                        <a href="{{route('sos.show',['id'=>$so->id])}}" data-toggle="tooltip"
                                           data-original-title="Details"><i
                                                    class="fa fa-eye"></i></a>
                                        <a id="'+val.id+'"
                                           class="do_not_delete"
                                           name="user-delete"
                                           href="{{route('sos.delete', ['id' => $so->id])}}"><i
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
                                {{ $sos->appends(request()->query())->links() }}
                            </nav>
                              {{ $sos->links('pagination.pagination') }}
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
                var url = "{{url('sos/delete', 'id')}}";
                url = url.replace('id', arrayUsers);

                $(this).attr('href', url);
            }
        } else {
            alert('Please Select Any One Record .');
        }
    });
    
</script>

@endsection
