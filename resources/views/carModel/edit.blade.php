@extends('layouts.app')

@section('content')
<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.car_model_create')}}</h3>
		</div>

		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
				<li class="breadcrumb-item"><a href= "{!! route('car_model') !!}" >{{trans('lang.car_model')}}</a></li>
				<li class="breadcrumb-item active">{{trans('lang.car_model_create')}}</li>
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
		<form action="{{ route('car_model.update',$carModel->id) }}" method="post"  enctype="multipart/form-data">
		@csrf
        @method("PUT")
			<div class="row restaurant_payout_create">
          	<div class="restaurant_payout_create-inner">
          		<fieldset>
              		<legend>{{trans('lang.edit_brand')}}</legend>

                      <div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.name')}}</label>
							<div class="col-7">
								<input type="text" class="form-control car_model_name" name="name" value="{{$carModel->name}}">
								<div class="form-text text-muted">
									{{ trans("lang.car_model_name_help") }}
								</div>
							</div>
						</div>

						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.brand')}}</label>
							<div class="col-7">
								<select  class="form-control brand_id" name="brand_name">
                                    
									@foreach($brand as $value)
                                  		<option value="{{ $value->id }}" {{$carModel->brand_id== $value->id ? 'selected' : '' }}>{{$value->name}}</option>
									@endforeach
                                   
								</select>
								<div class="form-text text-muted">
									{{ trans("lang.brand_help") }}
								</div>
							</div>
						</div>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.vehicle_type')}}</label>
							<div class="col-7">
							<select  class="form-control brand_id" name="vehicle_id">
									@foreach($vehicleType as $value)
									<option value="{{ $value->id }}" {{$carModel->vehicle_type_id== $value->id ? 'selected' : '' }}>{{$value->libelle}}</option>
									@endforeach
								</select>
								
							</div>
						</div>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.Image')}}</label>
							<div class="col-7">
								<input type="file" class="form-control" name="image"
										onchange="readURL(this);">
								@if (file_exists(public_path('assets\\images\\vehicle_models'.'\\'.$carModel->imageid)) && !empty($carModel->imageid))
									<img class="rounded" style="width:50px" id="uploding_image"
											src="{{asset('assets/images/vehicle_models').'/'.$carModel->imageid}}"
											alt="image">
								@else
									<img class="rounded" style="width:50px" id="uploding_image"
											src="{{asset('assets/images/placeholder_img_car.png')}}"
											alt="image">
								@endif
							</div>
						</div>
					 <div class="form-group row width-100">
						<div class="form-check">
                        @if ($carModel->status === "yes")    
							<input type="checkbox" class="car_model_active" id="car_model_active" name="status" checked="checked">
                            @else
                            <input type="checkbox" class="car_model_active" id="car_model_active" name="status">
                        @endif
							<label class="col-3 control-label" for="car_model_active">{{trans('lang.active')}}</label>

						</div>

					</div>
					<div class="form-group row width-100">
						<div class="form-check">
                        @if ($carModel->allow_cod === "yes")    
							<input type="checkbox" class="car_model_allowcod" id="car_model_allowcod" name="allowcod" checked="checked">
                            @else
                            <input type="checkbox" class="car_model_allowcod" id="car_model_allowcod" name="allowcod">
                        @endif
							<label class="col-3 control-label" for="car_model_allowcod">{{trans('lang.allow_cod')}}</label>

						</div>
						
					</div>
						
		 		</fieldset> 

			
		</div>
	</div>


			<div class="form-group col-12 text-center btm-btn" >
				<button type="submit" class="btn btn-primary  create_user_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
				<a href="{!! route('car_model') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
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
        function readURL(input) {
            console.log(input.files);
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#image_preview').show();
                    $('#uploding_image').attr('src', e.target.result);


                }

                reader.readAsDataURL(input.files[0]);
            }
        }


    </script>
@endsection

