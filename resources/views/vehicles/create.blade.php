@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.vehicles')}}</h3>
        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('vehicles') !!}">{{trans('lang.vehicles')}}</a>
                </li>
                <li class="breadcrumb-item active">{{trans('lang.create_vehicle')}}</li>
            </ol>
        </div>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card pb-4">

                    <div class="card-body">

                        <div id="data-table_processing" class="dataTables_processing panel panel-default"
                            style="display: none;">{{trans('lang.processing')}}</div>
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
                        <form action="{{route('vehicles.store')}}" method="post" enctype="multipart/form-data"
                            id="create_driver">
                            @csrf

                            <div class="row restaurant_payout_create">
                                <div class="restaurant_payout_create-inner">
                                    <fieldset>
                                        <legend>{{trans('lang.vehicle_info')}}</legend>
                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.vehicle_type')}}</label>
                                            <div class="col-7">
                                                <select class="form-control model" name="id_type_vehicule"
                                                    id="id_type_vehicule">
                                                    <option value="">{{trans('lang.select_type')}}</option>
                                                    @foreach($vehicleType as $value)
                                                    <option value="{{ $value->id }}">{{$value->libelle}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.vehicle_brand')}}</label>
                                            <div class="col-7">
                                                <select class="form-control brand_id" name="brand">
                                                    <option value="">{{trans('lang.select_brand')}}</option>
                                                    @foreach($brand as $value)
                                                    <option value="{{ $value->id }}">{{$value->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>

                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.vehicle_model')}}</label>
                                            <div class="col-7">
                                                <select class="form-control model" name="model" id="model">
                                                    <option value="">{{trans('lang.select_model')}}</option>
                                                </select>
                                                <div class="form-text text-muted">{{trans('lang.car_model_help')}}</div>
                                            </div>
                                        </div>


                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.vehicle_km')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control model" name="km"
                                                    value="{{Request::old('km')}}">
                                                <div class="form-text text-muted">{{trans('lang.vehicle_km_help')}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.vehicle_milage')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control model" name="milage"
                                                    value="{{Request::old('milage')}}">
                                                <div class="form-text text-muted">{{trans('lang.vehicle_milage_help')}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row width-50">
                                            <label
                                                class="col-3 control-label">{{trans('lang.vehicle_numberplate')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control car_number" name="car_number"
                                                    value="{{Request::old('car_number')}}">
                                                <div class="form-text text-muted">{{trans('lang.car_number_help')}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.vehicle_color')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control color" name="color"
                                                    value="{{Request::old('color')}}">
                                                <div class="form-text text-muted">
                                                    {{ trans("lang.car_color_help") }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row width-50">
                                            <label
                                                class="col-3 control-label">{{trans('lang.number_of_pessanger')}}</label>
                                            <div class="col-7">
                                                <input type="number" class="form-control" name="passenger"
                                                    value="{{Request::old('passenger')}}">
                                                <div class="form-text text-muted w-50">
                                                    {{ trans("lang.number_of_passenger_help") }}
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
                                            <div id="image_preview" style="display: none; padding-left: 15px;">
                                               
                                            </div>
                                        </div>
                                    </fieldset>
                                    <!-- <fieldset>
                                        <legend>{{trans('lang.bank_details')}}</legend>
                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.bank_name')}}</label>
                                            <div class="col-7">
                                                <input type="text" name="bank_name" class="form-control" id="bankName">

                                            </div>
                                        </div>
                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.branch_name')}}</label>
                                            <div class="col-7">
                                                <input type="text" name="branch_name" class="form-control"
                                                    id="branchName">

                                            </div>

                                        </div>

                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.holder_name')}}</label>
                                            <div class="col-7">
                                                <input type="text" name="holder_name" class="form-control"
                                                    id="holderName">

                                            </div>
                                        </div>

                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.account_number')}}</label>
                                            <div class="col-7">
                                                <input type="text" name="account_number" class="form-control"
                                                    id="accountNumber">

                                            </div>
                                        </div>
                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{ trans("lang.ifsc_code") }}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control user_phone" name="ifsc_code">

                                            </div>

                                        </div>
                                        <div class="form-group row width-50">
                                            <label
                                                class="col-3 control-label">{{trans('lang.other_information')}}</label>
                                            <div class="col-7">
                                                <input type="text" name="other_information" class="form-control"
                                                    id="otherDetails">

                                            </div>
                                        </div>

                                    </fieldset> -->

                                    <div class="form-group col-12 text-center btm-btn">
                                        <button type="submit" class="btn btn-primary save_driver_btn"><i
                                                class="fa fa-save"></i> {{ trans('lang.save')}}</button>
                                        <a href="{!! route('vehicles') !!}" class="btn btn-default"><i
                                                class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
                                    </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
                // $('select[name="model"]').append('<option value="">' + "No data found" + '</option>');
                $('select[name="model"]').empty();
            }
        });


        $('#images').on('change', function() {
        $('#image_preview').html('');
        var files = $(this)[0].files;

        if(files.length > 0) {
            for(var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image_preview').append('<img src="' + e.target.result + '" style="width:150px;height:auto;margin-right:10px" class="img-thumbnail">');
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