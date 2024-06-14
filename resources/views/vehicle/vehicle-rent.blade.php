@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.vehicle_renting')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{trans('lang.vehicle_rent')}}
                    </li>
                </ol>
            </div>
            <div></div>
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

                                <div id="users-table_filter" class="ml-auto">

                                <!-- <select name="selected_search" id="selected_search" class="form-control input-sm">
									<option value="first_name">{{ trans('lang.first_name')}}</option>
									<option value="last_name">{{ trans('lang.last_name')}}</option>
									<option value="email">{{ trans('lang.email')}}</option>
									</select> --> <!-- <input type="search" id="search" class="search form-control" placeholder="Search"
									aria-controls="users-table">
									</label>&nbsp;<button onclick="searchtext();"
									class="btn btn-warning btn-flat">Search</button>&nbsp;<button onclick="searchclear();"
									class="btn btn-warning btn-flat">Clear</button> -->
                                    <label>{{ trans('lang.search_by')}}
                                    <div class="form-group mb-0">
                                        <form action="{{ route('vehicle-rent') }}" method="get">

                                            @if(isset($_GET['selected_search']) &&  $_GET['selected_search'] != '')
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="vehicle_type" @if ($_GET[
                                            'selected_search']=='vehicle_type')
                                            selected="selected" @endif>{{trans('lang.vehicle_type')}}</option>
                                                    <option value="customer" @if ($_GET[
                                            'selected_search']=='customer')
                                            selected="selected" @endif>{{trans('lang.customer')}}</option>
                                                </select>
                                            @else
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="vehicle_type	">{{trans('lang.vehicle_type')}}</option>
                                                    <option value="customer">{{trans('lang.customer')}}</option>
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
                                                <a class="btn btn-warning btn-flat"
                                                   href="{{url('vehicle/vehicle-rent')}}">Clear</a>
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
                                        <th>{{trans('lang.vehicle_type')}}</th>
                                        <th>{{trans('lang.customer')}}</th>
                                        <th>{{trans('lang.number_of_days')}}</th>
                                        <th>{{trans('lang.start_date')}}</th>
                                        <th>{{trans('lang.end_date')}}</th>
                                        <th>{{trans('lang.contact')}}</th>
                                        <th>{{trans('lang.status')}}</th>
                                        <th>{{trans('lang.created_at')}}</th>
                                        <th>{{trans('lang.modified_at')}}</th>
                                        <th>{{trans('lang.actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="append_list12">
									 @if(count($rentals) > 0)
                                    @foreach($rentals as $rental)
                                        <tr>
                                            <td class="delete-all"><input type="checkbox"
                                                                          id="is_open_{{$rental->id}}"
                                                                          class="is_open"
                                                                          dataid="{{$rental->id}}"><label
                                                        class="col-3 control-label"
                                                        for="is_open_{{$rental->id}}"></label></td>
                                            <td>{{ $rental->libelle}}</td>
                                            <td>{{ $rental->prenom}}</td>
                                            <td>{{ $rental->nb_jour}}</td>
                                            <td><span class="date">{{ date('d F Y',strtotime($rental->date_debut))}}</span></td>
                                            <td><span class="date">{{ date('d F Y',strtotime($rental->date_fin))}}</span></td>
                                            <td>{{ $rental->contact}}</td>
                                            <td>@if ($rental->statut=="in progress")
                                                    <span class="badge badge-success">{{ $rental->statut}}<span> @else
                                                            {{-- <span class="badge badge-warning">{{ $vehicle->statut}}<span> --}}
                                                                <span class="badge badge-warning">{{ $rental->statut}}<span> @endif
                                            </td>
                                             <td class="dt-time"><span class="date">{{ date('d F Y',strtotime($rental->creer))}}</span>
                                                <span class="time">{{ date('h:i A',strtotime($rental->creer))}}</span>
                                             </td>
                                             <td class="dt-time"><span class="date">{{ date('d F Y',strtotime($rental->modifier))}}</span>
                                                <span class="time">{{ date('h:i A',strtotime($rental->modifier))}}</span>
                                             </td>


                                            <td class="action-btn">
                                                <a href="{{route('vehicle-rent.show', ['id' => $rental->id])}}" class=""
                                                   data-toggle="tooltip" data-original-title="Details"><i
                                                            class="fa fa-eye"></i></a>
                                                <a id="'+val.id+'" class="do_not_delete"
                                                   href="{{route('vehicle-rent.delete', ['id' => $rental->id]) }}"><i
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
                                    {{ $rentals->appends(request()->query())->links() }}
                                </nav>
                                {{ $rentals->links('pagination.pagination') }}
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
                    var url = "{{url('vehicle/vehicle-rent/delete', 'id')}}";
                    url = url.replace('id', arrayUsers);

                    $(this).attr('href', url);
                }
            } else {
                alert('Please Select Any One Record .');
            }
        });
       
    </script>

@endsection
