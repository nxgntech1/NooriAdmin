@extends('layouts.app')

@section('content')
<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.language_create')}}</h3>
		</div>

		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
				<li class="breadcrumb-item"><a href= "{!! route('language') !!}" >{{trans('lang.user_plural')}}</a></li>
				<li class="breadcrumb-item active">{{trans('lang.language_create')}}</li>
			</ol>
		</div>
</div>



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
		<form action="{{route('language.storeuser')}}" method="post"  enctype="multipart/form-data" id="create_driver">
		@csrf
			<div class="row restaurant_payout_create">
          	<div class="restaurant_payout_create-inner">
          		<fieldset>
              		<legend>{{trans('lang.language')}}</legend>

						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.language')}}</label>
							<div class="col-7">
								<input type="text" class="form-control language" name="language">
								<div class="form-text text-muted">
									{{ trans("lang.language_help") }}
								</div>
							</div>
						</div>

						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.code')}}</label>
							<div class="col-7">
								<input type="text" class="form-control code" name="code">
								<div class="form-text text-muted">
									{{ trans("lang.code_help") }}
								</div>
							</div>
						</div>
					<div class="form-group row width-100">
						<label class="col-3 control-label">{{trans('lang.flag')}}</label>
						<input type="file" class="col-7" name="flag">
						<div class="placeholder_img_thumb user_image"></div>
						<div id="uploding_image"></div>
					</div>	
					
					

					<div class="form-group row width-50">
						<div class="form-check">                    
							<input type="checkbox" class="user_active" id="status" name="status">
							<label class="col-3 control-label" for="status">{{trans('lang.active')}}</label>

						</div>
					</div>	
					
					<div class="form-group row width-50">
						<div class="form-check">                    
							<input type="checkbox" class="user_active" id="is_rtl" name="is_rtl" >
							<label class="col-3 control-label" for="is_rtl">{{trans('lang.is_rtl')}}</label>

						</div>
					</div>
		 		</fieldset> 

			
		</div>
	</div>


			<div class="form-group col-12 text-center btm-btn" >
				<button type="submit" class="btn btn-primary  create_user_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
				<a href="{!! route('language') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
			</div>

		</form>

		</div>

@endsection

@section('scripts')



@endsection