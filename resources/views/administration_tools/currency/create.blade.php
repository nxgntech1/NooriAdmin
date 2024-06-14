@extends('layouts.app')

@section('content')
<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.currency_create')}}</h3>
		</div>

		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
				<li class="breadcrumb-item"><a href= "{!! route('currency') !!}" >{{trans('lang.currency_plural')}}</a></li>
				<li class="breadcrumb-item active">{{trans('lang.currency_create')}}</li>
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
		<form action="{{route('currency.store')}}" method="post"  enctype="multipart/form-data" id="create_driver">
		@csrf
			<div class="row restaurant_payout_create">
          	<div class="restaurant_payout_create-inner">
          		<fieldset>
              		<legend>{{trans('lang.currency_create')}}</legend>

						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.currency_plural')}}</label>
							<div class="col-7">
								<input type="text" class="form-control libelle" name="libelle">

							</div>
						</div>

						<div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.currency_symbol')}}</label>
							<div class="col-7">
								<input type="text" class="form-control code" name="symbol">

							</div>
						</div>
						<div class="form-group row width-50">
						<label class="col-3 control-label">{{trans('lang.digit_after_decimal_point')}}</label>
						<div class="col-7">
							<input type="number" name="decimal_digit" class="form-control decimal_degits" value="0">

						</div>
					</div>

                    <div class="form-group row width-50">
						<div class="form-check">
							<input type="checkbox" class="user_active" id="status" name="statut" value="yes">
							<label class="col-3 control-label" for="status">{{trans('lang.status')}}</label>

						</div>

					</div>

					<div class="form-group row width-50">
						<div class="form-check">
							<input type="checkbox" class="symbol_at_right" id="symbol_at_right" name="symbol_at_right" >
							<label class="col-3 control-label" for="symbol_at_right">{{trans('lang.symbol_at_right')}}</label>

						</div>

					</div>


					</div>



		 		</fieldset>


		</div>
	</div>


			<div class="form-group col-12 text-center btm-btn" >
				<button type="submit" class="btn btn-primary  create_user_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
				<a href="{!! route('currency') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
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
