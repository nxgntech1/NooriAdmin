@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.settings')}}</h3>
        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('settings') !!}">{{trans('lang.user_plural')}}</a>
                </li>
                <li class="breadcrumb-item active">{{trans('lang.settings')}}</li>
            </ol>
        </div>
    </div>


    <div class="error_top"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card pb-4">
                    <div class="card-body">

                        <div id="data-table_processing" class="dataTables_processing panel panel-default"
                            style="display: none;">
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
                        <form action="{{route('settings.update',['id'=>1])}}" method="post"
                            enctype="multipart/form-data" id="setting_form">
                            @csrf

                            <div class="row restaurant_payout_create">
                                <div class="restaurant_payout_create-inner">
                                    @foreach($settings as $setting)
                                    <fieldset>
                                        <legend>{{trans('lang.settings')}}</legend>

                                        <div class="form-group row width-50">
                                            <label
                                                class="col-3 control-label">{{trans('lang.settings_panel_title')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control title" name="title" id="title"
                                                    value="{{$setting->title}}">

                                            </div>
                                        </div>

                                        <div class="form-group row width-50">
                                            <label
                                                class="col-3 control-label">{{trans('lang.settings_panel_footer')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control user_last_name" name="footer"
                                                    id="footer" value="{{$setting->footer}}">

                                            </div>
                                        </div>
                                        <!-- <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{trans('lang.settings_panel_email')}}</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control user_phone" name="email"
                                                   id="email" value="{{$setting->email}}">

                                        </div>

                                    </div> -->


                                        <div class="form-group row width-50">
                                            <label
                                                class="col-5 control-label">{{trans('lang.website_color_settings')}}</label>
                                            <br>
                                            <input type="color" class="ml-3" name="website_color" id="website_color"
                                                value="{{$setting->website_color}}">
                                        </div>
                                        <div class="form-group row width-50">
                                            <label
                                                class="col-5 control-label">{{trans('lang.driverapp_color_settings')}}</label>
                                            <br>
                                            <input type="color" class="ml-3" name="driverapp_color" id="driverapp_color"
                                                value="{{$setting->driverapp_color}}">
                                        </div>
                                        <div class="form-group row width-50">
                                            <label
                                                class="col-5 control-label">{{trans('lang.adminpanel_color_settings')}}</label>
                                            <br>
                                            <input type="color" class="ml-3" name="adminpanel_color"
                                                id="adminpanel_color" value="{{$setting->adminpanel_color}}">
                                        </div>
                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.driver_radios')}}
                                                ({{$setting->delivery_distance}})</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control user_phone" name="driver_radios"
                                                    id="driver_radios" value="{{$setting->driver_radios}}">
                                            </div>
                                        </div>

                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.app_logo')}}</label>
                                            <input type="file" class="col-7" name="app_logo" id="app_logo"
                                                onchange="readURL(this);">
                                            <div id="image_preview" style="padding-left: 15px;">
                                                @if (file_exists(public_path('assets/images/'.$setting->app_logo)) &&
                                                !empty($setting->app_logo))
                                                <img class="rounded" id="uploding_image" style="width:50px"
                                                    src="{{asset('assets/images/').'/'.$setting->app_logo}}"
                                                    alt="image">
                                                @else
                                                <img class="rounded" id="uploding_image" style="width:50px"
                                                    src="{{asset('assets/images/logo-placeholder-image.png')}}"
                                                    alt="image">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.app_logo_small')}}</label>
                                            <input type="file" class="col-7" name="app_logo_small" id="app_logo_small"
                                                onchange="readURL2(this);">
                                            <div id="image_preview2" style="padding-left: 15px;">
                                                @if (file_exists(public_path('assets/images/'.$setting->app_logo_small))
                                                && !empty($setting->app_logo_small))
                                                <img class="rounded" id="uploding_image2" style="width:50px"
                                                    src="{{asset('assets/images/').'/'.$setting->app_logo_small}}"
                                                    alt="image">
                                                @else
                                                <img class="rounded" id="uploding_image2" style="width:50px"
                                                    src="{{asset('assets/images/logo-placeholder-image.png')}}"
                                                    alt="image">
                                                @endif
                                            </div>
                                        </div>

                                    </fieldset>

                                    <fieldset>
                                        <legend>{{trans('lang.google_map_api_key')}}</legend>

                                        <div class="form-group row width-100">
                                            <label
                                                class="col-3 control-label">{{trans('lang.google_map_api_key')}}</label>
                                            <div class="col-7">
                                                <input type="password" class="form-control address_line1" name="map_key"
                                                    id="map_key" value="{{$setting->google_map_api_key}}">
                                            </div>
                                        </div>
                                    </fieldset>
                                    <!-- <fieldset>
                                    <legend>{{trans('lang.is_social_media')}}</legend>

                                    <div class="form-group ">
                                        <div class="form-check ">
                                            @if ($setting->is_social_media === "true")
                                            <input type="checkbox"
                                                   class="col-7 form-check-inline user_active"
                                                   id="user_active" name="is_social_media"
                                                   checked="checked">
@else
                                            <input type="checkbox"
                                                   class="col-7 form-check-inline user_active"
                                                   id="user_active" name="is_social_media">

@endif
                                                <label class="form title"
                                                       for="user_active">{{trans('lang.is_social_media')}}</label>
                                        </div>
                                    </div>


                                </fieldset> -->
                                    <fieldset>
                                        <legend>{{trans('lang.ride_settings')}}</legend>

                                        <!-- <div class="form-group row width-100">
                                        <label class="col-3 control-label">{{trans('lang.user_schedule_time')}}</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control user_schedule_time"
                                                   name="user_schedule_time" id="user_schedule_time"
                                                   value="{{$setting->user_ride_schedule_time_minute}}">
                                        </div>
                                    </div> -->

                                        <div class="form-group row width-100">
                                            <label
                                                class="col-3 control-label">{{trans('lang.trip_accept_reject_by_driver')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control trip_accept_reject_by_driver"
                                                    name="trip_accept_reject_by_driver"
                                                    id="trip_accept_reject_by_driver"
                                                    value="{{$setting->trip_accept_reject_driver_time_sec}}">
                                            </div>
                                        </div>


                                        <!-- <div class="form-group row width-100">
                                        <label class="col-3 control-label">{{trans('lang.show_ride_without_destination')}}</label>
                                        <div class="col-7">
                                            <select name="show_ride" id="show_ride" class="form-control">
                                                @if($setting->show_ride_without_destination == 'yes')
                                                <option value="yes" selected>Yes</option>
                                                <option value="no">No</option>
@else
                                                <option value="no" selected>No</option>
                                                <option value="yes">Yes</option>
@endif
                                                    </select>
                                                </div>
                                            </div> -->

                                        <!-- <div class="form-group row width-100">
                                        <label class="col-3 control-label">{{trans('lang.show_ride_later')}}</label>
                                        <div class="col-7">
                                            <select name="show_ride_later" id="show_ride_later"
                                                    class="form-control">
                                                @if($setting->show_ride_later == 'yes')
                                                <option value="yes" selected>Yes</option>
                                                <option value="no">No</option>
@else
                                                <option value="no" selected>No</option>
                                                <option value="yes">Yes</option>
@endif
                                                    </select>
                                                </div>
                                            </div> -->

                                        <div class="form-group row width-100">
                                            <label class="col-3 control-label">{{trans('lang.show_ride_otp')}}</label>
                                            <div class="col-7">
                                                <select name="show_ride_otp" id="show_ride_otp" class="form-control">
                                                    @if($setting->show_ride_otp == 'yes')
                                                    <option value="yes" selected>Yes</option>
                                                    <option value="no">No</option>
                                                    @else
                                                    <option value="no" selected>No</option>
                                                    <option value="yes">Yes</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend>{{trans('lang.delivery_charge_distance')}}</legend>
                                        <div class="form-group row width-100">
                                            <label class="col-3 control-label">{{trans('lang.distance')}}</label>
                                            <div class="col-7">
                                                <select name="delivery_distance" id="delivery_distance"
                                                    class="form-control">
                                                    @if($setting->delivery_distance == 'Miles')
                                                    <option value="KM">KM</option>
                                                    <option value="Miles" selected>Miles</option>
                                                    @else
                                                    <option value="KM" selected>KM</option>
                                                    <option value="Miles">Miles</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                    </fieldset>
                                    <fieldset>
                                        <legend>{{trans('lang.wallet_settings')}}</legend>
                                        <div class="form-group row width-100">
                                            <label
                                                class="col-3 control-label">{{trans('lang.minimum_deposit_amount')}}</label>

                                            <div class="col-7">
                                                <div class="control-inner">
                                                    <input type="number" class="form-control minimum_deposit_amount"
                                                        name="minimum_deposit_amount"
                                                        value="{{$setting->minimum_deposit_amount}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row width-100">
                                            <label
                                                class="col-3 control-label">{{trans('lang.minimum_withdrawal_amount')}}</label>

                                            <div class="col-7">
                                                <div class="control-inner">
                                                    <input type="number" class="form-control minimum_withdrawal_amount"
                                                        name="minimum_withdrawal_amount"
                                                        value="{{$setting->minimum_withdrawal_amount}}">
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>
                                    <fieldset>
                                        <legend>{{trans('lang.referral_settings')}}</legend>
                                        <div class="form-group row width-100">
                                            <label class="col-3 control-label">{{trans('lang.referral_amount')}}</label>

                                            <div class="col-7">
                                                <div class="control-inner">
                                                    <input type="number" class="form-control referral_amount"
                                                        name="referral_amount" value="{{$setting->referral_amount}}">
                                                    <span class="currentCurrency">{{$currency->symbole}}</span>
                                                    <div class="form-text text-muted">
                                                        {{ trans("lang.referral_amount_help") }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend>{{trans('lang.parcel_delivery_feature')}}</legend>
                                        <div class="form-group row width-100">
                                            <div class="form-check">
                                                <input type="checkbox" class="active" id="parcel_active"
                                                    name="parcel_active" {{($setting->parcel_active=="yes") ? "checked"
                                                : ""}}>
                                                <label class="col-3 control-label"
                                                    for="parcel_active">{{trans('lang.active')}}</label>
                                            </div>
                                        </div>
                                        <div class="parcel_setting">
                                            <div class="form-group row width-100">
                                                <label
                                                    class="col-3 control-label">{{trans('lang.delivery_charge_parcel')}}</label>
                                                <div class="col-7">
                                                    <input type="number" class="form-control delivery_charge_parcel"
                                                        name="delivery_charge_parcel" id="delivery_charge_parcel"
                                                        value="{{$setting->delivery_charge_parcel}}">
                                                </div>
                                            </div>
                                            <div class="form-group row width-100">
                                                <label
                                                    class="col-3 control-label">{{trans('lang.parcel_per_weight_charge')}}</label>
                                                <div class="col-7">
                                                    <input type="number" class="form-control parcel_per_weight_charge"
                                                        name="parcel_per_weight_charge" id="parcel_per_weight_charge"
                                                        value="{{$setting->parcel_per_weight_charge}}">
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>

                                    <fieldset>
                                        <legend>{{trans('lang.map_redirection')}}</legend>
                                        <div class="form-group row width-100">
                                            <label class="col-4 control-label">{{trans('lang.select_map_type')}}</label>
                                            <div class="col-7">
                                                <select name="map_type" id="map_type" class="form-control map_type">
                                                    <option value="">{{trans("lang.select_type")}}</option>
                                                    <option value="google" {{($setting->mapType=="google") ? "selected"
                                                        : ""}} >{{trans("lang.google_map")}}</option>
                                                    <option value="googleGo" {{ ($setting->mapType=="googleGo" ?
                                                        "selected" : "") }}>{{trans("lang.google_go_map")}}</option>
                                                    <option value="waze" {{ ($setting->mapType=="waze" ? "selected" :
                                                        "") }}>{{trans("lang.waze_map")}}</option>
                                                    <option value="mapswithme" {{ ($setting->mapType=="mapswithme" ?
                                                        "selected" : "") }}>{{trans("lang.mapswithme_map")}}</option>
                                                    <option value="yandexNavi" {{ ($setting->mapType=="yandexNavi" ?
                                                        "selected" : "") }}>{{trans("lang.vandexnavi_map")}}</option>
                                                    <option value="yandexMaps" {{ ($setting->mapType=="yandexMaps" ?
                                                        "selected" : "") }}>{{trans("lang.vandex_map")}}</option>
                                                    <option value="inappmap" {{ ($setting->mapType=="inappmap" ?
                                                        "selected" : "") }}>{{trans("lang.inapp_map")}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row width-100">
                                            <label
                                                class="col-4 control-label">{{trans('lang.driver_location_update')}}</label>
                                            <div class="col-7">
                                                <input name="driver_location_update" id="driver_location_update"
                                                    class="form-control" value="{{$setting->driverLocationUpdate}}">
                                            </div>
                                        </div>
                                    </fieldset>


                                    <fieldset>
                                        <legend>{{trans('lang.contact_us')}}</legend>

                                        <div class="form-group row width-50">
                                            <label
                                                class="col-3 control-label">{{trans('lang.contact_us_email')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control contact_us_email"
                                                    name="contact_us_email" id="contact_us_email"
                                                    value="{{$setting->contact_us_email}}">
                                            </div>
                                        </div>
                                        <div class="form-group row width-50">
                                            <label
                                                class="col-3 control-label">{{trans('lang.contact_us_phone')}}</label>
                                            <div class="col-7">
                                                <input type="number" class="form-control contact_us_phone"
                                                    name="contact_us_phone" id="contact_us_phone"
                                                    value="{{$setting->contact_us_phone}}">
                                            </div>
                                        </div>
                                        <div class="form-group row width-50">
                                            <label
                                                class="col-3 control-label">{{trans('lang.contact_us_address')}}</label>
                                            <div class="col-7">
                                                <textarea class="form-control contact_us_address" rows="3"
                                                    name="contact_us_address"
                                                    id="contact_us_address">{{$setting->contact_us_address}}</textarea>
                                            </div>
                                        </div>

                                    </fieldset>
                                    <fieldset>
                                        <legend>{{trans('lang.version')}}</legend>
                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.app_version')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control app_version" name="app_version"
                                                    id="app_version" value="{{$setting->app_version}}">
                                            </div>
                                        </div>
                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.web_version')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control web_version" name="web_version"
                                                    id="web_version" value="{{$setting->web_version}}">
                                            </div>
                                        </div>

                                    </fieldset>


                                    @endforeach
                                </div>
                            </div>


                            <div class="form-group col-12 text-center btm-btn">
                                <input type="hidden" class="form-control address_line1" name="id" id="id"
                                    value="{{$setting->id}}">

                                <button type="submit" class="btn btn-primary  create_user_btn"><i
                                        class="fa fa-save"></i> {{ trans('lang.save')}}
                                </button>
                                <a href="{!! route('settings') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{
                                    trans('lang.cancel')}}</a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('scripts')

    <script>

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#uploding_image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#uploding_image2').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        var ischeck = $('#parcel_active').is(':checked');
        console.log(ischeck);
        if (ischeck) {
            $('.parcel_setting').show();
        } else {
            $('.parcel_setting').hide();
        }
        $('#parcel_active').on('click',function() {
            var ischeck = $(this).is(':checked');
            if(ischeck) {
                $('.parcel_setting').show();
            }else{
                $('.parcel_setting').hide();
            }

        });
    </script>
    @endsection