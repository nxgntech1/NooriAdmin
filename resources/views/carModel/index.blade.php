@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.car_model')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item active">{{trans('lang.car_model')}}</li>
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
                                    <a class="nav-link do_not_create" href="{!! route('car_model.create') !!}"><i
                                                class="fa fa-plus mr-2"></i>{{trans('lang.car_model_create')}}</a>
                                </div>
                                <div id="users-table_filter" class="ml-auto">
                                    <label>{{ trans('lang.search_by')}}
                                    <div class="form-group mb-0">
                                        <form action="{{ route('car_model') }}" method="get">
                                            @if(isset($_GET['selected_search']) &&  $_GET['selected_search'] != '')
                                                <?php //dd($_GET['selected_search']);?>
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="name" @if ($_GET['selected_search']=='name')
                                                    selected="selected" @endif >{{ trans('lang.model_name')}}</option>
                                                </select>
                                            @else
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="name">{{ trans('lang.model_name')}}</option>
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
                                                <a class="btn btn-warning btn-flat" href="{{url('car_model')}}">Clear</a>
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
                                        <th>{{trans('lang.model_name')}}</th>
                                        <th>{{trans('lang.brand')}}</th>
                                        <th>{{trans('lang.vehicle_type')}}</th>
                                        <th>{{trans('lang.vehicles_count')}}</th>
                                        <th>{{trans('lang.status')}}</th>
                                        <th>{{trans('lang.allow_cod')}}</th>
                                        <th>{{trans('lang.actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="append_list12">
                                    @if(count($carModel) > 0)
                                    @foreach($carModel as $value)
                                        <tr>
                                            <td class="delete-all"><input type="checkbox"
                                                                          id="is_open_{{$value->id}}"
                                                                          class="is_open"
                                                                          dataid="{{$value->id}}"><label
                                                        class="col-3 control-label"
                                                        for="is_open_{{$value->id}}"></label></td>
                                                        @if (!empty($value->imageid))
                                    <td> <img class="rounded" style="width:50px"
                                             src="{{asset('assets/images/vehicle_models').'/'.$value->imageid}}"
                                             alt="image"></td>
                                    @else
                                    <td><img class="rounded" style="width:50px"
                                             src="{{ asset('assets/images/placeholder_img_car.png')}}" alt="image"></td>

                                    @endif
                                            <td><a href="{{route('car_model.edit', ['id' => $value->id])}}">{{ $value->name}}</a> </td>

                                            <td>@foreach ($brand as $brandVal)
                                                    @if($value->brand_id==$brandVal->id)
                                                    <a href="{{route('brand.edit', ['id' => $brandVal->id])}}">{{$brandVal->name}}</a>
                                                    @endif
                                                @endforeach</td>


                                            <td>@foreach ($vehicleType as $brandVal)
                                                    @if($value->vehicle_type_id==$brandVal->id)
                                                    {{$brandVal->libelle}}
                                                    @endif
                                                @endforeach</td>
                                                <td>{{ $value->vehicle_count}}</td>
                                              <td>  @if ($value->status=="yes")
                                                    <label class="switch"><input type="checkbox" id="{{$value->id}}" name="publish" checked><span class="slider round"></span></label>
                                                @else
                                                <label class="switch"><input type="checkbox"  id="{{$value->id}}" name="publish"><span class="slider round"></span></label>
                                                @endif
                                            </td>
                                            <td>  @if ($value->allow_cod=="yes")
                                                    <label class="switch"><input type="checkbox" id="{{$value->id}}" name="allowcod" checked><span class="slider round"></span></label>
                                                @else
                                                <label class="switch"><input type="checkbox"  id="{{$value->id}}" name="allowcod"><span class="slider round"></span></label>
                                                @endif
                                            </td>
                                            
                                            <td class="action-btn"><a
                                                        href="{{route('car_model.edit', ['id' => $value->id])}}" class="do_not_edit"><i
                                                            class="fa fa-edit"></i></a><a id="'+val.id+'"
                                                                                          class="do_not_delete"
                                                                                          name="user-delete"
                                                                                          href="{{route('car_model.delete', ['id' => $value->id])}}"><i
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
                                    {{ $carModel->appends(request()->query())->links()  }}
                                </nav>
                                {{ $carModel->links('pagination.pagination') }}
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
                    var url = "{{url('car_model/delete', 'id')}}";
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
    var isactive="true";
    console.log(id);
    //alert(id.toString()+'status');
    $.ajax({
      headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },
       url : 'carModel/switch',
       method:"POST",
       data:{ 'isactive':isactive, 'ischeck':ischeck,'id':id},
       success: function(data){

       },
    });

});
$(document).on("click", "input[name='allowcod']", function (e) {
    
    var ischeck = $(this).is(':checked');
    var id = this.id;
    var isactive="false";
    console.log(id);
    //alert(id.toString()+'cod');
    $.ajax({
      headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },
       url : 'carModel/switch',
       method:"POST",
       data:{ 'isactive':isactive, 'ischeck':ischeck,'id':id},
       success: function(data){

       },
    });

});

    </script>

@endsection
