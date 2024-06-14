@extends('layouts.app')

@section('content')

<div class="page-wrapper">

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.rental_vehicle_type')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.rental_vehicle_type')}}</li>
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
                                <a class="nav-link do_not_create" href="{!! route('vehicle-rental-type.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.create_rental_vehicle_type')}}</a>
                            </div>

                            <div id="users-table_filter" class="ml-auto">
                              <label>{{ trans('lang.search_by')}}
                                <div class="form-group">

                                    <form action="{{ route('vehicle-rental-type') }}" method="get">


                                        @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="libelle" @if ($_GET[
                                            'selected_search']=='libelle')
                                            selected="selected" @endif>{{trans('lang.vehicle_type')}}</option>
                                            <option value="prix" @if ($_GET[
                                            'selected_search']=='prix')
                                            selected="selected" @endif>{{trans('lang.price')}}</option>
                                        </select>
                                        @else
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="libelle">{{trans('lang.vehicle_type')}}</option>
                                            <option value="prix">{{trans('lang.price')}}</option>
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
                                               href="{{url('vehicle-rental-type/index')}}">Clear</a>
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
                                    <th>{{trans('lang.vehicle_type')}}</th>
                                    <th>{{trans('lang.price')}}</th>
                                    <th>{{trans('lang.number_of_passenger')}}</th>
                                    <th>{{trans('lang.status')}}</th>
                                    <!-- <th >{{trans('lang.status')}}</th> -->
                                    <th>{{trans('lang.created_at')}}</th>
                                    {{--<th>{{trans('lang.modified_at')}}</th>--}}

                                    <th>{{trans('lang.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody id="append_list12">
                                @if(count($vehicletype) > 0)
                                @foreach($vehicletype as $type)

                                <tr>
                                    <td class="delete-all"><input type="checkbox"
                                                                  id="is_open_{{$type->id}}"
                                                                  class="is_open"
                                                                  dataid="{{$type->id}}"><label
                                                class="col-3 control-label"
                                                for="is_open_{{$type->id}}"></label></td>
                                    @if (file_exists(public_path('assets/images/type_vehicle_rental'.'/'.$type->image)) &&
                                    !empty($type->image))
                                    <td><img class="rounded" style="width:50px"
                                             src="{{asset('assets/images/type_vehicle_rental').'/'.$type->image}}" alt="image">
                                    </td>
                                    @else
                                    <td><img class="rounded" style="width:50px"
                                             src="{{asset('assets/images/placeholder_image.jpg')}}" alt="image"></td>
                                    @endif
                                    <td>{{ $type->libelle}}</td>
                                    <td>
                                        @if($currency->symbol_at_right=="true")
                                            {{ number_format(floatval($type->prix),$currency->decimal_digit)."".$currency->symbole}}
                                        @else
                                         {{ $currency->symbole."".number_format(floatval($type->prix),$currency->decimal_digit)}}
                                        @endif                                        
                                    </td>
                                    <td>{{$type->no_of_passenger}}</td>
                                    <td> @if ($type->status=="yes")
                                          <label class="switch"><input type="checkbox" id="{{$type->id}}" name="publish" checked><span class="slider round"></span></label>
                                        @else
                                              <label class="switch"><input type="checkbox" id="{{$type->id}}" name="publish"><span class="slider round"></span></label>
                                        @endif
                                      </td>




                                    <td>
                                        <span class="date">{{ date('d F Y',strtotime($type->creer))}}</span>
                                        <span class="time">{{ date('h:i A',strtotime($type->creer))}}</span>
                                    </td>
                                   {{-- <td>
                                      @if($type->modifier!='0000-00-00 00:00:00')
                                      <span class="date">{{ date('d F Y',strtotime($type->modifier))}}</span>
                                      <span class="time">{{ date('h:i A',strtotime($type->modifier))}}</span>
                                      @endif
                                    </td>--}}
                                    <td class="action-btn"><a class="edit_type_vehicle do_not_edit" href="{{route('vehicle-rental-type.edit', ['id' => $type->id])}}"><i
                                                    class="fa fa-edit"></i></a><a id="'+val.id+'" class="do_not_delete"
                                                                                  href="{{route('vehicle-rental-type.delete', ['id' => $type->id])}}" class="do_not_delete"><i
                                                    class="fa fa-trash"></i></a></td>



                                </tr>
                                @endforeach
                                	@else
                                	<tr><td colspan="11" align="center">{{trans("lang.no_result")}}</td></tr>
                                @endif
                                </tbody>
                            </table>


                            <nav aria-label="Page navigation example" class="custom-pagination">
                                {{ $vehicletype->appends(request()->query())->links() }}
                            </nav>
                            {{ $vehicletype->links('pagination.pagination') }}
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

    $(".edit_type_vehicle").click(function () {


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
                var url = "{{url('vehicle-rental-type/delete', 'id')}}";
                url = url.replace('id', arrayUsers);

                $(this).attr('href', url);
            }
        } else {
            alert('Please Select Any One Record .');
        }
    });

    $(document).on("click", "input[name='publish']", function (e) {
    
    var ischeck = $(this).is(':checked');
    var id = this.id;

    $.ajax({
      headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },
       url : '../rental_vehicle_type/switch',
       method:"POST",
       data:{'ischeck':ischeck,'id':id},
       success: function(data){

       },
    });

});
    
</script>


@endsection
