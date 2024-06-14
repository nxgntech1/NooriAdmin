<nav class="sidebar-nav">

    <ul id="sidebarnav">

        <li >
        	<a class="waves-effect waves-dark" href="{!! url('/dashboard') !!}">
        	 	<i class="mdi mdi-home"></i>
        	 	<span class="hide-menu">Dashboard</span>
        	</a>
        </li>
        <li>
        <a class="has-arrow waves-effect waves-dark" class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                 <i class="mdi mdi-account-card-details"></i>
                <span class="hide-menu">{{trans('lang.driver_plural')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
            	<li><a href="{!! url('drivers') !!}">{{trans('lang.all_drivers')}}</a></li>
                <li><a href="{!! url('drivers/approved') !!}">{{trans('lang.approved_drivers')}}</a></li>
                <li><a href="{!! url('drivers/pending') !!}">{{trans('lang.pending_drivers')}}</a></li>
            </ul>
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
        <li>
	  		<a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-calendar-check"></i>
                <span class="hide-menu">{{trans('lang.reports')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{!! url('reports/driverreport') !!}">{{trans('lang.driver_reports')}}</a></li>
                <li><a href="{!! url('reports/travelreport') !!}">{{trans('lang.travel_report')}}</a></li>
            </ul>
        </li>
    </ul>
</nav>

<p class="webversion">V:<?php echo $app_setting->web_version; ?></p>
