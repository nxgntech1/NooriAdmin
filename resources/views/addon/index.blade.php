@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.addon_pricing')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item active">{{trans('lang.addon_pricing')}}</li>
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
                                    <a class="nav-link do_not_create" href="{!! route('addon.create') !!}"><i
                                                class="fa fa-plus mr-2"></i>{{trans('lang.create_addon_pricing')}}</a>
                                </div>
                                <div id="users-table_filter" class="ml-auto">
                                    <label>{{ trans('lang.search_by')}}
                                    <div class="form-group mb-0">
                                        <form action="{{ route('addon') }}" method="get">
                                            @if(isset($_GET['selected_search']) &&  $_GET['selected_search'] != '')
                                                <?php //dd($_GET['selected_search']);?>
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="brand" @if ($_GET['selected_search']=='brand')
                                                    selected="selected" @endif >{{ trans('lang.brand')}}</option>
                                                    <option value="model" @if ($_GET['selected_search']=='model')
                                                     @endif >{{ trans('lang.vehicle_model')}}</option>
                                                    
                                                </select>
                                            @else
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="brand">{{ trans('lang.brand')}}</option>
                                                    <option value="model">{{ trans('lang.vehicle_model')}}</option>
                                                    
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
                                                <a class="btn btn-warning btn-flat" href="{{url('addon')}}">Clear</a>
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
                                        <th>{{trans('lang.brand')}}</th>
                                        <th>{{trans('lang.vehicle_model')}}</th>
                                        <th>{{trans('lang.addon_label')}}</th>
                                        <th>{{trans('lang.price')}}</th>
                                        <th>{{trans('lang.status')}}</th>
                                        <th>{{trans('lang.actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="append_list12">
                                    @if(count($carModelPricing) > 0)
                                    @foreach($carModelPricing as $value)
                                        <tr>
                                            <td class="delete-all"><input type="checkbox"
                                                                          id="is_open_{{$value->PricingID}}"
                                                                          class="is_open"
                                                                          dataid="{{$value->PricingID}}"><label
                                                        class="col-3 control-label"
                                                        for="is_open_{{$value->PricingID}}"></label></td>
                                            <td>{{ $value->vehicletype}} </td>
                                            <td>{{ $value->BrandName}} </td>
                                            <td>{{ $value->modelname}} </td>
                                            <td>{{ $value->Add_on_Label}} </td>
                                            <td>{{ $currency->symbole."".number_format(floatval($value->Price),$currency->decimal_digit) }} </td>
                                              <td>  @if ($value->Status=="yes")
                                                    <label class="switch"><input type="checkbox" id="{{$value->PricingID}}" name="publish" checked><span class="slider round"></span></label>
                                                @else
                                                <label class="switch"><input type="checkbox"  id="{{$value->PricingID}}" name="publish"><span class="slider round"></span></label>
                                                @endif
                                            </td>
                                            <td class="action-btn">
                                            <a
                                                        href="{{route('addon.edit', ['id' => $value->PricingID])}}" class="do_not_edit"><i
                                                            class="fa fa-edit"></i></a><a id="'+val.PricingID+'"
                                                                                          class="do_not_delete"
                                                                                          name="user-delete"
                                                                                          href="{{route('addon.delete', ['id' => $value->PricingID])}}"><i
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
                                    {{ $carModelPricing->appends(request()->query())->links()  }}
                                </nav>
                                {{ $carModelPricing->links('pagination.pagination') }}
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
                    var url = "{{url('addon/delete', 'id')}}";
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
    //alert(id.toString()+'status');
    $.ajax({
      headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },
       url : 'addon/switch',
       method:"POST",
       data:{ 'ischeck':ischeck,'id':id},
       success: function(data){

       },
    });

});


    </script>

@endsection
