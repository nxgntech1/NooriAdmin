@extends('layouts.app')

@section('content')


<div class="page-wrapper">
  <div class="row page-titles">
    <div class="col-md-5 align-self-center">
      <h3 class="text-themecolor">{{trans('lang.edit_vehicle_info')}}</h3>
    </div>

    <div class="col-md-7 align-self-center">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
        <li class="breadcrumb-item"><a href="{!! route('vehicles') !!}">{{trans('lang.vehicles')}}</a></li>
        <li class="breadcrumb-item active">{{trans('lang.edit_vehicle_info')}}</li>
      </ol>
    </div>
  </div>







  <div class="container-fluid">

    <div class="card pb-4">

      <div class="card-body">

        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
          {{trans('lang.processing')}}</div>
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
        <form method="post" action="{{ route('vehicles.update',$vehicle->id) }}" enctype="multipart/form-data">
          @csrf
          @method("PUT")
          <div class="row restaurant_payout_create">
            <div class="restaurant_payout_create-inner">

              <fieldset>
                <legend>{{trans('lang.vehicle_info')}}</legend>
                <div class="form-group row width-50">
                  <label class="col-3 control-label">{{trans('lang.vehicle_type')}}</label>
                  <div class="col-7">
                    <select class="form-control model" name="id_type_vehicule" id="id_type_vehicule">
                      <option value="">{{trans('lang.select_type')}}</option>
                      @foreach($vehicletype as $value)
                      <?php if ($value->id == !empty($vehicle) ? $vehicle->id_type_vehicule : '') {
                        $selected = 'Selected';
                      } else {
                        $selected = '';
                      }
                      ?>
                      <option value="{{ $value->id }}" <?php echo $selected ?>>{{$value->libelle}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row width-50">
                  <label class="col-3 control-label">{{trans('lang.vehicle_brand')}}</label>
                  <div class="col-7">
                    <select class="form-control brand_id" name="brand">
                      <option value="">{{trans('lang.select_brand')}}</option>
                      {{--<input type="text" class="form-control address_line1" name="brand"
                        value="{{Request::old('brand')}}">--}}
                      @foreach($brand as $value)
                      <?php if ($value->id == !empty($vehicle) ? $vehicle->brand : '') {
                        $selected = 'Selected';
                      } else {
                        $selected = '';
                      }
                      ?>
                      <option value="{{ $value->id }}" <?php echo $selected ?>>{{$value->name}}</option>
                      @endforeach
                    </select>
                  </div>

                </div>

                <div class="form-group row width-50">
                  <label class="col-3 control-label">{{trans('lang.vehicle_model')}}</label>
                  <div class="col-7">
                    <select class="form-control model" name="model" id="model">
                      <option value="">{{trans('lang.select_model')}}</option>
                      @foreach($carmodel as $value)
                      <?php
                      if ($value->id == !empty($vehicle) ? $vehicle->model : '') {
                        $selected = 'Selected';
                      } else {
                        $selected = '';
                      }
                      ?>
                      <option value="{{ $value->id }}" <?php echo $selected ?>>{{$value->name}}</option>
                      @endforeach
                    </select>
                    <div class="form-text text-muted">{{trans('lang.car_model_help')}}</div>
                  </div>
                </div>

                <div class="form-group row width-50">
                  <label class="col-3 control-label">{{trans('lang.vehicle_color')}}</label>
                  <div class="col-7">
                    <input type="text" class="form-control user_last_name" name="color"
                      value="{{!empty($vehicle) ? $vehicle->color : ''}}">
                    <div class="form-text text-muted">
                      {{ trans("lang.car_color_help") }}
                    </div>
                  </div>
                </div>
                <div class="form-group row width-50">
                  <label class="col-3 control-label">{{trans('lang.vehicle_numberplate')}}</label>
                  <div class="col-7">
                    <input type="text" class="form-control user_email" name="numberplate"
                      value="{{!empty($vehicle) ? $vehicle->numberplate : ''}}">
                    <div class="form-text text-muted">{{trans('lang.car_number_help')}}</div>
                  </div>
                </div>

                <div class="form-group row width-50">
                  <label class="col-3 control-label">{{trans('lang.number_of_pessanger')}}</label>
                  <div class="col-7">
                    <input type="number" class="form-control user_phone" name="passenger"
                      value="{{!empty($vehicle) ? $vehicle->passenger : ''}}">
                    <div class="form-text text-muted w-50">
                      {{ trans("lang.number_of_passenger_help") }}
                    </div>
                  </div>

                </div>
                <div class="form-group row width-50">
                  <label class="col-3 control-label">{{trans('lang.vehicle_milage')}}</label>
                  <div class="col-7">
                    <input type="number" class="form-control user_phone" name="milage"
                      value="{{ !empty($vehicle) ? $vehicle->milage : ''}}">
                    <div class="form-text text-muted w-50">

                    </div>
                  </div>

                </div>
                <div class="form-group row width-50">
                  <label class="col-3 control-label">{{trans('lang.vehicle_km')}}</label>
                  <div class="col-7">
                    <input type="number" class="form-control user_phone" name="km"
                      value="{{ !empty($vehicle) ? $vehicle->km : ''}}">
                    <div class="form-text text-muted">
                      {{trans('lang.vehicle_km_help')}}
                    </div>
                  </div>

                </div>
                <div class="form-group row width-50">
                    <label class="col-3 control-label">{{trans('lang.vehicle_images')}}</label>
                    <div class="col-7">
                        <input type="file" class="" name="images[]" id="images"
                            value="{{Request::old('photo')}}" multiple>
                        <div class="form-text text-muted">{{trans('lang.vehicle_images_help')}}
                        </div>
                    </div>
                    
                    @if(!empty($vehicleImage))
                    <div id="image_preview" class="uploaded-images"  style="display: block;">
                        @foreach($vehicleImage as $fileName)
                        <div class="image-container">
                            <img src="{{ asset('assets/images/vehicle').'/'.$fileName->image }}" style="width:150px;height:auto;margin-right:10px" class="img-thumbnail" />
                            <span class="delete-icon" data-id="{{ $fileName->id }}">&times;</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                        <p>No images found.</p>
                    @endif
                    
                        
                    
                </div>

              </fieldset>

            </div>
          </div>
      </div>
      <div class="form-group col-12 text-center btm-btn">
        <button type="submit" class="btn btn-primary  save_user_btn"><i class="fa fa-save"></i> {{
          trans('lang.save')}}</button>
        <a href="{!! route('vehicles') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{
          trans('lang.cancel')}}</a>
      </div>

    </div>
    </form>
  </div>




</div>



@endsection

@section('scripts')
<script>
  $(document).ready(function () {
    $('select[name="brand"]').on('change', function () {

      var brand_id = $(this).val();
      var id_type_vehicule = $('select[name="id_type_vehicule"]').val();
      var url = "{{ route('driver.model',':brandId') }}";
      url = url.replace(':brandId', brand_id);

      if (brand_id) {
        $.ajax({
          url: url,
          type: "POST",
          data: {
            id_type_vehicule: id_type_vehicule,
            _token: '{{csrf_token()}}',
          },

          dataType: 'json',
          success: function (data) {
            $('select[name="model"]').empty();
            $('select[name="model"]').append('<option value="">{{trans("lang.select_model")}}</option>');
            $.each(data.model, function (key, value) {
              $('select[name="model"]').append('<option value="' + value.id + '">' + value.name + '</option>');
            });
          }
        });
      } else {
        $('select[name="model"]').empty();
      }
    });
    $('.delete-icon').on('click', function() {
        var imageId = $(this).data('id');
        var $imageContainer = $(this).closest('.image-container');
        var url = "{{ route('vehicles.deleteimage',':imageId') }}";
      url = url.replace(':imageId', imageId);
        if(confirm('Are you sure you want to delete this image?')) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: imageId
                },
                success: function(response) {
                    alert(JSON.stringify(response));
                    if(response.success) {
                        $imageContainer.remove();
                    } else {
                        alert('Failed to delete the image. Please try again.');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });

    $('#images').on('change', function() {
       // $('#image_preview').html('');
        var files = $(this)[0].files;

        if(files.length > 0) {
            for(var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image_preview').append('<div class="image-container"><img src="' + e.target.result + '" style="width:150px;height:auto;margin-right:10px" class="img-thumbnail" /><span class="delete-icon" data-id="'+ e.target.id +'">&times;</span></div>');
                    $('#image_preview').show();
                }
                reader.readAsDataURL(files[i]);
            }
        }
    });


  });

  function readURL(input) {
    console.log(input.files);
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        //	$('#image_preview').show();
        $('#uploding_image').attr('src', e.target.result);


      }

      reader.readAsDataURL(input.files[0]);
    }

  }
  function readCarURL(input) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        //	$('#image_preview').show();
        $('#car_image').attr('src', e.target.result);


      }

      reader.readAsDataURL(input.files[0]);
    }

  }
  function readURLNic(input) {
    console.log(input.files);
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        //$('#placeholder_img_thumb').show();
        $('#user_nic_image').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

</script>



@endsection