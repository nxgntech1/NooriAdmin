@extends('layouts.app')

@section('content')
	<div class="page-wrapper">
         <div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card pb-4">
        <div class="card-body">
            <div class="payment-top-tab mt-3 mb-3">
            <ul class="nav nav-tabs card-header-tabs align-items-end">
               <li class="nav-item">
                    <a class="nav-link stripe_active_label" href="{!! url('settings/payment/stripe') !!}"><i class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_stripe')}}<span class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link cod_active_label" href="{!! url('settings/payment/cod') !!}"><i class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_cod_short')}}<span class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link apple_pay_active_label" href="{!! url('settings/payment/applepay') !!}"><i class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_apple_pay')}}<span class="badge ml-2"></span>
                    </a>
                </li>
                  <li class="nav-item">
                    <a class="nav-link razorpay_active_label" href="{!! url('settings/payment/razorpay') !!}"><i class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_razorpay')}}<span class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link paypal_active_label" href="{!! url('settings/payment/paypal') !!}"><i class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_paypal')}}<span class="badge ml-2"></span>
                    </a>
                </li>

                 <li class="nav-item">
                    <a class="nav-link paytm_active_label" href="{!! url('settings/payment/paytm') !!}"><i class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_paytm')}}<span class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link wallet_active_label" href="{!! url('settings/payment/wallet') !!}"><i class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_wallet')}}<span class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active PayFast_active_label" href="{!! url('settings/payment/payfast') !!}"><i class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_payfast')}}<span class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link PayStack_active_label" href="{!! url('settings/payment/paystack') !!}"><i class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_paystack')}}<span class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link FlutterWave_active_label" href="{!! url('settings/payment/flutterwave') !!}"><i class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_flutterwave')}}<span class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                        <a class="nav-link  mercadopago_active_label" href="{!! url('settings/payment/mercadopago') !!}"><i
                                    class="fa fa-envelope-o mr-2"></i>{{trans('lang.mercadopago')}}<span
                                    class="badge ml-2"></span></a>
                    </li>
            </ul>
        </div>

            <div class="card-body">
      	        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">Processing...</div>
                <div class="row restaurant_payout_create">
                    <div class="restaurant_payout_create-inner">
                            <fieldset>
                                <legend><i class="mr-3 fa fa-cc-stripe"></i>{{trans('lang.app_setting_payfast')}}</legend>
                                @foreach($payfast as $data)

                                <div class="form-check width-100">
                                <input type="hidden" class="id" id="id"  value="{{$data->id}}">

                                    <input type="checkbox" class="enable_payfast" id="enable_payfast" value="{{$data->isEnabled}}" @if($data->isEnabled == 'true') checked=checked @endif>
                                    <label class="col-3 control-label" for="enable_payfast">{{trans('lang.active')}}</label>
                                   
                                </div>
                                <div class="form-check width-100">
                                    <input type="checkbox" class="sandbox_enable" id="sandbox_enable"  value="{{$data->isSandboxEnabled}}" @if($data->isSandboxEnabled == 'true') checked=checked @endif>
                                    <label class="col-3 control-label" for="sandbox_enable">{{trans('lang.sandbox_enable_payfast')}}</label>
                                   
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-3 control-label">{{trans('lang.app_setting_payfast_key')}}</label>
                                    <div class="col-7">
                                        <input type="password" class="form-control stripe_key" value="{{ $data->merchant_key }}">
                                        <div class="form-text text-muted">
                                            {!! trans('lang.app_setting_payfast_key_help') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row width-100">
                                    <label class="col-3 control-label">{{trans('lang.app_setting_payfast_merchant_id')}}</label>
                                    <div class="col-7">
                                        <input type="password" class=" col-7 form-control payfast_secret" value="{{ $data->merchant_Id }}">
                                        <div class="form-text text-muted">
                                            {!! trans('lang.app_setting_payfast_merchant_id_help') !!}
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="form-group row width-100">
                                    <label class="col-3 control-label">{{trans('lang.app_setting_payfast_cancel_url')}}</label>
                                    <div class="col-7">
                                        <input type="password" class=" col-7 form-control payfast_cancel_url" value="{{ $data->cancel_url }}">
                                      
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-3 control-label">{{trans('lang.app_setting_payfast_notify_url')}}</label>
                                    <div class="col-7">
                                        <input type="password" class=" col-7 form-control payfast_notify_url" value="{{ $data->notify_url }}">
                                     
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-3 control-label">{{trans('lang.app_setting_payfast_return_url')}}</label>
                                    <div class="col-7">
                                        <input type="password" class=" col-7 form-control payfast_return_url" value="{{ $data->return_url }}">
                                      
                                    </div>
                                </div>
                                @endforeach

                            </fieldset>
                            @foreach($stripe as $stripe)
                            <input style="display:none" type="checkbox"  class="enable_stripe"  value="{{$stripe->isEnabled}}" id="enable_stripe" @if($stripe->isEnabled == "true") checked @endif>
                            @endforeach 
                            @foreach($cods as $cods)
                            <input style="display:none" type="checkbox"  class="enable_cod"  value="{{$cods->isEnabled}}" id="enable_cod" @if($cods->isEnabled == "true") checked @endif>
                            @endforeach 
                            @foreach($paypal as $paypal)
                            <input style="display:none" type="checkbox"  class="enable_paypal"  value="{{$paypal->isEnabled}}" id="enable_paypal" @if($paypal->isEnabled == "true") checked @endif>
                            @endforeach 
                            @foreach($razorpay as $razorpay)
                            <input style="display:none" type="checkbox"  class="enable_razor"  value="{{$razorpay->isEnabled}}" id="enable_razor" @if($razorpay->isEnabled == "true") checked @endif>
                            @endforeach 
                            @foreach($wallet as $wallet)
                            <input style="display:none" type="checkbox"  class="enable_wallet"  value="{{$wallet->isEnabled}}" id="enable_wallet" @if($wallet->isEnabled == "true") checked @endif>
                            @endforeach 
                            @foreach($paystack as $paystack)
                            <input style="display:none" type="checkbox"  class="enable_paystack"  value="{{$paystack->isEnabled}}" id="enable_paystack" @if($paystack->isEnabled == "true") checked @endif>
                            @endforeach 
                            @foreach($flutterwave as $flutterwave)
                            <input style="display:none" type="checkbox"  class="enable_flutterwave"  value="{{$flutterwave->isEnabled}}" id="enable_flutterwave" @if($flutterwave->isEnabled == "true") checked @endif>
                            @endforeach 
                            @foreach($paytm as $paytm)
                            <input style="display:none" type="checkbox"  class="enable_paytm"  value="{{$paytm->isEnabled}}" id="enable_paytm" @if($paytm->isEnabled == "true") checked @endif>
                            @endforeach 
                            @foreach($mercadopago as $mercadopago)
                            <input style="display:none" type="checkbox"  class="enable_mercadopago"  value="{{$mercadopago->isEnabled}}" id="enable_mercadopago" @if($mercadopago->isEnabled == "true") checked @endif>
                            @endforeach 
                            @foreach($applePay as $applePay)
                            <input style="display:none" type="checkbox"  class="enable_applePay"  value="{{$applePay->isEnabled}}" id="enable_applePay" @if($applePay->isEnabled == "true") checked @endif>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group col-12 text-center btm-btn">
                    <button type="button" class="btn btn-primary save_stripe_btn" ><i class="fa fa-save"></i> {{trans('lang.save')}}</button>
                    <a href="{{url('/dashboard')}}" class="btn btn-default"><i class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
                </div>
            </div>
        </div>
        </div>
        </div>
        </div>
        </div>
           
    


 @endsection

@section('scripts')

 <script>
        var isStripeEnabled = $(".enable_stripe").val();
     var isRazorpayenabled = $(".enable_razor").val();
     var isCodenabled = $(".enable_cod").val();
     var isPaytmenabled = $(".enable_paytm").val();
     var isPaypalenabled = $(".enable_paypal").val();
     var isPayfastenabled = $(".enable_payfast").val();
     var isPaystackenabled = $(".enable_paystack").val();
     var isflutterwaveenabled = $(".enable_flutterwave").is(":checked");
     var isWalletenabled = $(".enable_wallet").val();
     var ismercadoenabled = $(".enable_mercadopago").val();
     var isapplepayenabled = $(".enable_applePay").val();
    console.log(isWalletenabled)
    $(document).ready(function() {
            try {
                if (isStripeEnabled == 'true') {
                        //$(".enable_stripe").prop('checked', true);
                        jQuery(".stripe_active_label span").addClass('badge-success');
                        jQuery(".stripe_active_label span").text('Active');
                    }

                    if (isRazorpayenabled == 'true') {
                        //$(".enable_stripe").prop('checked', true);
                            jQuery(".razorpay_active_label span").addClass('badge-success');
                            jQuery(".razorpay_active_label span").text('Active');
                    }

                    if (isCodenabled == 'true') {
                        //$(".enable_stripe").prop('checked', true);
                        jQuery(".cod_active_label span").addClass('badge-success');
                        jQuery(".cod_active_label span").text('Active');
                    }

                    if (isPaytmenabled == 'true') {
                        //$(".enable_stripe").prop('checked', true);
                        jQuery(".paytm_active_label span").addClass('badge-success');
                        jQuery(".paytm_active_label span").text('Active');
                    }

                    if (isPaypalenabled == 'true') {
                        //$(".enable_stripe").prop('checked', true);
                        jQuery(".paypal_active_label span").addClass('badge-success');
                        jQuery(".paypal_active_label span").text('Active');
                    }

                    if (isPayfastenabled == 'true') {
                        //$(".enable_stripe").prop('checked', true);
                        jQuery(".PayFast_active_label span").addClass('badge-success');
                            jQuery(".PayFast_active_label span").text('Active');
                    }

                    if (isPaystackenabled == 'true') {
                        //$(".enable_stripe").prop('checked', true);
                        jQuery(".PayStack_active_label span").addClass('badge-success');
                            jQuery(".PayStack_active_label span").text('Active');
                    }

                    if (isflutterwaveenabled == true) {
                        //$(".enable_stripe").prop('checked', true);
                        jQuery(".FlutterWave_active_label span").addClass('badge-success');
                            jQuery(".FlutterWave_active_label span").text('Active');
                    }

                    if (isWalletenabled == 'true') {
                        //$(".enable_stripe").prop('checked', true);
                        jQuery(".wallet_active_label span").addClass('badge-success');
                        jQuery(".wallet_active_label span").text('Active');
                    }
                    if (ismercadoenabled == 'true') {
                        //$(".enable_stripe").prop('checked', true);
                        jQuery(".mercadopago_active_label span").addClass('badge-success');
                        jQuery(".mercadopago_active_label span").text('Active');
                    }

                    if (isapplepayenabled == 'true') {
                        //$(".enable_stripe").prop('checked', true);
                        jQuery(".apple_pay_active_label span").addClass('badge-success');
                        jQuery(".apple_pay_active_label span").text('Active');
                    }
                } catch (error) {

                }
            });




  $(".save_stripe_btn").click(function(){
    
        var sandbox_enable = $(".sandbox_enable").is(":checked");
        var enable_payfast = $(".enable_payfast").is(":checked");
        var stripe_key = $(".stripe_key").val();
        var payfast_secret = $(".payfast_secret").val();
        var payfast_notify_url = $(".payfast_notify_url").val();
        var payfast_cancel_url = $(".payfast_cancel_url").val();
        var payfast_return_url = $(".payfast_return_url").val();

        var id =$('.id').val();
        var url = "{{ route('payment.payfastUpdate',':id') }}";
        url = url.replace(':id', id);
        $.ajax({
                    url: url,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    //dataType: "json",
                    data:{
                        merchant_Id:payfast_secret,
                        merchant_key:stripe_key,
                        cancel_url:payfast_cancel_url,
                        notify_url:payfast_notify_url,
                        return_url:payfast_return_url,
                        isEnabled:enable_payfast,
                        isSandboxEnabled:sandbox_enable
                    },
                    
                    success: function(response) {
                       
                        window.location.reload();
                    },
                    error: function(response) {
                        console.log(response);
                    },
                });

})

</script>


@endsection