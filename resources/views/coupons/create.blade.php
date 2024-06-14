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
            
          <li class="breadcrumb-item"><a href= "{!! route('coupons') !!}" >{{trans('lang.coupon_plural')}}</a></li>
      
				<li class="breadcrumb-item active">{{trans('lang.coupon_create')}}</li>
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
                <li class="error">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif    
        <form method="post" action="{{ route('coupons.store') }}" enctype="multipart/form-data">
          @csrf

        <div class="row restaurant_payout_create">
      
          <div class="restaurant_payout_create-inner">

          <!-- <div class="col-md-6"> -->
          <fieldset>
            <legend>{{trans('lang.coupon_create')}}</legend>     

            <div class="form-group row width-50">
                <label class="col-3 control-label">{{trans('lang.coupon_code')}}</label>
                <div class="col-7">
                  <input type="text" type="text" class="form-control coupon_code" name="code" value="{{ Request::old('code')}}">
                  <div class="form-text text-muted">{{ trans("lang.coupon_code_help") }} </div>  
                </div>
            </div>

            <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.coupon_discount_type')}}</label>
              <div class="col-7">
                <select id="coupon_discount_type" class="form-control" name="type">
                @if (Request::old('type') == 'Percentage')
                  <option value="Percentage" selected>{{trans('lang.coupon_percent')}}</option>
              @else
              <option value="Percentage">{{trans('lang.coupon_percent')}}</option>
              @endif
              @if (Request::old('type') == 'Fix Price')
                  <option value="Fix Price" selected>{{trans('lang.coupon_fixed')}}</option>
              @else
              <option value="Fix Price">{{trans('lang.coupon_fixed')}}</option>
              @endif
                </select>
                <div class="form-text text-muted">{{ trans("lang.coupon_discount_type_help") }}</div>

              </div>
            </div>

            <div class="form-group row width-50">
              <label class="col-3 control-label">{{trans('lang.coupon_discount')}}</label>
              <div class="col-7">
                <input type="number" type="text" class="form-control coupon_discount" name="discount" value="{{ Request::old('discount')}}">
                <div class="form-text text-muted">{{ trans("lang.coupon_discount_help") }}</div>  
              </div>
            </div>

            <div class="form-group row width-50">
                <label class="col-3 control-label">{{trans('lang.coupon_expires_at')}}</label>
                <div class="col-7">
                  <!-- <div class="form-group"> -->
                    <div class='input-group date' id='datetimepicker1'>
                      <input type='date' class="form-control date_picker input-group-addon" name="expire_at"  value="{{ Request::old('expire_at')}}"/>
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
                @if (Request::old('coupon_type') == 'ride')
                          <option value="Ride" selected>{{trans('lang.ride')}}</option>
                          @else
                          <option value="Ride">{{trans('lang.ride')}}</option>
                          @endif
                          @if (Request::old('coupon_type') == 'parcel')
                          <option value="Parcel" selected>{{trans('lang.parcel')}}</option>
                          @else
                          <option value="Parcel">{{trans('lang.parcel')}}</option>
                          @endif
                </select>
                <div class="form-text text-muted">
                  {{ trans("lang.coupon_type_help") }}
                </div> 
              </div>
            </div>

            <div class="form-group row width-100">
              <label class="col-3 control-label">{{trans('lang.coupon_description')}}</label>
              <div class="col-7">
                <textarea rows="12" class="form-control coupon_description" id="coupon_description" name="discription" value="{{ Request::old('description')}}">{{ Request::old('description')}}</textarea>
                <div class="form-text text-muted">{{ trans("lang.coupon_description_help") }}</div>
              </div>
            </div>

            <!-- <div class="form-group row width-100">
              <label class="col-3 control-label">{{trans('lang.category_image')}}</label>
              <div class="col-7">
                <input type="file" onChange="handleFileSelect(event)">
                <div class="placeholder_img_thumb coupon_image"></div>
                <div id="uploding_image"></div>
              </div>
            </div> -->

            <div class="form-group row width-100">
              <div class="form-check">                    
                <input type="checkbox" class="coupon_enabled" id="coupon_enabled" name="statut">
                <label class="col-3 control-label" for="coupon_enabled">{{trans('lang.coupon_enabled')}}</label>

              </div>
            </div>

          <!-- </div>

          <div class="col-md-6"> -->

             <!-- <div class="form-group row">
                    <label class="col-3 control-label">{{trans('lang.coupon_food_id')}}</label>
                    <div class="col-7">
                    <select id="coupon_food" class=" select2 form-control" multiple>
                      <option value="">{{trans('lang.select_food')}}</option>
                    </select>
                    <div class="form-text text-muted">
                      {{ trans("lang.coupon_food_id_help") }}
                    </div>  
                  </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 control-label">{{trans('lang.coupon_restaurant_id')}}</label>
                    <div class="col-7">
                    <select id="coupon_restaurant" class=" select2 form-control" multiple>
                      <option vale="percent">{{trans('lang.select_restaurant')}}</option>
                    </select>
                    <div class="form-text text-muted">
                      {{ trans("lang.coupon_restaurant_id_help") }}
                    </div> 
                  </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 control-label">{{trans('lang.coupon_category_id')}}</label>
                    <div class="col-7">
                    <select id="coupon_category" class="form-control select2" multiple>
                      <option vale="percent">{{trans('lang.select_category')}}</option>
                    </select>
                    <div class="form-text text-muted">
                      {{ trans("lang.coupon_category_id_help") }}
                    </div>  
                  </div>
                </div> -->
             
                
        <!-- </div> -->
          </fieldset>
         </div>

      </div>

    </div>
    
      <div class="form-group col-12 text-center btm-btn">
        <button type="submit" class="btn btn-primary save_coupon_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
       
        <!-- <a href="{!! route('coupons') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a> -->
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
  $('.save_coupon_btn').on('click',function(){

    var code = $('.coupon_code').val();
    if(code != '')
    {
      $('.error').html('The Code field is required!');
    }

  })
</script>
@endsection