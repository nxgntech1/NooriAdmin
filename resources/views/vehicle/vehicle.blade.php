@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.vehicle')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item active">{{trans('lang.vehicle')}}</li>
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
                                 style="display: none;">{{trans('lang.processing')}}</div>

                            <div class="userlist-topsearch d-flex mb-3">

                                <div class="userlist-top-left">
                                    <a class="nav-link" href="{!! route('vehicle.vehicle_create') !!}"><i
                                                class="fa fa-plus mr-2"></i>{{trans('lang.create_vehicle')}}</a>
                                </div>

                                <div id="users-table_filter" class="ml-auto">

                                    <div class="form-group">

                                        <form action="{{ route('vehicle') }}" method="get">


                                            @if(isset($_GET['selected_search']) &&  $_GET['selected_search'] != '')
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="libelle" @if ($_GET['selected_search']=='libelle')
                                                    selected="selected" @endif>{{trans('lang.vehicle_type')}}</option>
                                                    <option value="number" @if ($_GET['selected_search']=='number')
                                                    selected="selected" @endif>{{trans('lang.number')}}</option>
                                                </select>
                                            @else
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="libelle">{{trans('lang.vehicle_type')}}</option>
                                                    <option value="number">{{trans('lang.number')}}</option>
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
                                                <a class="btn btn-warning btn-flat" href="{{url('vehicle/vehicle')}}">Clear</a>
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
                                        <th>{{trans('lang.extra_image')}}</th>
                                        <th>{{trans('lang.vehicle_type')}}</th>
                                        <th>{{trans('lang.number')}}</th>
                                        <th>{{trans('lang.status')}}</th>
                                        <th>{{trans('lang.created_at')}}</th>
                                        <th>{{trans('lang.modified_at')}}</th>
                                        <th>{{trans('lang.actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="append_list12">
                                    @if(count($vehicles) > 0)
                                    @foreach($vehicles as $vehicle)
                                        <tr>
                                            <td class="delete-all"><input type="checkbox"
                                                                          id="is_open_{{$vehicle->id}}"
                                                                          class="is_open"
                                                                          dataid="{{$vehicle->id}}"><label
                                                        class="col-3 control-label"
                                                        for="is_open_{{$vehicle->id}}"></label></td>

                                        @if ($vehicle->image === "")
                                                <td><img class="rounded" style="width:50px"
                                                         src="{{asset('images/user.png')}}" alt="image"></td>
                                            @else
                                                <td><img class="rounded" style="width:50px"
                                                         src="{{asset('/images/app_user/'.$vehicle->image)}}"
                                                         alt="image"></td>
                                            @endif
                                            <td>{{ $vehicle->libelle}}</td>
                                            <td>{{ $vehicle->nombre}}</td>
                                        <!-- <td>{{ $vehicle->statut}}</td> -->
                                            <td>@if ($vehicle->statut=="yes")
                                                    <label class="switch"><input type="checkbox" checked id="{{$vehicle->id}}" name="publish"><span class="slider round"></span></label>
                                            @else
                                                <!-- <span class="badge badge-warning">{{ $vehicle->statut}}<span> -->
                                                  <label class="switch"><input type="checkbox" id="{{$vehicle->id}}" name="publish"><span class="slider round"></span></label>
                                                @endif
                                            </td>
                                            <td class="dt-time"><span class="date">{{ date('d F Y',strtotime($vehicle->creer))}}</span>
                                                <span class="time">{{ date('h:i A',strtotime($vehicle->creer))}}</span>
                                     </td>
                                    <td class="dt-time">
@if($type->modifier >= $vehicle->creer)
    <span class="date">{{ date('d F Y',strtotime($type->modifier))}}</span>
    <span class="time">{{ date('h:i A',strtotime($type->modifier))}}</span>
@endif
                                                
                                    </td>


                                            <td class="action-btn"><a class="edit_type_vehicle" href="{{route('vehicle.vehicle_edit', ['id' => $vehicle->id])}}"><i
                                                            class="fa fa-edit"></i></a><a id="'+val.id+'"
                                                                                          class="do_not_delete"
                                                                                          href="{{route('vehicle.delete', ['id' => $vehicle->id])}}"><i
                                                            class="fa fa-trash"></i></a></td>

                                            <!-- <div class="modal fade" id="exampleModal_{{$vehicle->id}}" tabindex="-1"
                                                 role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="exampleModalLabel">{{trans('lang.vehicle_type')}}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="error_top"></div>
                                                            @if($errors->any())
                                                                <div class="alert alert-danger">
                                                                    <ul>
                                                                        @foreach($errors->all() as $error)
                                                                            <li>{{ $error }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                            <form method="post"
                                                                  action="{{ route('vehicle.update',$vehicle->id) }}"
                                                                  enctype="multipart/form-data">
                                                                @csrf
                                                                @method("PUT")
                                                                <div class="form-group">
                                                                    <label>{{trans('lang.vehicle_type')}}</label>
                                                                    <select name="type" class="form-control">
                                                                        @foreach($types as $type)
                                                                            <option value="{{ $type->id }}" {{ $type->id == $vehicle->id_type_vehicule_rental ? 'selected' : '' }}>{{ $type->libelle }}</option>                                                @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('lang.price')}}</label>
                                                                    <input type="text" class="form-control" name="price"
                                                                           value="{{$vehicle->prix}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('lang.places')}}</label>
                                                                    <input type="text" class="form-control"
                                                                           name="places" value="{{$vehicle->nb_place}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('lang.vehicle_number')}}</label>
                                                                    <input type="text" class="form-control"
                                                                           name="vehicle_number"
                                                                           value="{{$vehicle->nombre}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="file" class="form-control"
                                                                           name="image">
                                                                </div>
                                                                <img src="/images/app_user/{{ $vehicle->image}}"
                                                                     width="300" height="200">
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">{{trans('lang.close')}}</button>
                                                            <button type="submit"
                                                                    class="btn btn-primary">{{trans('lang.save_changes')}}</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div> -->

                                        </tr>
                                    @endforeach
                                      @else
                                		<tr><td colspan="11" align="center">{{trans("lang.no_result")}}</td></tr>
                                	@endif
                                    </tbody>
                                </table>


                                <nav aria-label="Page navigation example" class="custom-pagination">
                                    {{ $vehicles->links() }}
                                </nav>

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
                    var url = "{{url('vehicle/vehicle/delete', 'id')}}";
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
               url : '../vehicle/switch',
               method:"POST",
               data:{'ischeck':ischeck,'id':id},
               success: function(data){

               },
            });

        });

        /*toggal publish action code end*/
    </script>

@endsection
