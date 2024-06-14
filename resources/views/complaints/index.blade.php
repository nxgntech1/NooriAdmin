@extends('layouts.app')

@section('content')
<div class="page-wrapper">

    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.complaints')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{trans('lang.complaints')}}
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

                                        <form action="{{ route('complaints') }}" method="get">
                                            @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                            <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                                <option value="title" @if($_GET['selected_search']=='title' )
                                                    selected="selected" @endif>{{ trans('lang.title')}}</option>
                                                <option value="message" @if($_GET['selected_search']=='message' )
                                                    selected="selected" @endif>{{ trans('lang.message')}}</option>
                                                <option value="status" @if($_GET['selected_search']=='status' )
                                                    selected="selected" @endif>{{trans('lang.status')}}</option>
                                            </select>
                                            @else
                                            <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                                <option value="title">{{ trans('lang.title')}}</option>
                                                <option value="message">{{ trans('lang.message')}}</option>
                                                <option value="status">{{trans('lang.status')}}</option>

                                            </select>
                                            @endif
                                            <div class="search-box position-relative">
                                                @if(isset($_GET['search']) && $_GET['search'] != '')
                                                <input type="text" class="search form-control" name="search" id="search"
                                                    value="{{$_GET['search']}}">
                                                <select id="status" class="form-control" name="status"
                                                    style="display: none">
                                                    <option value="initiated">{{ trans('lang.initiated')}}</option>
                                                    <option value="processing">{{ trans('lang.processing')}}</option>
                                                    <option value="completed">{{ trans('lang.completed')}}</option>
                                                </select>
                                                @elseif(isset($_GET['status']) && $_GET['status']!='')
                                                <input type="text" class="search form-control" name="search" id="search"
                                                    style="display:none">
                                                <select id="status" class="search form-control" name="status">
                                                    <option @if($_GET['status']=='initiated' )selected="selected" @endif
                                                        value="initiated">{{ trans('lang.initiated')}}</option>

                                                    <option @if($_GET['status']=='processing' )selected="selected"
                                                        @endif value="processing">{{ trans('lang.processing')}}</option>

                                                    <option @if($_GET['status']=='completed' )selected="selected" @endif
                                                        value="completed">{{ trans('lang.completed')}}</option>
                                                </select>

                                                @else
                                                <input type="text" class="search form-control" name="search"
                                                    id="search">
                                                <select id="status" class="form-control" name="status"
                                                    style="display: none">
                                                    <option value="initiated">{{ trans('lang.initiated')}}</option>
                                                    <option value="processing">{{ trans('lang.processing')}}</option>
                                                    <option value="completed">{{ trans('lang.completed')}}</option>
                                                </select>

                                                @endif
                                                <button type="submit" class="btn-flat position-absolute"><i
                                                        class="fa fa-search"></i></button>
                                                <a class="btn btn-warning btn-flat"
                                                    href="{{url('complaints')}}">Clear</a>
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
                                        <th>{{trans('lang.order_id')}}</th>
                                        <th>{{trans('lang.order_type')}}</th>
                                        <th>{{trans('lang.driver_plural')}}</th>
                                        <th>{{trans('lang.userName')}}</th>
                                        <th>{{trans('lang.title')}}</th>
                                        <th>{{trans('lang.complaint_by')}}</th>
                                        <th>{{trans('lang.status')}}</th>
                                        <th>{{trans('lang.created')}}</th>
                                        <th>{{trans('lang.actions')}}</th>
                                    </tr>

                                </thead>

                                <tbody id="append_list1">
                                    @if(count($complaints) > 0)
                                    @foreach($complaints as $complaint)

                                    <tr>
                                        <td class="delete-all"><input type="checkbox" id="is_open_{{$complaint->id}}"
                                                class="is_open" dataid="{{$complaint->id}}"><label
                                                class="col-3 control-label" for="is_open_{{$complaint->id}}"></label>
                                        </td>

                                        @if(!empty($complaint->id_ride))
                                        @if(!empty($complaint->rideId))
                                        <td><a href="{{route('ride.show', ['id' => $complaint->id_ride])}}">{{
                                                $complaint->id_ride}}</a></td>
                                         @else<td></td>
                                         @endif       
                                        <td>{{trans('lang.ride')}}
                                        </td>
                                        @else
                                        @if(!empty($complaint->parcelId))
                                        <td><a href="{{route('parcel.show', ['id' => $complaint->id_parcel])}}">{{
                                                $complaint->id_parcel}}</a></td>
                                        @else<td></td>
                                        @endif
                                        <td>{{trans('lang.parcel')}}</td>
                                        @endif
                                        <td><a href="{{route('driver.show', ['id' => $complaint->driverId])}}">{{
                                                $complaint->driverName}}</a></td>
                                        <td><a href="{{route('users.show', ['id' => $complaint->userId])}}">{{
                                                $complaint->userName}}</a></td>
                                        <td>{{ $complaint->title}}</td>
                                        <td>{{$complaint->user_type}}</td>
                                        <td>
                                         @if($complaint->status=="completed")
                                          <span class="badge badge-success">{{ $complaint->status }}</span>
                                        @elseif($complaint->status == "processing")                                      
                                            <span class="badge badge-warning"> {{$complaint->status}}</span>
                                        @elseif($complaint->status == "initiated")                                      
                                            <span class="badge badge-primary"> {{$complaint->status}}</span>
                                        @endif
                                        </td>
                                        <td>
                                            <span class="date">{{ date('d F Y',strtotime($complaint->created))}}</span>
                                            <span class="time">{{ date('h:i A',strtotime($complaint->created))}}</span>
                                        </td>
                                        <td class="action-btn">
                                            <a href="javascript:void(0)" id="{{$complaint->id}}" class="complaint-show"
                                                data-toggle="tooltip" data-original-title="Details"><i
                                                    class="fa fa-eye"></i></a>
                                            <a id="'+val.id+'" class="do_not_delete" name="user-delete"
                                                href="{{route('complaints.delete', ['id' => $complaint->id])}}"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>

                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="11" align="center">{{trans("lang.no_result")}}</td>
                                    </tr>
                                    @endif
                                </tbody>

                            </table>

                            <nav aria-label="Page navigation example" class="custom-pagination">
                                {{$complaints->appends(request()->query())->links()}}
                            </nav>
                            {{ $complaints->links('pagination.pagination') }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="showComplaintModal" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered location_modal">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title locationModalTitle">{{trans('lang.complaint_detail')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <form action="{{route('complaints.update')}}" method="post" class="">
                    @csrf

                    <div class="form-row">

                        <div class="form-group row">
                            <input type="text" name="complaint_id" id="complaint_id" hidden>
                            <div class="form-group row width-100">
                                <label class="col-12 control-label">{{
                                    trans('lang.title')}}</label>

                                <div class="col-12 title">
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-12 control-label">{{
                                    trans('lang.message')}}</label>

                                <div class="col-12 message">
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-12 control-label">{{
                                    trans('lang.status')}}</label>
                                <div class="col-12">
                                    @php
                                    $status = ['initiated' => 'initiated', 'processing'=> 'processing','completed' =>
                                    'completed',]
                                    @endphp
                                    <select name="complaint_status" class="form-control" class="status"
                                        id="complaint_status">
                                        @foreach ($status as $key => $value)
                                        <option value="{{ $key }}"> {{ $value }}
                                        </option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="add-wallet-btn">{{trans('submit')}}</a>
                        </button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                            {{trans('close')}}</a>
                        </button>

                    </div>
                </form>


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
                var url = "{{url('complaints/delete', 'id')}}";
                url = url.replace('id', arrayUsers);

                $(this).attr('href', url);
            }
        } else {
            alert('Please Select Any One Record .');
        }
    });

    $('.complaint-show').on('click', function () {
        var id = this.id;
        var url = "{{url('complaints/show', 'id')}}";
        url = url.replace('id', id);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('.title').text(data.title);
                $('.message').text(data.description);
                $('#complaint_status [value="' + data.status + '"]').attr('selected', 'true');
                $('#complaint_id').val(id);
                $('#showComplaintModal').modal('show');
            },
        })
    })
    $(document).ready(function () {

        if ($('#selected_search').val() == "status") {
            jQuery('#search').val('');
        } else {
            jQuery('#status').val('');

        }
    })

    $(document.body).on('change', '#selected_search', function () {

        if (jQuery(this).val() == 'status') {
            jQuery('#status').show();
            jQuery('#status').val('initiated');
            jQuery('#search').val('');
            jQuery('#search').hide();
        } else {
            jQuery('#status').hide();
            jQuery('#status').val('');
            jQuery('#search').show();

        }
    });

</script>

@endsection