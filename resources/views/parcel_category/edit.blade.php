@extends('layouts.app')

@section('content')
<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.edit_parcelCategory')}}</h3>
		</div>

		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
				<li class="breadcrumb-item"><a href="{!! route('parcel-category') !!}">{{trans('lang.parcelCategory_plural')}}</a></li>
				<li class="breadcrumb-item active">{{trans('lang.edit_parcelCategory')}}</li>
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
						<form action="{{ route('parcel-category.update',$parcelCategory->id) }}" method="post" enctype="multipart/form-data">
							@csrf
							@method("PUT")
							<div class="row restaurant_payout_create">
								<div class="restaurant_payout_create-inner">
									<fieldset>
										<legend>{{trans('lang.edit_parcelCategory')}}</legend>

										<div class="form-group row width-50">
											<label class="col-3 control-label">{{trans('lang.title')}}</label>
											<div class="col-7">
												<input type="text" class="form-control libelle" name="title" value="{{$parcelCategory->title}}">

											</div>
										</div>
										<div class="form-group row width-50">
											<label class="col-3 control-label">{{trans('lang.photo')}}</label>
											<div class="col-7">
												<input type="file" class="form-control" name="image" value="" onchange="readURL(this);">
												@if (file_exists(public_path('assets/images/parcel_category'.'/'.$parcelCategory->image)) && !empty($parcelCategory->image))
												<img class="rounded" style="width:50px" id="uploding_image" src="{{asset('assets/images/parcel_category').'/'.$parcelCategory->image}}" alt="image">
												@else
												<img class="rounded" style="width:50px" id="uploding_image" src="{{asset('assets/images/placeholder_image.jpg')}}" alt="image">
												@endif
											</div>
										</div>

										<div class="form-group row width-100">
											<div class="form-check">
												@if ($parcelCategory->status === "yes")
												<input type="checkbox" class="user_active" id="status" name="status" checked="checked">
												@else
												<input type="checkbox" class="user_active" id="status" name="status">
												@endif
												<label class="col-3 control-label" for="status">{{trans('lang.status')}}</label>

											</div>
										</div>

								</div>



								</fieldset>


							</div>
					</div>


					<div class="form-group col-12 text-center btm-btn">
						<button type="submit" class="btn btn-primary  create_user_btn"><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
						<a href="{!! route('parcel-category') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
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

$(document).ready(function () {
            $(".shadow-sm").hide();
        })

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