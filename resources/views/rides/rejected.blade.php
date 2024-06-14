@extends('layouts.app')

@section('content')
    <div class="page-wrapper">

        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor">{{trans('lang.canceland_reject_rides')}}</h3>

            </div>

            <div class="col-md-7 align-self-center">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>

                    <li class="breadcrumb-item active">{{trans('lang.taxi_booking')}}</li>

                    <li class="breadcrumb-item active">{{trans('lang.canceland_reject_rides')}}</li>

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

                            <h4 class="card-title"></h4>
                            <div class="userlist-topsearch d-flex mb-3">
                            <!-- <div class="userlist-top-left">
                                    <a class="nav-link" href=""><i class="fa fa-plus mr-2"></i>{{trans('lang.onride_create')}}</a>
                                </div>   -->
                                <div id="users-table_filter" class="ml-auto">
                                    <div class="form-group mb-0">
                                        <form action="{{ route('rides.rejected') }}" method="get">
                                            @if(isset($_GET['selected_search']) &&  $_GET['selected_search'] != '')
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="userPrenom"
                                                            @if($_GET['selected_search']=='userPrenom')
                                                            selected="selected" @endif>{{trans('lang.user_name')}}</option>
                                                    <option value="driverPrenom"
                                                            @if($_GET['selected_search']=='driverPrenom')
                                                            selected="selected" @endif>{{trans('lang.driver_name')}}</option>
                                                    <option value="status" @if($_GET['selected_search']=='status')
                                                    selected="selected" @endif>{{trans('lang.status')}}</option>
                                                </select>
                                            @else
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="userPrenom">{{trans('lang.user_name')}}</option>
                                                    <option value="driverPrenom">{{trans('lang.driver_name')}}</option>
                                                    <option value="status">{{trans('lang.status')}}</option>
                                                </select>
                                            @endif
                                            <div class="search-box position-relative">
                                                @if(isset($_GET['search']) &&  $_GET['search'] != '')
                                                    <input type="text" class="search form-control" name="search"
                                                           id="search" value="{{$_GET['search']}}">
                                                           <select id="ride_status" class="form-control"
                                                                   name="ride_status" style="display: none">
                                                                <option value="confirmed" >{{ trans('lang.confirmed')}}</option>
                                                               <option  value="new">{{ trans('lang.new')}}</option>

                                                               <option value="on ride" >{{ trans('lang.on_ride')}}</option>
                                                               <option value="completed" >{{ trans('lang.completed')}}</option>
                                                               <option value="rejected" >{{ trans('lang.rejected')}}</option>

                                                           </select>
                                                           @elseif(isset($_GET['ride_status']) && $_GET['ride_status']!='')
                                                <input type="text" class="search form-control" name="search"
                                                       id="search" style="display:none">
                                                       <select id="ride_status" class="search form-control"
                                                               name="ride_status" >
                                                               <option @if($_GET['ride_status']=='confirmed')selected="selected"
                                                               @endif  value="confirmed" >{{ trans('lang.confirmed')}}</option>
                                                           <option @if($_GET['ride_status']=='new')selected="selected"
                                                           @endif  value="new">{{ trans('lang.new')}}</option>

                                                           <option @if($_GET['ride_status']=='on ride')selected="selected"
                                                           @endif  value="on ride" >{{ trans('lang.on_ride')}}</option>
                                                           <option @if($_GET['ride_status']=='completed')selected="selected"
                                                           @endif  value="completed" >{{ trans('lang.completed')}}</option>
                                                           <option @if($_GET['ride_status']=='rejected')selected="selected"
                                                           @endif  value="rejected" >{{ trans('lang.rejected')}}</option>

                                                       </select>
                                                @else
                                                    <input type="text" class="search form-control" name="search"
                                                           id="search">
                                                           <select id="ride_status" class="search form-control"
                                                                   name="ride_status" style="display: none">
                                                                <option value="confirmed" >{{ trans('lang.confirmed')}}</option>
                                                               <option  value="new">{{ trans('lang.new')}}</option>

                                                               <option value="on ride" >{{ trans('lang.on_ride')}}</option>
                                                               <option value="completed" >{{ trans('lang.completed')}}</option>
                                                               <option value="rejected" >{{ trans('lang.rejected')}}</option>

                                                           </select>
                                                @endif
                                                <button type="submit" class="btn-flat position-absolute"><i
                                                            class="fa fa-search"></i></button>
                                                <a class="btn btn-warning btn-flat" href="{{url('rides/rejected')}}">Clear</a>
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
                                    <!-- <th >{{trans('lang.booking_id')}}</th> -->
                                        <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                    class="col-3 control-label" for="is_active"><a id="deleteAll"
                                                                                                   class="do_not_delete"
                                                                                                   href="javascript:void(0)"><i
                                                            class="fa fa-trash"></i> All</a></label></th>
                                        <th>{{trans('lang.ride_id')}}</th>
                                        <th>{{trans('lang.user_name')}}</th>
                                        <th>{{trans('lang.driver_name')}}</th>
                                       {{-- <th>{{trans('lang.source_amount')}}</th>
                                        <th>{{trans('lang.destination_address')}}</th>--}}
                                        <th>{{trans('lang.cost_amount')}}</th>
                                        <th>{{trans('lang.status')}}</th>
                                        <th>{{trans('lang.created')}}</th>
                                        <th>{{trans('lang.actions')}}</th>

                                    </tr>
                                    </thead>
                                    <tbody id="append_list12">
                                    @if(count($rides) > 0)
                                    @foreach($rides as $ride)
                                        <tr>
                                            <td class="delete-all"><input type="checkbox"
                                                                          id="is_open_{{$ride->id}}"
                                                                          class="is_open"
                                                                          dataid="{{$ride->id}}"><label
                                                        class="col-3 control-label"
                                                        for="is_open_{{$ride->id}}"></label></td>
                                            <td><a href="{{route('ride.show', ['id' => $ride->id])}}">{{ $ride->id}}</a>
                                            </td>
                                            <td><a href="{{route('users.show', $ride->id_user_app)}}">{{ $ride->userPrenom}} {{ $ride->userNom}}</a></td>
                                            <td><a href="{{route('driver.show', $ride->id_conducteur)}}">{{ $ride->driverPrenom}} {{ $ride->driverNom}}</a></td>
                                          {{-- <td class="address-td">{{ $ride->depart_name}}</td>
                                            <td class="address-td">{{ $ride->destination_name}}</td>--}}
                                            <?php $montant=floatval($ride->montant);
                                                        $total_price =$montant;
                                                       
                                                          $discount=$ride->discount;
                                                          
                                                          if($discount)
                                                          {
                                                            $total_price =$montant-$discount;
                                                          }
                                                          
                                                          $tax=floatval($ride->tax);
                                                          if($tax){
                                                            $total_price = $total_price + $tax;
                                                          }
                                                          $tip=floatval($ride->tip_amount);
                                                          if($tip)
                                                          {
                                                            $total_price = $total_price + $tip;
                                                          }
                                                          //$total_price= +$tax+$tip; ?>
                                            <td>{{$currency->symbole." ".number_format(floatval($total_price),$currency->decimal_digit)}}</td>
                                            <td>

				                                                <span class="badge badge-danger">{{ $ride->statut }}<span>

                                            </td>
                                            <td class="dt-time"><span class="date">{{ date('d F Y',strtotime($ride->creer))}}</span>
                                                <span class="time">{{ date('h:i A',strtotime($ride->creer))}}</span></td>
                                            <td class="action-btn">
                                              <a href="{{route('ride.show', ['id' => $ride->id])}}" class=""
                                                 data-toggle="tooltip" data-original-title="Details"><i
                                                          class="fa fa-eye"></i></a>
                                                {{--
                                                @if($ride->statut=="completed" or $ride->statut=="on ride" or $ride->statut=="confirmed")
                                                @else
                                                    <a id="'+val.id+'" class="do_not_delete" name="user-delete" href="{{route('ride.delete', ['id' => $ride->id])}}"><i class="fa fa-trash"></i></a>
                                                @endif
                                                --}}

                                                <a id="'+val.id+'"
                                                   class="do_not_delete"
                                                   name="user-delete"
                                                   href="{{route('ride.delete', ['rideid' => $ride->id])}}"><i
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
                                {{$rides->appends(request()->query())->links()}}
                                </nav>
{{ $rides->links('pagination.pagination') }}
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
                    var url = "{{url('ride/delete', 'rideid')}}";
                    url = url.replace('rideid', arrayUsers);

                    $(this).attr('href', url);
                }
            } else {
                alert('Please Select Any One Record .');
            }
        });
        
       $(document).ready(function () {

if($('#selected_search').val()=="status"){
  jQuery('#search').val('');
}else{
  jQuery('#ride_status').val('');

}
})
$(document.body).on('change', '#selected_search', function () {

if (jQuery(this).val() == 'status') {
    jQuery('#ride_status').show();
    jQuery('#ride_status').val('new');
    jQuery('#search').val('');
    jQuery('#search').hide();
} else {

    jQuery('#ride_status').hide();
    jQuery('#ride_status').val('');
    jQuery('#search').show();

}
});
    </script>
@endsection
