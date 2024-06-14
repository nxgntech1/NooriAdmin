@extends('layouts.app')

@section('content')
<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.dashboard_total_admin_commission')}}</h3>
		</div>

		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
				<li class="breadcrumb-item"><a href= "{!! route('commission') !!}" >{{trans('lang.dashboard_total_admin_commission')}}</a></li>
			</ol>
		</div>
</div>


<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card pb-4">
	<div class="card-body">

		<div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
			{{trans('lang.processing')}}
		</div>
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
		<form action="{{ route('commission.update',$commission->id) }}" method="post"  enctype="multipart/form-data" id="create_driver">
		@csrf
        @method("PUT")
			<div class="row restaurant_payout_create">
          	<div class="restaurant_payout_create-inner">
          		<fieldset>
              		<legend>{{trans('lang.dashboard_total_admin_commission')}}</legend>
                      <div class="form-group row width-100">
						<div class="form-check">   
                            @if($commission->statut == 'yes')
							<input type="checkbox" class="user_active" id="status" name="statut" checked="checked">
							@else
                            <input type="checkbox" class="user_active" id="status" name="statut">
                            @endif
                            <label class="col-3 control-label" for="status">{{trans('lang.enable_commission')}}</label>

						</div>

						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.commission_type')}}</label>
							
                            <select name="type" class="form-control">
                            @if($commission->type == 'Percentage')
                            <option value="Percentage" selected>Percentage</option>
                            <option value="Fixed">Fixed</option>
                            @else
                            <option value="Fixed" selected>Fixed</option>
                            <option value="Percentage">Percentage</option>
                            @endif
                            </select> 
								
								
							
						</div>

						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.dashboard_total_admin_commission')}}</label>
							
								<input type="text" class="form-control code" name="value" value="{{ $commission->value }}">
								
							
						</div>
                       
					</div> 
						
					</div>

						
						
		 		</fieldset> 

			
		</div>
	</div>


			<div class="form-group col-12 text-center btm-btn" >
				<button type="submit" class="btn btn-primary  create_user_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
				<a href="{!! route('commission') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
			</div>

		</form>

		</div>
		</div>
		</div>
		</div>
		</div>

@endsection

@section('scripts')
 
@endsection

