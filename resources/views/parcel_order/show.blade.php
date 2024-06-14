@extends('layouts.app')

@section('content')
    <div class="page-wrapper ridedetail-page">
        <div class="row page-titles non-printable">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor">{{ trans('lang.parcel_detail') }}</h3>

            </div>

            <div class="col-md-7 align-self-center">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item">
                        <a href="{!! url('/dashboard') !!}">{{ trans('lang.home') }}</a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{!! route('parcel.all') !!}">{{ trans('lang.all_parcel') }}</a>
                    </li>

                    <li class="breadcrumb-item active">
                        {{ trans('lang.parcel_detail') }}
                    </li>

                </ol>

            </div>
        </div>

        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-body">

                            {{--  <div class="row">

						<div class="col-12">

							<div class="box">
								<div class="box-header bb-2 border-primary">
									<h3 class="box-title">{{trans('lang.map_view')}}</h3>
								</div>
								<div class="box-body">
									<div id="map" style="height:300px">
									</div>
								</div>
							</div>
						</div>
					</div> --}}
                            <div id="data-table_processing" class="dataTables_processing panel panel-default"
                                 style="display: none;">{{ trans('lang.processing') }}</div>
                            <div class="col-md-12">
                                <div class="print-top non-printable mt-3">
                                    <div class="text-right print-btn non-printable">
                                        <button type="button" class="fa fa-print non-printable"
                                                onclick="printDiv('printableArea')"></button>
                                    </div>
                                </div>

                                <hr class="non-printable">
                            </div>

                            <form method="post" action="{{ route('parcel.update', $ride->id) }}"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="order_detail printableArea" id="order_detail">
                                    <div class="order_detail-top mb-3 ">
                                        <div class="row">
                                            <div class="order_edit-genrl col-md-6">
                                                <div class="card">
                                                    <div class="card-header bg-white">
                                                        <h3>{{ trans('lang.general_details') }}</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="order_detail-top-box">
                                                            <div class="form-group row widt-100 gendetail-col">
                                                                <label class="col-12 control-label"><strong>{{ trans('lang.parcel_id') }}
                                                                        : </strong><span
                                                                            id="ride_id">{{ $ride->id }}</span></label>
                                                            </div>


                                                            <div class="form-group row widt-100 gendetail-col">
                                                                <label class="col-12 control-label"><strong>{{ trans('lang.date_created') }}
                                                                        : </strong><span
                                                                            id="createdAt">{{ date('d F Y h:i A', strtotime($ride->created_at)) }}</span></label>
                                                            </div>

                                                            <div
                                                                    class="form-group row widt-100 gendetail-col payment_status">
                                                                <label class="col-12 control-label"><strong>{{ trans('lang.payment_status') }}
                                                                        : </strong>
                                                                    <span id="payment_status">

                                                                        @if ($ride->payment_status == 'yes')
                                                                            <span
                                                                                    class="badge badge-success py-2 px-3">Paid</span>
                                                                        @else
                                                                            <span class="badge badge-warning py-2 px-3">Not
                                                                                paid</span>
                                                                        @endif

                                                                    </span>
                                                                </label>
                                                            </div>

                                                            <div
                                                                    class="form-group row widt-100 gendetail-col payment_method">
                                                                <label class="col-12 control-label"><strong>{{ trans('lang.payment_methods') }}
                                                                        : </strong>
                                                                    <span id="payment_method">

                                                                        @if ($ride->image)
                                                                            <img class="rounded" style="width:70px"
                                                                                 src="{{ asset('/assets/images/payment_method/' . $ride->image) }}"
                                                                                 alt="image">
                                                                        @endif
                                                                    </span>
                                                                </label>

                                                            </div>

                                                            <div
                                                                    class="form-group row widt-100 gendetail-col payment_status">
                                                                <label class="col-12 control-label"><strong>{{ trans('lang.parcel_distance') }}
                                                                        : </strong><span
                                                                            id="trip_objective">{{ $ride->distance . ' ' . $ride->distance_unit }}</span></label>

                                                            </div>

                                                            <div class="form-group row width-100 ">
                                                                <label
                                                                        class="col-3 control-label">{{ trans('lang.parcel_status') }}
                                                                    :</label>
                                                                <div class="col-7">

                                                                    @php
                                                                        $status = ['new' => 'new', 'confirmed' => 'confirmed', 'onride' => 'onride', 'completed' => 'completed', 'canceled' => 'canceled', 'rejected' => 'rejected'];

                                                                    @endphp

                                                                    <select name="order_status" class="form-control">
                                                                        @foreach ($status as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                    {{ $key == $ride->status ? 'selected' : '' }}>
                                                                                {{ $value }} </option>
                                                                        @endforeach

                                                                    </select>

                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="order_edit-genrl col-md-6">
                                                <div class="card">
                                                    <div class="card-header bg-white">
                                                        <h3>{{ trans('lang.sender_details') }}</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="address order_detail-top-box user-details">

                                                            <p>
                                                                <strong>{{ trans('lang.name') }}: </strong>
                                                                <span class="billing_name d-flex">

                                                                        <span class="drove-det ml-2"><span
                                                                                    class="drv-name">
                                                                                    {{ $ride->sender_name }}
                                                                            </span>
                                                                        </span>

                                                                </span>

                                                            </p>

                                                            <p><strong>{{ trans('lang.phone') }}:</strong>
                                                                <span id="billing_phone">
                                                                        {{ $ride->sender_phone }}
                                                                </span>
                                                            </p>

                                                            <p><strong>{{ trans('lang.address') }}:</strong>
                                                                <span id="billing_phone">
                                                                        {{ $ride->source }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header bg-white">
                                                        <h3>{{ trans('lang.receiver_details') }}</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="address order_detail-top-box user-details">

                                                            <p>
                                                                <strong>{{ trans('lang.name') }}: </strong>
                                                                <span class="billing_name d-flex">

                                                                        <span class="drove-det ml-2"><span
                                                                                    class="drv-name">
                                                                                    {{ $ride->receiver_name }}
                                                                            </span>
                                                                        </span>

                                                                </span>

                                                            </p>

                                                            <p><strong>{{ trans('lang.phone') }}:</strong>
                                                                <span id="billing_phone">
                                                                        {{ $ride->receiver_phone }}
                                                                </span>
                                                            </p>

                                                            <p><strong>{{ trans('lang.address') }}:</strong>
                                                                <span id="billing_phone">
                                                                        {{ $ride->destination }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="order-deta-btm mt-4">
                                        <div class="row">
                                           {{-- @php
                                                $class = "col-md-12";

                                            if(isset($ride->id_conducteur) && $ride->id_conducteur !== null)
                                               $class = "col-md-6";

                                            @endphp--}}
                                            <div class="col-md-6 order-deta-btm-left">
                                                <div class="card non-printable">
                                                    <div class="card-body">
                                                        <div class="row">

                                                            <div class="col-12">

                                                                <div class="box">
                                                                    <div class="box-header bb-2 border-primary">
                                                                        <h3 class="box-title">{{ trans('lang.map_view') }}
                                                                        </h3>
                                                                    </div>
                                                                    <div class="box-body">
                                                                        <div id="map" style="height:300px">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="col-md-6 order-deta-btm-right">
                                                    <div class="resturant-detail">
                                                        <div class="card">
                                                            <div class="card-header bg-white">
                                                                <h3 class="box-title">{{ trans('lang.parcel_details') }}</h3>
                                                            </div>

                                                            <div class="card-body">
                                                               
                                                            <div class="address order_detail-top-box user-details">
                                                                <p><strong>{{ trans('lang.parcel_type') }}
                                                                        :</strong>
                                                                    <span id="vendor_email">{{(!empty($ride->title)) ? $ride->title : '' }}</span>
                                                                </p>                                                               
                                                                <p><strong>{{ trans('lang.parcel_weight') }}
                                                                        :</strong>
                                                                    <span id="vendor_email">{{ $ride->parcel_weight }} Kg</span>
                                                                </p>
                                                                 <p><strong>{{ trans('lang.parcel_dimension') }}
                                                                        :</strong>
                                                                    <span id="vendor_email">{{(!empty($ride->parcel_dimension)) ? $ride->parcel_dimension." ft" : '-' }} </span>
                                                                </p>   
                                                                <p><strong>{{ trans('lang.parcel_note') }}
                                                                        :</strong>
                                                                    <span id="vendor_email">{{(!empty($ride->note)) ? $ride->note : '-' }} </span>
                                                                </p>
                                                                <p><strong>{{ trans('lang.parcel_date') }}
                                                                        :</strong>
                                                                    <span id="vendor_phone">{{ date('d F Y h:i A', strtotime($ride->parcel_date.$ride->parcel_time)) }}</span>
                                                                </p>
                                                                <p><strong>{{ trans('lang.receive_date') }}
                                                                        :</strong>
                                                                    <span id="vendor_phone">{{ date('d F Y h:i A', strtotime($ride->receive_date.$ride->receive_time)) }}</span>
                                                                </p>
                                                                
                                                                <p><strong>{{ trans('lang.parcel_image') }}:</strong>
                                                                @if(!empty($parcel_image))
                                                                             <span id="billing_name"
                                                                                  class="d-flex"><span
                                                                                        class="user-img"><img
                                                                                            class="rounded"
                                                                                            style="width:50px"
                                                                                            src="{{$parcel_image[0]}}"
                                                                                            alt="Image"></span>
                                                                            @else
                                                                                    <span id="billing_name"
                                                                                          class="d-flex"><span
                                                                                                class="user-img"><img
                                                                                                    class="rounded"
                                                                                                    style="width:50px"
                                                                                                    src="{{ asset('assets/images/placeholder_image.jpg') }}"
                                                                                                    alt="Image"></span>
                                                                              @endif                                      
                                                                </p>
                                                                
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                        </div>
                                    </div>

                                    <div class="order-deta-btm mt-4">
                                        <div class="row">
                                            <div class="col-md-6 order-deta-btm-left">
                                                <div class="card">
                                                    <div class="box card-body p-0">
                                                        <div class="box-header bb-2 card-header bg-white">
                                                            <h3 class="box-title">{{ trans('lang.location_details') }}
                                                            </h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="live-tracking-list">
                                                                <div class="live-tracking-box track-from">


                                                                    <div class="live-tracking-inner">
                                                                        <div class="location-ride">

                                                                            <div class="from-ride">
                                                                                {{ $ride->source }}</div>

                                                                            <div class="to-ride">
                                                                                {{ $ride->destination }}</div>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="order_addre-edit ">
                                                        <div class="card-header bg-white">
                                                            <h3>{{ trans('lang.price_details') }}</h3>
                                                        </div>
                                                        <div class="card-body price_detail">
                                                            <div class="order-deta-btm-right">
                                                                <div class="order-totals-items pt-0">
                                                                    <div class="row">
                                                                        <div class="col-md-12 ml-auto">
                                                                            <div class="table-responsive bk-summary-table">
                                                                                <table class="order-totals">

                                                                                    <tbody id="order_products_total">
                                                                                    @if (!empty($ride->transaction_id))
                                                                                        <tr
                                                                                                class="transaction_id_48fc3f15-66f1-45a7-b4b8-123563426fe4">
                                                                                            <td class="label">
                                                                                                <strong>{{ trans('lang.transaction_id') }}</strong>
                                                                                            </td>
                                                                                            <td>
                                                                                                <strong>{{ $ride->transaction_id }}</strong>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endif
                                                                                    <tr>
                                                                                        <td class="seprater"
                                                                                            colspan="2">
                                                                                            <hr>
                                                                                            <span>{{ trans('lang.sub_total') }}</span>
                                                                                        </td>
                                                                                    </tr>

                                                                                    <tr class="final-rate">
                                                                                        <td class="label">
                                                                                            {{ trans('lang.sub_total') }}
                                                                                        </td>
                                                                                        <td>
                                                                                            @if ($currency->symbol_at_right == 'true')
                                                                                                {{ number_format(floatval($ride->amount), $currency->decimal_digit) . '' . $currency->symbole }}
                                                                                            @else
                                                                                                {{ $currency->symbole . '' . number_format(floatval($ride->amount), $currency->decimal_digit) }}
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                    @if ($ride->discount > 0)
                                                                                        <tr>
                                                                                            <td class="seprater"
                                                                                                colspan="2">
                                                                                                <hr>
                                                                                                <span>{{ trans('lang.discount') }}</span>
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="label">
                                                                                                {{ trans('lang.discount') }}
                                                                                            </td>
                                                                                            <td>
                                                                                                    <span
                                                                                                            style="color:red">
                                                                                                        @if ($currency->symbol_at_right == 'true')
                                                                                                            (-
                                                                                                            {{ number_format(floatval($ride->discount), $currency->decimal_digit) . '' . $currency->symbole }}
                                                                                                            )
                                                                                                        @else
                                                                                                            (-{{ $currency->symbole . '' . number_format(floatval($ride->discount), $currency->decimal_digit) }})
                                                                                                        @endif
                                                                                                    </span>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endif
                                                                                    @if (!empty($taxHtml))
                                                                                        <tr>
                                                                                            <td class="seprater"
                                                                                                colspan="2">
                                                                                                <hr>
                                                                                                <span>{{ trans('lang.tax_calculation') }}</span>
                                                                                            </td>
                                                                                        </tr>

                                                                                        {!! $taxHtml !!}
                                                                                    @endif
                                                                                    @if ($ride->tip > 0)
                                                                                        <tr>
                                                                                            <td class="seprater"
                                                                                                colspan="2">
                                                                                                <hr>
                                                                                                <span>{{ trans('lang.tip') }}</span>
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="label">
                                                                                                {{ trans('lang.tip_amount') }}
                                                                                            </td>
                                                                                            <td>
                                                                                                @if ($currency->symbol_at_right == 'true')
                                                                                                    {{ number_format(floatval($ride->tip), $currency->decimal_digit) . '' . $currency->symbole }}
                                                                                                @else
                                                                                                    {{ $currency->symbole . '' . number_format(floatval($ride->tip), $currency->decimal_digit) }}
                                                                                                @endif
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endif
                                                                                    <tr>
                                                                                        <td class="seprater"
                                                                                            colspan="2">
                                                                                            <hr>
                                                                                        </td>
                                                                                    </tr>

                                                                                    <tr class="grand-total">
                                                                                        <td class="label">
                                                                                            {{ trans('lang.total_amount') }}
                                                                                        </td>
                                                                                        <td class="total_price_val">
                                                                                            @if ($currency->symbol_at_right == 'true')
                                                                                                {{ number_format(floatval($totalAmount), $currency->decimal_digit) . '' . $currency->symbole }}
                                                                                            @else
                                                                                                {{ $currency->symbole . '' . number_format(floatval($totalAmount), $currency->decimal_digit) }}
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                    @if ($ride->admin_commission != '')
                                                                                        <tr>
                                                                                            <td class="label">
                                                                                                <small>
                                                                                                    {{ trans('lang.admin_commission') }}
                                                                                                </small>
                                                                                            </td>
                                                                                            <td
                                                                                                    class="adminCommission_val">
                                                                                                <small>
                                                                                                        <span
                                                                                                                style="color:red">
                                                                                                            @if ($currency->symbol_at_right == 'true')
                                                                                                                ({{ number_format(floatval($ride->admin_commission), $currency->decimal_digit) . '' . $currency->symbole }}
                                                                                                                )
                                                                                                            @else
                                                                                                                (
                                                                                                                {{ $currency->symbole . '' . number_format(floatval($ride->admin_commission), $currency->decimal_digit) }})
                                                                                                            @endif
                                                                                                        </span>
                                                                                                </small>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endif

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 order-deta-btm-right">
                                                <div class="card">
                                                    <div class="card-header bg-white">
                                                        <h3 class="box-title">{{ trans('lang.ride_customer_review') }}</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <p>
                                                            @if ($customer_review->isEmpty())
                                                                {{ trans('lang.no_review_found') }}
                                                            @else
                                                            
                                                            @foreach($customer_review as $review)
                                                               <div class="d-inline-block d-flex">                                                     
                                                                {{$review->comment }} 
                                                                <div class="ml-auto">
                                                                
                                                                <ul class="rating" data-rating="{{$review->niveau}}">
                                                                    <li class="rating__item"></li>
                                                                    <li class="rating__item"></li>
                                                                    <li class="rating__item"></li>
                                                                    <li class="rating__item"></li>
                                                                    <li class="rating__item"></li>
                                                                </ul>
                                                                
                                                             </div>
                                                            </div> 
                                                            @endforeach
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="card">
                                                    <div class="card-header bg-white">
                                                        <h3>{{ trans('lang.complaints') }}</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        @if (count($complaints))
                                                            <div class="address order_detail-top-box user-details">

                                                                <div class="card-body price_detail">
                                                                    <div class="order-deta-btm-right">
                                                                        <div class="order-totals-items pt-0">
                                                                            <div class="row">
                                                                                <div class="col-md-12 ml-auto">
                                                                                    <div class="table-responsive bk-summary-table">

                                                                                        <table class="order-totals ">

                                                                                            <tbody id="order_products_total">


                                                                                            @foreach($complaints as $complaint)

                                                                                                <tr>
                                                                                                    <td class="seprater"
                                                                                                        colspan="5">
                                                                                                        <hr>
                                                                                                        <span>{{ trans('lang.by') }} {{ $complaint->user_type }}</span>
                                                                                                    </td>
                                                                                                </tr>

                                                                                                <tr>
                                                                                                    <td>

                                                                                                        <strong>{{ trans('lang.title') }}
                                                                                                            
                                                                                                        </strong>

                                                                                                    </td>
                                                                                                    <td>

                                                                                        <span id="billing_phone">
                                                                        {{ $complaint->title }}
                                                                </span>

                                                                                                    </td>
                                                                                                    <td></td>
                                                                                                </tr>

                                                                                                <tr>
                                                                                                    <td>

                                                                                                        <strong>{{ trans('lang.message') }}
                                                                                                            </strong>

                                                                                                    </td>
                                                                                                    <td>

                                                                                        <span id="billing_phone">
                                                                        {{ $complaint->description }}
                                                                </span>

                                                                                                    </td>
                                                                                                    <td></td>

                                                                                                </tr>

                                                                                            @endforeach


                                                                                            </tbody>
                                                                                        </table>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else

                                                            <p>  {{ trans('lang.no_complaint_found') }}  </p>
                                                        @endif

                                                    </div>
                                                </div>
                                            @if(isset($ride->id_conducteur) && $ride->id_conducteur !== null)
                                                <div class="resturant-detail">
      
                                            <div class="card">
                                                <div class="card-header bg-white">
                                                    <h3 class="box-title">{{ trans('lang.driver_detail') }}</h3>
                                                </div>
                                            
                                                <div class="card-body">
                                                    <a href="#" class="row redirecttopage" id="resturant-view">
                                                        <div class="col-4">
                                                            @if (file_exists(public_path('assets/images/drivers' . '/' . $ride->driver_photo)) &&
                                                            !empty($ride->driver_photo))
                                                            <span id="billing_name" class="d-flex"><span class="user-img"><img class="rounded" style="width:50px"
                                                                        src="{{ asset('assets/images/drivers/' . $ride->driver_photo) }}" alt="Image"></span>
                                                                @else
                                                                <span id="billing_name" class="d-flex"><span class="user-img"><img class="rounded"
                                                                            style="width:50px" src="{{ asset('assets/images/placeholder_image.jpg') }}"
                                                                            alt="Image"></span>
                                                                    @endif
                                                        </div>
                                                        <div class="col-8">
                                                            <h4 class="vendor-title">{{ $ride->driverPrenom }}
                                                                {{ $ride->driverNom }}</h4>
                                                            <span class="badge badge-warning text-white ml-auto"><i class="fa fa-star"></i>{{ $driverRating
                                                                }}</span>
                                                        </div>
                                                    </a>
                                            
                                                    <h5 class="contact-info">{{ trans('lang.contact_info') }}
                                                        :</h5>
                                                    <p><strong>{{ trans('lang.email') }}
                                                            :</strong>
                                                        <span id="vendor_email">{{ $ride->driver_email }}</span>
                                                    </p>
                                                    <p><strong>{{ trans('lang.phone') }}
                                                            :</strong>
                                                        <span id="vendor_phone">{{ $ride->driver_phone }}</span>
                                                    </p>
                                            
                                            
                                                </div>
                                            </div>
                                                            </div>
                                            @endif

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-12 text-center btm-btn non-printable">
                                    <button type="submit" class="btn btn-primary save_order_btn"><i
                                                class="fa fa-save"></i>
                                        {{ trans('lang.save') }}</button>
                                    <a href="javascript:history.go(-1)" class="btn btn-default"><i
                                                class="fa fa-undo"></i>{{ trans('lang.cancel') }}</a>

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
    <script type="text/javascript">
        var map;
        var marker;


        var myLatlng = new google.maps.LatLng({!! $ride->lat_destination !!}, {!! $ride->lng_destination !!});
        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow();

        var mapOptions = {
            zoom: 10,
            center: myLatlng,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map"), mapOptions);

        marker = new google.maps.Marker({
            map: map,
            position: myLatlng,
            draggable: true
        });

        google.maps.event.addListener(marker, 'click', function () {
            infowindow.setContent('{!! $ride->destination !!}');
            infowindow.open(map, marker);
        });

        //Set direction route
        let directionsService = new google.maps.DirectionsService();
        let directionsRenderer = new google.maps.DirectionsRenderer();

        directionsRenderer.setMap(map);

        const origin = {
            lat: {!! $ride->lat_source !!},
            lng: {!! $ride->lng_source !!}
        };
        const destination = {
            lat: {!! $ride->lat_destination !!},
            lng: {!! $ride->lng_destination !!}
        };

        const route = {
            origin: origin,
            destination: destination,
            travelMode: 'DRIVING'
        }

        directionsService.route(route, function (response, status) {
            if (status !== 'OK') {
                window.alert('Directions request failed due to ' + status);
                return;
            } else {
                directionsRenderer.setDirections(response);
                var directionsData = response.routes[0].legs[0];
            }
        });

        function printDiv(divName) {
            var css = '@page { size: portrait; }',
                head = document.head || document.getElementsByTagName('head')[0],
                style = document.createElement('style');
            style.type = 'text/css';
            style.media = 'print';

            if (style.styleSheet) {
                style.styleSheet.cssText = css;
            } else {
                style.appendChild(document.createTextNode(css));
            }

            head.appendChild(style);

            var printContents = document.getElementsByClassName(divName).html;
            window.print();

        }
    </script>
@endsection
