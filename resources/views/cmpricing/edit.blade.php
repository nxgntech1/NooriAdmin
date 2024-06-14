@extends('layouts.app')

@section('content')
<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.pricing')}}</h3>
		</div>

		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
				<li class="breadcrumb-item"><a href= "{!! route('car_model') !!}" >{{trans('lang.pricing')}}</a></li>
				<li class="breadcrumb-item active">{{trans('lang.pricing')}}</li>
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
		<form action="{{ route('cmpricing.update',$carmodelprice->PricingID) }}" method="post"  enctype="multipart/form-data">
		@csrf
        @method("PUT")
			<div class="row restaurant_payout_create">
          	<div class="restaurant_payout_create-inner">
          		<fieldset>
              		<legend>{{trans('lang.edit_car_model_price')}}</legend>

                      <div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.vehicle_type')}}</label>
							<div class="col-7">
							<select id="vehicle_id"  class="form-control brand_id" name="vehicle_id">
									@foreach($vehicletype as $value)
										<option value="{{$value->id}}" {{$selectedcarmodel->vehicle_type_id== $value->id ? 'selected' : '' }}>{{$value->libelle}}</option>
									@endforeach
								</select>
								<div class="form-text text-muted">
									{{ trans("lang.select_vehicle_type") }}
								</div>
							</div>
						</div>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.brand')}}</label>
							<div class="col-7">
								<select id="brand" class="form-control brand_id" name="brand">
									@foreach($brand as $value)
										<option value="{{$value->id}}" {{$selectedcarmodel->brand_id== $value->id ? 'selected' : '' }}>{{$value->name}}</option>
									@endforeach
								</select>
								<div class="form-text text-muted">
									{{ trans("lang.brand_help") }}
								</div>
							</div>
						</div>
                        <div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.car_model')}}</label>
							<div class="col-7">
							<select id="carmodel_id" class="form-control brand_id" name="carmodel_id">
									@foreach($carmodel as $value)
										<option value="{{$value->id}}" {{$carmodelprice->CarModelID== $value->id ? 'selected' : '' }}>{{$value->name}}</option>
									@endforeach
								</select>
								<div class="form-text text-muted">
									{{ trans("lang.car_model_help") }}
								</div>
							</div>
						</div>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.bookingtype_name')}}</label>
							<div class="col-7">
								<select  class="form-control brand_id" name="bookingtype_id">
									@foreach($bookingtype as $value)
										<option value="{{$value->id}}" {{$carmodelprice->BookingTypeID== $value->id ? 'selected' : '' }}>{{$value->bookingtype}}</option>
									@endforeach
								</select>
								<div class="form-text text-muted">
									{{ trans("lang.bookingtype_select_help") }}
								</div>
							</div>
						</div>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.price')}}</label>
							<div class="col-7">
								<input type="text" class="form-control car_model_name" value="{{$carmodelprice->Price}}" name="price">
								<div class="form-text text-muted">
									{{ trans("lang.food_price_help") }}
								</div>
							</div>
						</div>
					 <div class="form-group row width-100">
						<div class="form-check">
                            @if($carmodelprice->Status=="yes")
							<input type="checkbox" class="car_model_active" id="car_model_active" checked="checked" name="status">
                            @else
                            <input type="checkbox" class="car_model_active" id="car_model_active" name="status">
                            @endif
							<label class="col-3 control-label" for="car_model_active">{{trans('lang.active')}}</label>

						</div>
					</div>
						
		 		</fieldset> 

			
		</div>
	</div>


			<div class="form-group col-12 text-center btm-btn" >
				<button type="submit" class="btn btn-primary  create_user_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
				<a href="{!! route('cmpricing') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
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

