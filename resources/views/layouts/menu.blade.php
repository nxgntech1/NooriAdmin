<nav class="sidebar-nav">

    <ul id="sidebarnav">

        <li >
        	<a class="waves-effect waves-dark" href="{!! url('/dashboard') !!}">
        	 	<i class="mdi mdi-home"></i>
        	 	<span class="hide-menu">Dashboard</span>
        	</a>
        </li>

        <li>
        	<a class="waves-effect waves-dark" href="{!! url('users') !!}" >
        		<i class="mdi mdi-account-multiple"></i>
                <span class="hide-menu">{{trans('lang.user_plural')}}</span>
        	</a>
        </li>

        <li>
        	<a class="waves-effect waves-dark" href="{!! url('webuser') !!}" >
        		<i class="mdi mdi-account-key"></i>
                <span class="hide-menu">{{trans('lang.web_users')}}</span>
        	</a>
        </li>
        
        <!-- <li>
            <a class="waves-effect waves-dark" href="{!! url('dispatcher-users') !!}" >
                <i class="mdi mdi-account-box"></i>
                <span class="hide-menu">{{trans('lang.dispatcher_user')}}</span>
            </a>
        </li> -->

        <li>
        <a class="has-arrow waves-effect waves-dark" class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                 <i class="mdi mdi-account-card-details"></i>
                <span class="hide-menu">{{trans('lang.driver_plural')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
            	<li><a href="{!! url('drivers') !!}">{{trans('lang.all_drivers')}}</a></li>
                <li><a href="{!! url('drivers/approved') !!}">{{trans('lang.approved_drivers')}}</a></li>
                <li><a href="{!! url('drivers/pending') !!}">{{trans('lang.pending_drivers')}}</a></li>
                <li><a href="{!! url('cash_collection') !!}">{{trans('lang.pending_cash_collection')}}</a></li>
                <li><a href="{!! url('cash_collection/collected') !!}">{{trans('lang.cash_collected')}}</a></li>
            </ul>
        </li>

        <li>
          <a class="" href="{!! url('coupons') !!}">
                <i class="mdi mdi-sale"></i>
                <span class="hide-menu">{{trans('lang.coupon_plural')}}</span>
            </a>
        </li>

        <li>
          <a class="" href="{!! url('rides/new') !!}">
                <i class="mdi mdi-clock-alert"></i>
                <span class="hide-menu">{{trans('lang.pending_rides')}}</span>
            </a>
        </li>

	    <li>
          <a class="" href="{!! url('rides/all') !!}">
                <i class="mdi mdi-map-marker-multiple"></i>
                <span class="hide-menu">{{trans('lang.all_rides')}}</span>
            </a>
        </li>

        <li>
          <a class="" href="{!! url('map') !!}">
                <i class="mdi mdi-home-map-marker"></i>
                <span class="hide-menu">{{trans('lang.map_view')}}</span>
            </a>
        </li>

        <!-- <li>
            <a class="waves-effect waves-dark" href="{!! url('zone') !!}" aria-expanded="false">

                <i class="mdi mdi-map-marker-circle"></i>

                <span class="hide-menu">{{trans('lang.zone')}}</span>

            </a>
        </li> -->
        
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.other_services')}}</span></li>

        <li>
            <a class="waves-effect waves-dark" href="{!! url('complaints') !!}">
                <i class="fa fa-list-alt"></i>
                <span class="hide-menu">{{trans('lang.complaints')}}</span>
            </a>
        </li>

        <li>
            <a class="waves-effect waves-dark" href="{!! url('sos') !!}">
                <i class="fa fa-heartbeat"></i>
                <span class="hide-menu">{{trans('lang.sos')}}</span>
            </a>
        </li>

        <li>
            <a class="waves-effect waves-dark" href="{!! url('notification') !!}">
                 <i class="fa fa-table"></i>
                <span class="hide-menu">{{trans('lang.notification')}}</span>
            </a>
        </li>
        <!-- <li>
            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-package"></i>
                <span class="hide-menu">{{trans('lang.parcel')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                <li><a class="" href="{!! url('parcel/map') !!}">{{trans('lang.map_view')}}</a></li>
                <li><a href="{!! url('parcel-category') !!}">{{trans('lang.parcelCategory')}}</a></li>
                <li><a href="{!! url('parcel/all') !!}">{{trans('lang.parcelOrders')}}</a></li>
            </ul>
        </li> -->

        <!-- <li>
         	<a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-car"></i>
                <span class="hide-menu">{{trans('lang.vehicle_rental')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{!! url('vehicle-rental-type/index') !!}">{{trans('lang.rental_vehicle_type')}}</a></li>
                <li><a href="{!! url('vehicle/vehicle-rent') !!}">{{trans('lang.rented_vehicle_booking')}}</a></li>
            </ul>
        </li> -->
        <li>
         	<a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-car-connected"></i>
                <span class="hide-menu">{{trans('lang.vehicle_settings')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{!! url('vehicle/index') !!}">{{trans('lang.vehicle_type')}}</a></li>
                <li><a href="{!! url('brands') !!}">{{trans('lang.brand')}}</a></li>
                <li><a href="{!! url('car_model') !!}">{{trans('lang.model')}}</a></li>
                <li><a href="{!! url('bookingtypes') !!}">{{trans('lang.booking_types')}}</a></li>
                <li><a href="{!! url('cmpricing') !!}">{{trans('lang.pricing')}}</a></li>
                <li><a href="{!! url('addon') !!}">{{trans('lang.addon_pricing')}}</a></li>
                <li><a href="{!! url('vehicles') !!}">{{trans('lang.vehicles')}}</a></li>
                
            </ul>
        </li>

         <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.other_settings')}}</span></li>

        <!-- <li>
        	<a class="" href="{!! url('cms') !!}">
                <i class="mdi mdi-book-open-page-variant"></i>
                <span class="hide-menu">{{trans('lang.cms_plural')}}</span>
            </a>
        </li> -->

        <!-- <li>
        	<a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-bank"></i>
                <span class="hide-menu">{{trans('lang.payment_plural')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{!! url('driversPayouts') !!}">{{trans('lang.drivers_payout')}}</a></li>
                <li><a href="{!! url('payoutRequest') !!}">{{trans('lang.payout_request')}}</a></li>
                <li><a href="{!! url('walletstransactions/driver') !!}">{{trans('lang.driver_wallet_transaction')}}</a></li>
                <li><a href="{!! url('walletstransaction') !!}">{{trans('lang.user_wallet_transaction')}}</a></li>
            </ul>
        </li> -->

        <li>
        	<a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
			    <i class="mdi mdi-settings"></i>
                <span class="hide-menu">{{trans('lang.administration_tools')}}</span>
            </a>

            <ul aria-expanded="false" class="collapse">
                <li><a href="{!! url('administration_tools/country') !!}">{{trans('lang.administration_tools_country')}}</a></li>
                <li><a href="{!! url('administration_tools/currency') !!}">{{trans('lang.administration_tools_currency')}}</a></li>
                <!-- <li><a href="{!! url('administration_tools/commission') !!}">{{trans('lang.administration_tools_commission')}}</a></li> -->
                <li><a href="{!! url('administration_tools/tax') !!}">{{trans('lang.administration_tools_tax')}}</a></li>
                <li><a href="{!! url('administration_tools/email_template') !!}">{{trans('lang.email_template')}}</a></li>                                
                <li><a href="{!! url('administration_tools/driver_document') !!}">{{trans('lang.administration_tools_driver_document')}}</a></li>
				<li><a href="{!! url('administration_tools/settings') !!}">{{trans('lang.administration_tools_settings')}}</a></li>
                <!-- <li><a href="{!! url('administration_tools/homepageTemplate') !!}">{{trans('lang.homepageTemplate')}}</a></li> -->
                <li><a href="{!! url('administration_tools/terms_condition') !!}">{{trans('lang.administration_tools_terms_condition')}}</a></li>
                <li><a href="{!! url('administration_tools/privacy_policy') !!}">{{trans('lang.administration_tools_privacy_policy')}}</a></li>
                <li><a href="{!! url('language') !!}">{{trans('lang.language')}}</a></li>
                <li><a href="{!! url('settings/payment/razorpay') !!}">{{trans('lang.administration_payment_methods')}}</a></li>
            </ul>
        </li>

        <li>
	  		<a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-calendar-check"></i>
                <span class="hide-menu">{{trans('lang.reports')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{!! url('reports/userreport') !!}">{{trans('lang.user_reports')}}</a></li>
                <li><a href="{!! url('reports/driverreport') !!}">{{trans('lang.driver_reports')}}</a></li>
                <li><a href="{!! url('reports/travelreport') !!}">{{trans('lang.travel_report')}}</a></li>
            </ul>
        </li>
    </ul>
</nav>

<p class="webversion">V:<?php echo $app_setting->web_version; ?></p>
