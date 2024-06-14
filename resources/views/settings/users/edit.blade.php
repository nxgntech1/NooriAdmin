@extends('layouts.app')


@section('content')
<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.user_edit')}}</h3>
		</div>
		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
				<li class="breadcrumb-item"><a href= "{!! route('users') !!}" >{{trans('lang.user_plural')}}</a></li>
				<li class="breadcrumb-item active">{{trans('lang.user_edit')}}</li>
			</ol>
		</div>

	</div>
	<div class="container-fluid">

                <div class="row daes-top-sec">

                    <div class="col-lg-6 col-md-6">


                    	<div class="card">

                    <div class="card-body d-flex icon-blue">

                    	<div class="card-left">

                    		 @if ($rides[0]->rides == "")
                                		 <h3 class="m-b-0 text-info">0</h3>
                                	@else
 										<h3 class="m-b-0 text-info">{{$rides[0]->rides}}</h3>
                    				@endif
                                    <h5 class="text-muted m-b-0">{{trans('lang.completed_rides')}}</h5>
                    	</div>

                    	<div class="card-right ml-auto">

                    		<i class="mdi mdi-car"></i>

                    	</div>

                    </div>

                </div>


                    </div>

                   	<div class="col-lg-6 col-md-6">


                   		<div class="card">

                    <div class="card-body d-flex icon-blue">

                    	<div class="card-left">

                    		 <h3 class="m-b-0 text-info" id="driver_count" >0</h3>

                                    <h5 class="text-muted m-b-0">{{trans('lang.average_ratings')}}</h5>
                    	</div>

                    	<div class="card-right ml-auto">

						<i class="mdi mdi-star"></i>

                    	</div>

                    </div>

                </div>


                    </div>

                </div>

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
				<form method="post" action="{{ route('user.update',$user->id) }}" enctype="multipart/form-data">
					@csrf
					@method("PUT")
			<div class="row restaurant_payout_create">
				<div class="restaurant_payout_create-inner">

					<fieldset>
						<legend>{{trans('lang.user_edit')}}</legend>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.first_name')}}</label>
							<div class="col-7">
								<input type="text" class="form-control user_first_name" name="prenom" value="{{$user->prenom}}">
								<div class="form-text text-muted">
									{{ trans("lang.user_first_name_help") }}
								</div>
							</div>
						</div>

						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.last_name')}}</label>
							<div class="col-7">
								<input type="text" class="form-control user_last_name" name="nom" value="{{$user->nom}}">
								<div class="form-text text-muted">
									{{ trans("lang.user_last_name_help") }}
								</div>
							</div>
						</div>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.email')}}</label>
							<div class="col-7">
								<input type="text" class="form-control user_email"  name="email" value="{{$user->email}}">
								<div class="form-text text-muted">
									{{ trans("lang.user_email_help") }}
								</div>
							</div>
						</div>

						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.user_phone')}}</label>
							<div class="col-7">
								<input type="text" class="form-control user_phone" name="phone" value="{{$user->phone}}">
								<div class="form-text text-muted w-50">
									{{ trans("lang.user_phone_help") }}
								</div>
							</div>

						</div>

						<!-- <div class="form-group row width-50">
						<label class="col-3 control-label">{{trans('lang.user_age')}}</label>
						<div class="col-7">
							<input type="text" class="form-control user_age" name="age"  value="{{$user->age}}">
							<div class="form-text text-muted w-50">
								{{ trans("lang.user_age_help") }}
							</div>
						</div> -->

					

					<!-- <div class="form-group row width-50">
						<label class="col-3 control-label">{{trans('lang.user_gender')}}</label>
						<div class="col-7">

							<select name="gender" id="user_role" class="form-control">
								<option value="">Select Gender</option>
								<option class="customer" value="Male" {{ $user->gender =='Male' ? 'selected' : ''}}>{{trans('lang.user_gender_male')}}</option>
								<option class="vendor" value="Female"  {{ $user->gender =='Female' ? 'selected' : ''}}>{{trans('lang.user_gender_female')}}</option>
								<option class="vendor" value="Other"  {{ $user->gender =='Other' ? 'selected' : ''}}>{{trans('lang.user_gender_other')}}</option>
							</select>
							<div class="form-text text-muted w-50">
							{{ trans("lang.user_gender_help") }}
							</div>
						</div>
					</div> -->
						<!-- <div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.device_id')}}</label>
							<div class="col-7">
								<input type="text" class="form-control user_phone" name="device_id" value="{{$user->device_id}}">
							</div>

						</div>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.fcm_id')}}</label>
							<div class="col-7">
								<input type="text" class="form-control user_phone" name="fcm_id" value="{{$user->fcm_id}}">
							</div>

						</div> -->

						<div class="form-group row width-100">
							<label class="col-2 control-label">{{trans('lang.profile_image')}}</label>
							<input type="file" class="col-6 photo" name="photo" onchange="readURL(this);">

							{{--@if (file_exists('assets/images/users'.'/'.$user->photo_path) && !empty($user->photo_path))--}}
                                          @if (file_exists(public_path('assets/images/users'.'/'.$user->photo_path)) && !empty($user->photo_path))
                                            <img class="rounded" id="uploding_image" style="width:50px" src="{{asset('assets/images/users').'/'.$user->photo_path}}" alt="image">
                                        @else
                                        <img class="rounded" id="uploding_image" style="width:50px" src="{{asset('assets/images/placeholder_image.jpg')}}" alt="image">

                                        @endif

						</div>
						<!-- <div class="form-group row width-100">
							<label class="col-2 control-label">{{trans('lang.nic_image')}}</label>
							<input type="file" class="col-6 photo" name="nic_path" onchange="readURLNic(this);">

							@if (file_exists('assets/images/users'.'/'.$user->photo_nic_path) && !empty($user->photo_nic_path))
                                            <img class="rounded"  id="user_nic_image" style="width:50px" src="{{asset('assets/images/users').'/'.$user->photo_nic_path}}" alt="image">
                                        @else
                                        <img class="rounded" style="width:50px" id="user_nic_image" src="{{asset('assets/images/placeholder_image.jpg')}}" alt="image">

                                        @endif

						</div> -->
						<div class="form-group row width-50">

							<div class="form-check">

								 @if ($user->statut === "yes")
									 <input type="checkbox" class="user_active" name="statut" id="user_active" checked="checked"  value="yes"/>
       							@else
          							<input type="checkbox" class="user_active" name="statut" id="user_active" value="no"/>
       							@endif
								<label class="col-3 control-label" for="user_active">{{trans('lang.active')}}</label>
							</div>

						</div>

						<!-- <div class="form-group row width-50">

							<div class="form-check">

								 @if ($user->statut === "yes")
									 <input type="checkbox" class="user_active" name="notify" id="notify" checked="checked">
       							@else
          							<input type="checkbox" class="user_active" name="notify" id="notify">
       							@endif
								<label class="col-3 control-label" for="notify">{{trans('lang.Tonotify')}}</label>
							</div>

						</div> -->
						</div>
					</fieldset>

					<!--<fieldset>
						<legend>{{trans('lang.address')}}</legend>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.address')}}</label>
							<div class="col-7">
								<input type="text" class="form-control address_line1" name="address" value="{{$user->address}}">
							</div>

						</div>


					</fieldset>-->

				</div>
<div class="form-group col-12 text-center btm-btn" >
			<button type="submit" class="btn btn-primary  save_user_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
			<a href="{!! route('users') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
		</div>
			</div>
		</div>
		

</div>
</form>

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
				$('#user_nic_image').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
