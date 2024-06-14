@extends('layouts.app')

@section('content')

<div class="page-wrapper driverdetail-page">

	<div class="row page-titles">

		<div class="col-md-5 align-self-center">

			<h3 class="text-themecolor">{{trans('lang.country_detail')}}</h3>

		</div>

		<div class="col-md-7 align-self-center">

			<ol class="breadcrumb">

				<li class="breadcrumb-item">
					<a href="{!! url('/dashboard') !!}">{{trans('lang.home')}}</a>
				</li>

				<li class="breadcrumb-item">
					<a href="{!! route('country') !!}">{{trans('lang.administration_tools_country')}}</a>
				</li>

				<li class="breadcrumb-item active">
				{{trans('lang.country_detail')}}
				</li>

			</ol>

		</div>

	</div>

	<div class="container-fluid">

		<div class="row">

			<div class="col-12">

				<div class="card">

					<div class="card-body p-0 pb-5">

						<div class="user-top">
							<div class="row align-items-center">
							
								<div class="user-title col-md-8">
									<h4 class="card-title"> Details of {{$countries->libelle}}</h4>
								</div>
							</div>
						</div>

						<div class="user-detail" role="tabpanel">

							<!-- Nav tabs -->
							<ul class="nav nav-tabs">

								<li role="presentation" class="">
									<a href="#information" aria-controls="information" role="tab" data-toggle="tab" class="{{ (Request::get('tab') == 'information' || Request::get('tab') == '') ? 'active show' : '' }}">Information</a>
								</li>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">

								<div role="tabpanel" class="tab-pane {{ (Request::get('tab') == 'information' || Request::get('tab') == '') ? 'active' : '' }}" id="information">

									<div class="row">

										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.name')}}:</label>
												<span>{{ $countries->libelle}}</span>
											</div>
										</div>

										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.code')}}:</label>
												<span>{{ $countries->code}} </span>
											</div>
										</div>

										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.status')}} :</label>
												@if ($countries->statut=="yes")
												<span class="badge badge-success">enable</span>
												@else
												<span class="badge badge-warning">disable</span>
												@endif
											</div>
										</div>

										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.created_at')}} :</label>
												<span class="date">{{ date('d F Y',strtotime($countries->creer))}}</span>
                                                                        <span class="time">{{ date('h:i A',strtotime($countries->creer))}}</span>
											</div>
										</div>

										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.edited')}} :</label>
												@if($countries->modifier!='0000-00-00 00:00:00')
                                                                        <span class="date">{{ date('d F Y',strtotime($countries->modifier))}}</span>
                                                                        <span class="time">{{ date('h:i A',strtotime($countries->modifier))}}</span>
                                                                        @endif
											</div>
										</div>

										
										<div class="col-md-12 text-center">
											<div class="col-group-btn">
												@if ($countries->statut=="no")
												<a href="{{route('country.changeStatus', ['id' => $countries->id])}}" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Activate"> Enable Country <i class="fa fa-check"></i> </a>
												@else
												<a href="{{route('country.changeStatus', ['id' => $countries->id])}}" class="btn btn-warning btn-sm" data-toggle="tooltip" data-original-title="Activate"> Disable Country <i class="fa fa-check"></i> </a>
												@endif
											</div>
										</div>
									</div>

								</div>
								
							
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
