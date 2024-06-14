@extends('layouts.app')


@section('content')
<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.webuser_edit')}}</h3>
		</div>
		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
				<li class="breadcrumb-item"><a href= "{!! route('webuser') !!}" >{{trans('lang.web_users')}}</a></li>
				<li class="breadcrumb-item active">{{trans('lang.webuser_edit')}}</li>
			</ol>
		</div>

	</div>
	<div class="container-fluid">

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
				<form method="post" action="{{ route('webuser.update',$user->id) }}" enctype="multipart/form-data">
					@csrf
					@method("PUT")
			<div class="row restaurant_payout_create">
				<div class="restaurant_payout_create-inner">

					<fieldset>
						<legend>{{trans('lang.user_edit')}}</legend>
						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.name')}}</label>
							<div class="col-7">
								<input type="text" class="form-control user_first_name" name="name" value="{{$user->name}}">
								<div class="form-text text-muted">
									{{ trans("lang.category_name_help") }}
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

						</div>
					</fieldset>

					

				</div>
<div class="form-group col-12 text-center btm-btn" >
			<button type="submit" class="btn btn-primary  save_user_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
			<a href="{!! route('webuser') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
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
