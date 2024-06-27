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
				<li class="breadcrumb-item"><a href= "{!! route('addon') !!}" >{{trans('lang.addon_pricing')}}</a></li>
				<li class="breadcrumb-item active">{{trans('lang.addon_pricing')}}</li>
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
		<form action="{{route('addon.storecarmodelprice')}}" method="post"  enctype="multipart/form-data" id="create_carmodelprice">
		@csrf
			<div class="row restaurant_payout_create">
          	<div class="restaurant_payout_create-inner">
          		<fieldset>
              		<legend>{{trans('lang.create_addon_pricing')}}</legend>
                        <div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.vehicle_type')}}</label>
							<div class="col-7">
							<select id="vehicle_id"  class="form-control brand_id" name="vehicle_id">
									@foreach($vehicletype as $value)
										<option value="{{$value->id}}">{{$value->libelle}}</option>
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
										<option value="{{$value->id}}">{{$value->name}}</option>
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
										<option value="{{$value->id}}">{{$value->name}}</option>
									@endforeach
								</select>
								<div class="form-text text-muted">
									{{ trans("lang.car_model_help") }}
								</div>
							</div>
						</div>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.price')}}</label>
							<div class="col-7">
								<input type="text" id="price" pattern="^\d+(\.\d+)?$" title="Only numbers are allowed" class="form-control car_model_name" name="price">
								<div class="form-text text-muted">
									{{ trans("lang.food_price_help") }}
								</div>
							</div>
						</div>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.hours')}}</label>
							<div class="col-7">
								<input type="text" id="hours" pattern="\d*" title="Only numbers are allowed" class="form-control car_model_name" name="hours">
								<div class="form-text text-muted">
									{{ trans("lang.hours_help") }}
								</div>
							</div>
						</div>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.kms')}}</label>
							<div class="col-7">
								<input type="text" id="kms" pattern="\d*" title="Only numbers are allowed" class="form-control car_model_name" name="kms">
								<div class="form-text text-muted">
									{{ trans("lang.kms_help") }}
								</div>
							</div>
						</div>
					 <div class="form-group row width-100">
						<div class="form-check">
							<input type="checkbox" class="car_model_active" id="car_model_active" name="status">
							<label class="col-3 control-label" for="car_model_active">{{trans('lang.active')}}</label>

						</div>
					</div>
		 		</fieldset> 


		</div>
	</div>
			<div class="form-group col-12 text-center btm-btn" >
				<button type="submit" class="btn btn-primary  create_user_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
				<a href="{!! route('addon') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
			</div>

		</form>

		</div>
		</div>
		</div>
		</div>
		</div>

@endsection

@section('scripts')
<script type="text/javascript">
	document.getElementById('price').addEventListener('input', function (e) {
		this.value = this.value.replace(/[^0-9.]/g, '');
    if ((this.value.match(/\./g) || []).length > 1) {
        this.value = this.value.replace(/\.$/, '');
    }
	});
	document.getElementById('hours').addEventListener('input', function (e) {
		this.value = this.value.replace(/[^0-9]/g, '');
	});
	document.getElementById('kms').addEventListener('input', function (e) {
		this.value = this.value.replace(/[^0-9]/g, '');
	});
$(document).ready(function() {
    $('#brand').change(function() {
        
        var brandid = $(this).val();
        var vtypeid = $('#vehicle_id').val();
        if(brandid && vtypeid) {
            $.ajax({
                headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },
                url: '{{ route("addon.filter") }}',
                type: 'POST',
                data: { vehicletype_id: vtypeid, brand_id: brandid },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#carmodel_id').empty();
                    
                    $.each(data, function(key, value) {
                        $('#carmodel_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        } else {
            alert(brandid.toString()+"::"+vtypeid.toString());
            $('#carmodel_id').empty();
            //$('#carmodel_id').append('<option value="">Select Car Model</option>');
        }
    });
});
</script>

@endsection