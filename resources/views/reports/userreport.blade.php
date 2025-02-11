@extends('layouts.app')

@section('content')
<div class="page-wrapper">
  <div class="row page-titles">
    <div class="col-md-5 align-self-center">
      <h3 class="text-themecolor">{{trans('lang.user_reports')}}</h3>
    </div>

    <div class="col-md-7 align-self-center">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
        <li class="breadcrumb-item"><a href= "{!! route('userreport') !!}" >{{trans('lang.reports')}}</a></li>
        <li class="breadcrumb-item active">{{trans('lang.user_reports')}}</li>
      </ol>
    </div>
</div>
@if(session()->has('message'))
		<div class="alert alert-danger center">
			{{ session()->get('message') }}
		</div>
	@endif
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
        <form action="{{ route('userreport.downloadExcel') }}"   method="get"  enctype="multipart/form-data" id="create_driver">
          <!-- @csrf -->

          <div class="row restaurant_payout_create">
              <div class="restaurant_payout_create-inner">
                <fieldset>
                  <legend>{{trans('lang.user_reports')}}</legend>
                        <div class="form-group row width-50">
                          <label class="col-3 control-label">{{trans('lang.select_status')}}</label>
                          <div class="col-7">           
                          <!-- <input type="hidden" class="form-control user_first_name" name="id">       
                            <input type="text" class="form-control user_first_name" name="nom"> -->
                            <select class="form-control" name="user_status">
                                <option value="0">{{trans('lang.select_status')}}</option>
                                <option value="yes">{{trans('lang.active')}}</option>
                                <option value="no">{{trans('lang.inactive')}}</option>
                            </select>
                          </div>
                        </div>
                       
                        <div class="form-group row width-50">
                          <label class="col-3 control-label">{{trans('lang.date')}}</label>
                          <div class="col-7">           
                          <!-- <input type="hidden" class="form-control user_first_name" name="id">       
                            <input type="text" class="form-control user_first_name" name="nom"> -->
                            <select class="form-control" name="date">
                                <option value="0">{{trans('lang.select_date')}}</option>
                                <option value="today">{{trans('lang.today')}}</option>
                                <option value="week">{{trans('lang.week')}}</option>
                                <option value="month">{{trans('lang.month')}}</option>
                                <option value="year">{{trans('lang.year')}}</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row width-50">
                          <label class="col-3 control-label">{{trans('lang.from')}}</label>
                          <div class="col-7">
                            <input type="date" class="form-control user_email" name="from">
                          </div>    
                        </div>
                        <div class="form-group row width-50">
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
                                <option value="csv">{{trans('lang.csv')}}</option>
                                <option value="pdf">{{trans('lang.pdf')}}</option>
                            </select>                          </div>    
                        </div>

                     
                      </fieldset>
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
 
@endsection