@extends('layouts.app')

@section('content')


<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.rental_vehicle_type')}}</h3>
		</div>

		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href= "{{ url('vehicle-rental-type/index') }}" >{{trans('lang.vehicle_type')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.create_rental_vehicle_type')}}</li>
			</ol>
		</div>
   </div>

		<div class="container-fluid">
      <div class="row">
        <div class="col-12">
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
        <form action="{{route('vehicle-rental-type.store')}}" method="post" enctype="multipart/form-data">
          @csrf
      <div class="row restaurant_payout_create">
        <div class="restaurant_payout_create-inner">

          <fieldset>
            <legend>{{trans('lang.create_rental_vehicle_type')}}</legend>
            <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.name')}}</label>
              <div class="col-7">
                  <input type="text" class="form-control" value="{{ Request::old('libelle') }}" name="libelle">
                  <!-- <div class="form-text text-muted"></div> -->
              </div>
            </div>

            <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.per_day_price')}}</label>
              <div class="col-7">
                  <input type="text" class="form-control" name="prix" value="{{ Request::old('prix') }}">

              </div>
            </div>

            <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.Image')}}</label>
              <div class="col-7">
                  <input type="file" class="form-control" name="image" value="" onchange="readURL(this);">
                  <!-- <div class="form-text text-muted"></div> -->
                  <div id="image_preview" style="display: none; padding-left: 15px;">
						<img class="rounded" style="width:50px" id="uploding_image" src="#" alt="image">
						</div>
              </div>
            </div>

						 <div class="form-group row width-50">
							<label class="col-3 control-label">{{trans('lang.number_of_passenger')}}</label>
							<div class="col-7">
									<input type="text" class="form-control" name="no_of_passenger" value="{{ Request::old('no_of_passenger') }}">
							</div>
						</div>
						<!--<div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.vehicle_number')}}</label>
              <div class="col-7">
                <input type="text" class="form-control" name="nombre" value="{{ Request::old('nombre') }}">
              </div>
            </div> -->
						<!-- <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.places')}}</label>
              <div class="col-7">
                <input type="text" class="form-control" name="nb_place" value="{{ Request::old('nb_place') }}">
              </div>
            </div> -->
						<div class="form-group row width-50">
							<div class="form-check">
								<input type="checkbox" class="user_active" id="user_active" name="status">
								<label class="col-3 control-label" for="user_active">{{trans('lang.active')}}</label>

							</div>
								</div>


						</div>

          </fieldset>

        </div>
<div class="form-group col-12 text-center btm-btn" >
      <button type="submit" class="btn btn-primary  save_user_btn" ><i class="fa fa-save"></i>{{ trans('lang.save')}}</button>
      <a href="{{ url('vehicle-rental-type/index') }}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
    </div>
      </div>
    </div>
    

</div>
</form>
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
