@extends('layouts.app')

@section('content')
<div class="page-wrapper">
  <div class="row page-titles">
    <div class="col-md-5 align-self-center">
      <h3 class="text-themecolor">{{trans('lang.travel_report')}}</h3>
    </div>

    <div class="col-md-7 align-self-center">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
        <li class="breadcrumb-item"><a href= "{!! route('userreport') !!}" >{{trans('lang.reports')}}</a></li>
        <li class="breadcrumb-item active">{{trans('lang.travel_report')}}</li>
      </ol>
    </div>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card pb-4">
      <div class="card-body">
        
        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">{{trans('lang.processing')}}</div>
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
        <form action="{{ route('travelreport.downloadExcelTravel') }}"   method="get"  enctype="multipart/form-data" id="create_driver">
          <!-- @csrf -->

          <div class="row restaurant_payout_create">
              <div class="restaurant_payout_create-inner">
                <fieldset>
                  <legend>{{trans('lang.travel_report')}}</legend>
                        <!-- <div class="form-group row width-50">
                          <label class="col-3 control-label">{{trans('lang.vehicle_type')}}</label>
                          <div class="col-7">           
                            <select class="form-control" name="vehicle_type">
                                <option value="0">{{trans('lang.select_vehicle_type')}}</option>
                                @foreach($type as $data)
                                <option value="{{$data->libelle}}">{{$data->libelle}}</option>
                                @endforeach
                            </select>
                          </div>
                        </div> -->
                        <div class="form-group row width-50">
                          <label class="col-3 control-label">{{trans('lang.trip_status')}}</label>
                          <div class="col-7">           
                          <!-- <input type="hidden" class="form-control user_first_name" name="id">       
                            <input type="text" class="form-control user_first_name" name="nom"> -->
                            <select class="form-control" name="trip_status">
                                <option value="0">{{trans('lang.select_trip_status')}}</option>
                                <option value="Completed">{{trans('lang.completed')}}</option>
                                <option value="Cancelled">{{trans('lang.cancelled')}}</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row width-50">
                          <label class="col-3 control-label">{{trans('lang.payment_option')}}</label>
                          <div class="col-7">           
                          <!-- <input type="hidden" class="form-control user_first_name" name="id">       
                            <input type="text" class="form-control user_first_name" name="nom"> -->
                            <select class="form-control" name="payment">
                                <option value="0">{{trans('lang.select_payment_option')}}</option>
                                <option value="5">{{trans('lang.cash')}}</option>
                                <option value="9">{{trans('lang.wallet')}}</option>
                                <option value="8">{{trans('lang.card')}}</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row width-50">
                          <label class="col-3 control-label">{{trans('lang.date')}}</label>
                          <div class="col-7">           
                          <!-- <input type="hidden" class="form-control user_first_name" name="id">       
                            <input type="text" class="form-control user_first_name" name="nom"> -->
                            <select class="form-control" name="date" id="date">
                                <option value="0">{{trans('lang.select_date')}}</option>
                                <option value="today">{{trans('lang.today')}}</option>
                                <option value="week">{{trans('lang.week')}}</option>
                                <option value="month">{{trans('lang.month')}}</option>
                                <option value="year">{{trans('lang.year')}}</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row width-50" id="from">
                          <label class="col-3 control-label">{{trans('lang.from')}}</label>
                          <div class="col-7">
                            <input type="date" class="form-control user_email" name="from" >
                          </div>    
                        </div>
                        <div class="form-group row width-50" id="to">
                          <label class="col-3 control-label">{{trans('lang.to')}}</label>
                          <div class="col-7">
                            <input type="date" class="form-control user_email" name="to">
                          </div>    
                        </div>
                        <div class="form-group row width-50">
                          <label class="col-3 control-label">{{trans('lang.file_format')}}</label>
                          <div class="col-7">
                            <select class="form-control type" name="type" id="type">
                                <option value="">{{trans('lang.file_format')}}</option>
                                <option value="xls">{{trans('lang.xls')}}</option>
                                <option value="csv">{{trans('lang.csv')}}</option>
                                <option value="pdf">{{trans('lang.pdf')}}</option>
                            </select>                          
                        </div>    
                        </div>

                     
                      </fieldset>
                   </div>              
              </div>
          </div>

          <div class="form-group col-12 text-center btm-btn">
            <button type="submit" class="btn btn-primary download" ><i class="fa fa-save"></i> {{ trans('lang.download')}}</button>
            <!-- <a href="{!! route('drivers') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a> -->
          </div>
          </form>
  </div>
  </div>
  </div>
  </div>


@endsection

@section('scripts')
 
<script>
    $(document).ready(function() {

      $('#date').on('change', function(){
        $('#from').hide();
        $('#to').hide();
      })

    });

 </script> 
@endsection