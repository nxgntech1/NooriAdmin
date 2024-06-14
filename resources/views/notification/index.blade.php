@extends('layouts.app')

@section('content')
<div class="page-wrapper">

    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.notification')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{trans('lang.notification')}}
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


                                <div class="form-group">
                                    <form action="{{ route('notification') }}" method="get">
                                        @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <!-- <option value="name_admin">{{ trans('lang.name_admin')}}</option> -->
                                            <option value="title">{{ trans('lang.title')}}</option>
                                            <option value="message">{{ trans('lang.message')}}</option>
                                        </select>
                                        @else
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <!-- <option value="name_admin">{{ trans('lang.name_admin')}}</option> -->
                                            <option value="title">{{ trans('lang.title')}}</option>
                                            <option value="message">{{ trans('lang.message')}}</option>
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
                                            <a class="btn btn-warning btn-flat" href="{{url('notification')}}">Clear</a>
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
                                    <th>{{trans('lang.title')}}</th>
                                    <th>{{trans('lang.message')}}</th>
                                    <th>{{trans('lang.created')}}</th>
                                    <th>{{trans('lang.actions')}}</th>
                                </tr>

                                </thead>

                                <tbody id="append_list1">

                                @foreach($notifications as $notification)
                                <tr>
                                    <td class="delete-all"><input type="checkbox"
                                                                  id="is_open_{{$notification->id}}"
                                                                  class="is_open"
                                                                  dataid="{{$notification->id}}"><label
                                                class="col-3 control-label"
                                                for="is_open_{{$notification->id}}"></label></td>

                                    <td>{{ $notification->titre}}</td>
                                    <td class="address-td">{{ $notification->message}}</td>
                                    <td class="dt-time"><span class="date">{{ date('d F Y',strtotime($notification->creer))}}</span>
                                                <span class="time">{{ date('h:i A',strtotime($notification->creer))}}</span></td>
                                    <td class="action-btn">
                                        <a href="{{route('notification.show', ['id' => $notification->id])}}" class=""
                                           data-toggle="tooltip" data-original-title="Details"><i
                                                    class="fa fa-ellipsis-h"></i></a>
                                        <a id="'+val.id+'" class="do_not_delete" name="user-delete"
                                           href="{{route('notification.delete', ['id' => $notification->id])}}"><i
                                                    class="fa fa-trash"></i></a>
                                    </td>
                                </tr>

                                @endforeach
                                </tbody>

                            </table>

                            <nav aria-label="Page navigation example" class="custom-pagination">
                                {{ $notifications->links() }}
                            </nav>

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
                var url = "{{url('notification/delete', 'id')}}";
                url = url.replace('id', arrayUsers);

                $(this).attr('href', url);
            }
        } else {
            alert('Please Select Any One Record .');
        }
    });

</script>

@endsection
