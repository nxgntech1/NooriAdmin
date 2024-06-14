@extends('layouts.app')

@section('content')
<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.user_create')}}</h3>
		</div>

		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
				<li class="breadcrumb-item"><a href= "{!! route('users') !!}" >{{trans('lang.user_plural')}}</a></li>
				<li class="breadcrumb-item active">{{trans('lang.user_create')}}</li>
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

		<form action="{{route('users.storeuser')}}" method="post"  enctype="multipart/form-data" id="create_driver">
		@csrf
			<div class="row restaurant_payout_create">
          	<div class="restaurant_payout_create-inner">
          		<fieldset>
              		<legend>{{trans('lang.user_details')}}</legend>

						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.first_name')}}</label>
							<div class="col-7">
								<input type="text" class="form-control user_first_name" name="prenom" value="{{ Request::old('prenom')}}">
								<div class="form-text text-muted">
									{{ trans("lang.user_first_name_help") }}
								</div>
							</div>
						</div>

						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.last_name')}}</label>
							<div class="col-7">
								<input type="text" class="form-control user_last_name" name="nom" value="{{ Request::old('nom')}}">
								<div class="form-text text-muted">
									{{ trans("lang.user_last_name_help") }}
								</div>
							</div>
						</div>
						<div class="form-group row width-50">
						<label class="col-3 control-label">{{trans('lang.user_phone')}}</label>
						<div class="col-7">
							<input type="text" class="form-control user_phone" name="phone" value="{{ Request::old('phone')}}">
							<div class="form-text text-muted w-50">
								{{ trans("lang.user_phone_help") }}
							</div>
						</div>

					</div>


						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.email')}}</label>
							<div class="col-7">
								<input type="text" class="form-control user_email" name="email" value="{{ Request::old('email')}}">
								<div class="form-text text-muted">
									{{ trans("lang.user_email_help") }}
								</div>
							</div>
						</div>

						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.password')}}</label>
							<div class="col-7">
								<input type="password" class="form-control user_password" name="password" value="{{ Request::old('password')}}">
								<div class="form-text text-muted">
									{{ trans("lang.user_password_help") }}
								</div>
							</div>
						</div>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.confirm_password')}}</label>
							<div class="col-7">
								<input type="password" class="form-control confirm_password" name="confirm_password" value="{{ Request::old('confirm_password')}}">
								<div class="form-text text-muted">
									{{ trans("lang.user_password_help") }}
								</div>
							</div>
						</div>

					<!-- <div class="form-group row width-50">
						<label class="col-3 control-label">{{trans('lang.user_age')}}</label>
						<div class="col-7">
							<input type="text" class="form-control user_age" name="age" value="{{ Request::old('age')}}">
							<div class="form-text text-muted w-50">
								{{ trans("lang.user_age_help") }}
							</div>
						</div>

					</div>

					<div class="form-group row width-50">
						<label class="col-3 control-label">{{trans('lang.user_gender')}}</label>
						<div class="col-7">
							<select name="gender" id="user_role" class="form-control" >
								<option class="customer" value="Male" {{ Request::old('gender')=="Male" ?'selected':''}}>{{trans('lang.user_gender_male')}}</option>
								<option class="vendor" value="Female" {{ Request::old('gender')=="Female" ?'selected':''}}>{{trans('lang.user_gender_female')}}</option>
								<option class="vendor" value="Other" {{ Request::old('gender')=="Other" ?'selected':''}}>{{trans('lang.user_gender_other')}}</option>
							</select>
							<div class="form-text text-muted w-50">
							{{ trans("lang.user_gender_help") }}
							</div>
						</div>
					</div> -->
					<!-- <div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.device_id')}}</label>
							<div class="col-7">
								<input type="text" class="form-control user_phone" name="device_id">
							</div>

						</div> -->
						<!-- <div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.fcm_id')}}</label>
							<div class="col-7">
								<input type="text" class="form-control user_phone" name="fcm_id">
							</div>

						</div> -->
					<div class="form-group row width-50">
						<label class="col-3 control-label">{{trans('lang.profile_image')}}</label>
						<input type="file"  class="col-7" name="photo" onchange="readURL(this);">
						<div class="placeholder_img_thumb user_image"></div>
						<div id="image_preview" style="display: none; padding-left: 15px;">
						<img class="rounded" style="width:50px" id="uploding_image" src="#" alt="image">
						</div>
					</div>
					
					<!-- <div class="form-group row width-50">
						<label class="col-3 control-label">{{trans('lang.nic_image')}}</label>
						<input type="file" class="col-7" name="nic_path" onchange="readURLNic(this);">
						<div class="placeholder_img_thumb user_nic_image"></div>
						<div id="placeholder_img_thumb" style="display: none;padding-left: 15px;">
						<img class="rounded" style="width:50px" id="user_nic_image" src="#" alt="image">
						</div>
					</div> -->


					<div class="form-group row width-50">
						<div class="form-check">
							<input type="checkbox" class="user_active" id="user_active" name="statut">
							<label class="col-3 control-label" for="user_active">{{trans('lang.active')}}</label>

						</div>
					</div>	
<!--
					<div class="form-group row width-50">
						<div class="form-check">
							<input type="checkbox" class="user_active" id="notify" name="tonotify" value="yes">
							<label class="col-3 control-label" for="notify">{{trans('lang.Tonotify')}}</label>

						</div>
					</div> -->
		 		</fieldset>

		<!--	<fieldset>
            	<legend>{{trans('lang.address')}}</legend>

            	<div class="form-group row width-50">
					<label class="col-3 control-label">{{trans('lang.address_line1')}}</label>
					<div class="col-7">
						<input type="text" class="form-control address_line1" name="address_line1" value="{{ Request::old('address_line1')}}">
						<div class="form-text text-muted w-50">
							{{ trans("lang.address_line1_help") }}
						</div>
					</div>

				</div>

            	<div class="form-group row width-50">
					<label class="col-3 control-label">{{trans('lang.address_line2')}}</label>
					<div class="col-7">
						<input type="text" class="form-control address_line2" name="address_line2" value="{{ Request::old('address_line2')}}">
						<div class="form-text text-muted w-50">
							{{ trans("lang.address_line2_help") }}
						</div>
					</div>

				</div>

            	<div class="form-group row width-50">
					<label class="col-3 control-label">{{trans('lang.city')}}</label>
					<div class="col-7">
						<input type="text" class="form-control city" name="city" value="{{ Request::old('city')}}">
						<div class="form-text text-muted w-50">
							{{ trans("lang.city_help") }}
						</div>
					</div>

				</div>

            	<div class="form-group row width-50">
					<label class="col-3 control-label">{{trans('lang.country')}}</label>
					<div class="col-7">
						<input type="text" class="form-control country" name="country" value="{{ Request::old('country')}}">
						<div class="form-text text-muted w-50">
							{{ trans("lang.country_help") }}
						</div>
					</div>

				</div>

            	<div class="form-group row width-50">
					<label class="col-3 control-label">{{trans('lang.postalcode')}}</label>
					<div class="col-7">
						<input type="text" class="form-control postalcode" name="postalcode" value="{{ Request::old('postalcode')}}">
						<div class="form-text text-muted w-50">
							{{ trans("lang.postalcode_help") }}
						</div>
					</div>

				</div>

				 <div class="form-group row width-100">
	              <div class="col-12">
	                <h6>{{ trans("lang.know_your_cordinates") }}<a target="_blank" href="https://www.latlong.net/">{{ trans("lang.latitude_and_longitude_finder") }}</a></h6>
	              </div>
	            </div>

            	 <div class="form-group row width-50">
					<label class="col-3 control-label">{{trans('lang.user_latitude')}}</label>
					<div class="col-7">
						<input type="text" class="form-control user_latitude" name="user_latitude">
						<div class="form-text text-muted w-50">
							{{ trans("lang.user_latitude_help") }}
						</div>
					</div>

				</div>

            	 <div class="form-group row width-50">
					<label class="col-3 control-label">{{trans('lang.user_longitude')}}</label>
					<div class="col-7">
						<input type="text" class="form-control user_longitude" name="user_longitude">
						<div class="form-text text-muted w-50">
							{{ trans("lang.user_longitude_help") }}
						</div>
					</div>

				</div>

			</fieldset> -->
		</div>
	</div>


			<div class="form-group col-12 text-center btm-btn" >
				<button type="submit" class="btn btn-primary  create_user_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
				<a href="{!! route('users') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
			</div>

		</form>

		</div>
		</div>
		</div>
		</div>
		</div>

@endsection

@section('scripts')
 <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
 <script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
				$('#image_preview').show();
                $('#uploding_image').attr('src', e.target.result);	
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

	function readURLNic(input) {
		console.log(input.files);
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
				$('#placeholder_img_thumb').show();
				$('#user_nic_image').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
