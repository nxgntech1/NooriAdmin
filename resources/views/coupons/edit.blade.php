@extends('layouts.app')

@section('content')
<div class="page-wrapper">
  <div class="row page-titles">
    <div class="col-md-5 align-self-center">
      <h3 class="text-themecolor">{{trans('lang.coupon_plural')}}</h3>
    </div>

    <div class="col-md-7 align-self-center">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
        <?php if (isset($_GET['eid']) && $_GET['eid'] != '') { ?>
          <li class="breadcrumb-item"><a href="{{route('restaurants.coupons',$_GET['eid'])}}">{{trans('lang.coupon_plural')}}</a></li>
        <?php } else { ?>
          <li class="breadcrumb-item"><a href="{!! route('coupons') !!}">{{trans('lang.coupon_plural')}}</a></li>
        <?php } ?>
        <li class="breadcrumb-item active">{{trans('lang.coupon_edit')}}</li>
      </ol>
    </div>

  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card pb-4">
          <div class="card-body">

            <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">{{trans('lang.processing')}}</div>

            <div class="error_top" style="display:none"></div>
            @if($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif
            <form method="post" action="{{ route('coupons.update',$discount->id) }}" enctype="multipart/form-data">
              @csrf
              @method("PUT")
              <div class="row restaurant_payout_create">

                <div class="restaurant_payout_create-inner">

                  <!-- <div class="col-md-6"> -->
                  <fieldset>
                    <legend>{{trans('lang.coupon_edit')}}</legend>

                    <div class="form-group row width-50">
                      <label class="col-3 control-label">{{trans('lang.coupon_code')}}</label>
                      <div class="col-7">
                        <input type="text" type="text" class="form-control coupon_code" name="code" value="{{$discount->code}}">
                        <div class="form-text text-muted">{{ trans("lang.coupon_code_help") }} </div>
                      </div>
                    </div>

                    <div class="form-group row width-50">
                      <label class="col-3 control-label">{{trans('lang.coupon_discount_type')}}</label>
                      <div class="col-7">
                        <select id="coupon_discount_type" class="form-control" name="type">
                          <option value="Percentage" {{ $discount->type == 'Percentage' ? 'selected' : '' }}>{{trans('lang.coupon_percent')}}</option>
                          <option value="Fix Price" {{ $discount->type == 'Fix Price' ? 'selected' : '' }}>{{trans('lang.coupon_fixed')}}</option>
                        </select>
                        <div class="form-text text-muted">{{ trans("lang.coupon_discount_type_help") }}</div>

                      </div>
                    </div>

                    <div class="form-group row width-50">
                      <label class="col-3 control-label">{{trans('lang.coupon_discount')}}</label>
                      <div class="col-7">

                        <input type="number" type="text" class="form-control coupon_discount" name="discount" value="{{$discount->discount}}">
                        <div class="form-text text-muted">{{ trans("lang.coupon_discount_help") }}</div>

                      </div>
                    </div>

                    <div class="form-group row width-50">
                      <label class="col-3 control-label">{{trans('lang.coupon_expires_at')}}</label>
                      <div class="col-7">
                        <!-- <div class="form-group"> -->
                        <div class='input-group date' id='datetimepicker1'>

                          <input type='date' class="form-control date_picker input-group-addon" name="expire_at" value="{{ date('Y-m-d', strtotime($discount->expire_at)) }}" />
                          <span class="">
                            <!-- <span class="glyphicon glyphicon-calendar fa fa-calendar"></span> -->
                          </span>
                        </div>
                        <div class="form-text text-muted">
                          {{ trans("lang.coupon_expires_at_help") }}
                        </div>
                        <!-- </div> -->
                      </div>
                    </div>
                    <div class="form-group row width-50">
                      <label class="col-3 control-label">{{trans('lang.coupon_type')}}</label>
                      <div class="col-7">
                        <select id="coupon_type" class="form-control" name="coupon_type">
                        <option value="Ride" {{ $discount->coupon_type == 'Ride' ? 'selected' : '' }}>{{trans('lang.ride')}}</option>
                          <option value="Parcel" {{ $discount->coupon_type == 'Parcel' ? 'selected' : '' }}>{{trans('lang.parcel')}}</option>

                        </select>
                        <div class="form-text text-muted">
                          {{ trans("lang.coupon_type_help") }}
                        </div>
                      </div>
                    </div>

                    <!-- <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.coupon_restaurant_id')}}</label>
              <div class="col-7">
                <select id="vendor_restaurant_select" class="form-control">
                  <option value="">{{trans('lang.select_restaurant')}}</option>
                </select>
                <div class="form-text text-muted">
                  {{ trans("lang.coupon_restaurant_id_help") }}
                </div> 
              </div>
            </div> -->

                    <div class="form-group row width-100">
                      <label class="col-3 control-label">{{trans('lang.coupon_description')}}</label>
                      <div class="col-7">
                        <textarea rows="12" class="form-control coupon_description" name="discription" id="coupon_description">{{$discount->discription}}</textarea>
                        <div class="form-text text-muted">{{ trans("lang.coupon_description_help") }}</div>
                      </div>
                    </div>

                    <div class="form-group row width-100">
                      <div class="form-check">
                        @if ($discount->statut === "yes")
                        <input type="checkbox" class="coupon_enabled" id="coupon_enabled" name="statut" checked="checked">

                        @else
                        <input type="checkbox" class="coupon_enabled" id="coupon_enabled" name="statut">
                        @endif
                        <label class="col-3 control-label" for="coupon_enabled">{{trans('lang.coupon_enabled')}}</label>

                      </div>
                    </div>
                  </fieldset>
                </div>

              </div>

          </div>
          <div class="form-group col-12 text-center btm-btn">
            <button type="submit" class="btn btn-primary save_coupon_btn"><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @endsection

  @section('scripts')

  @endsection