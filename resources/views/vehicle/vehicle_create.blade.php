@extends('layouts.app')

@section('content')


<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.vehicle')}}</h3>
		</div>

		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href= "{{ url('vehicle/vehicle') }}" >{{trans('lang.vehicle')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.create_vehicle')}}</li>
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
        <form action="{{route('vehicle.create')}}" method="post" enctype="multipart/form-data">
          @csrf
      <div class="row restaurant_payout_create">
        <div class="restaurant_payout_create-inner">

          <fieldset>
            <legend>{{trans('lang.create_vehicle')}}</legend>
            
            <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.vehicle_type')}}</label>
              <div class="col-7">
                <select name="id_type_vehicule_rental" class="form-control">
                  @foreach($vehicle as $type1)
                    <option value="{{$type1->id}}">{{$type1->libelle}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            
            <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.price')}}</label>
              <div class="col-7">
                <input type="text" class="form-control" name="prix" value="{{ Request::old('prix') }}">
              </div>
            </div>

            <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.places')}}</label>
              <div class="col-7">
                <input type="text" class="form-control" name="nb_place" value="{{ Request::old('nb_place') }}">
              </div>
            </div>

            <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.Image')}}</label>
              <div class="col-7">
                <input type="file" class="form-control" name="image" value="{{ Request::old('image') }}" onchange="readURL(this);">
              </div>
              <div id="image_preview" style="display: none; padding-left: 15px;">
						<img class="rounded" style="width:50px" id="uploding_image" src="#" alt="image">
						</div>
            </div>

            <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.status')}}</label>
              <div class="col-7">
                <select name="statut" class="form-control">
                  @if (Request::old('statut') == 'yes')
                     <option value="yes" selected>{{trans('lang.yes')}}</option>
                  @else
                     <option value="yes">{{trans('lang.yes')}}</option>
                  @endif
                  @if (Request::old('statut') == 'no')
                     <option value="no" selected>{{trans('lang.no')}}</option>
                  @else
                     <option value="no">{{trans('lang.no')}}</option>
                  @endif
                </select>
              </div>
            </div>

            <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.vehicle_number')}}</label>
              <div class="col-7">
                <input type="text" class="form-control" name="nombre" value="{{ Request::old('nombre') }}">
              </div>
            </div>

          </fieldset>

        </div>
      </div>
    </div>
    <div class="form-group col-12 text-center btm-btn" >
      <button type="submit" class="btn btn-primary  save_user_btn" ><i class="fa fa-save"></i>{{ trans('lang.save')}}</button>
      <a href="{{ url('vehicle/vehicle') }}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
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

        $("#is_active").click(function () {
            $("#example24 .is_open").prop('checked', $(this).prop('checked'));

        });
        
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