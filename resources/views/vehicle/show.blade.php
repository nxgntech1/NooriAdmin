@extends('layouts.app')

@section('content')

<div class="page-wrapper ridedetail-page">

	<div class="row page-titles">

		<div class="col-md-5 align-self-center">

			<h3 class="text-themecolor">{{trans('lang.vehicle_rent_detail')}}</h3>

		</div>

		<div class="col-md-7 align-self-center">

			<ol class="breadcrumb">

				<li class="breadcrumb-item">
					<a href="{!! url('/dashboard') !!}">{{trans('lang.home')}}</a>
				</li>

				<li class="breadcrumb-item">
					<a href="{!! url('/vehicle/vehicle-rent') !!}">{{trans('lang.vehicle_rent')}}</a>
				</li>

				<li class="breadcrumb-item active">
				{{trans('lang.vehicle_rent_detail')}}
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

								<!--<div class="user-profile col-md-2">

									<div class="profile-img">


									</div>

								</div>-->
								<div class="user-title col-md-8">
									<h4 class="card-title"> Details of Rent </h4>
								</div>
							</div>
						</div>

						<div class="user-detail taxi-detail" role="tabpanel">

							<!-- Nav tabs -->
							<ul class="nav nav-tabs">

								<li role="presentation" class="">
									<a href="#user" aria-controls="information" role="tab" data-toggle="tab" class="{{ (Request::get('tab') == 'user' || Request::get('tab') == '') ? 'active show' : '' }}">User</a>
								</li>
								<li role="presentation" class="">
									<a href="#vehicle_type" aria-controls="vehicle_type" role="tab" data-toggle="tab" class="{{ (Request::get('tab') == 'vehicle_type') ? 'active show' : '' }}}}">Vehicle Type</a>
								</li>

								<li role="presentation" class="">
									<a href="#rental_vehicle" aria-controls="rental_vehicle" role="tab" data-toggle="tab" class="{{ (Request::get('tab') == 'rental_vehicle') ? 'active show' : '' }}">Rental Vehicle</a>
								</li>

							</ul>

							<!-- Tab panes -->
							<div class="tab-content">

								<div role="tabpanel" class="tab-pane {{ (Request::get('tab') == 'user' || Request::get('tab') == '') ? 'active' : '' }}" id="user">

									<div class="row">
										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.user_name')}}:</label>
												<span>{{ $rentals->userPrenom}} {{ $rentals->userNom}}</span>
											</div>
										</div>

										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.user_phone')}}:</label>
												<span>{{ $rentals->user_phone}}</span>
											</div>
										</div>

										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.email')}}:</label>
												<span>{{ $rentals->user_email}}</span>
											</div>
										</div>




									</div>

								</div>
								<div role="tabpanel" class="tab-pane {{ Request::get('tab') == 'vehicle_type' ? 'active' : '' }}" id="vehicle_type">

									<div class="row">
										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.vehicle_type_name')}}:</label>
												<span>{{ $rentals->libelle}} </span>
											</div>
										</div>

										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.Image')}}:</label>
												<span><img alt="Image" style="width:50px;" src="{{url('/assets/images/type_vehicle_rental/'.$rentals->image) }}"></span>
											</div>
										</div>

									</div>

								</div>

								<div role="tabpanel" class="tab-pane {{ Request::get('tab') == 'rental_vehicle' ? 'active' : '' }}" id="rental_vehicle">

									<div class="row">

										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.number_of_days')}}:</label>
												<span >{{ $rentals->nb_jour}}</span>
											</div>
										</div>
										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.start_date')}}:</label>
												<span class="date">{{ date('d F Y',strtotime($rentals->date_debut))}}</span>
											</div>
										</div>
										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">{{trans('lang.end_date')}}:</label>
												<span class="date">{{ date('d F Y',strtotime($rentals->date_fin))}}</span>
											</div>
										</div>
										<div class="col-md-6">
											<div class="col-group">
												<label for="" class="font-weight-bold">Price:</label>
												<?php 
													if($rentals->nb_jour !== 0) {
														$price= $rentals->prix*$rentals->nb_jour;
													}
													else{
														$price= $rentals->prix;
													}
												?>
												<span class="price">
													@if($currency->symbol_at_right=="true")
													{{number_format(floatval($price),$currency->decimal_digit)."".$currency->symbole}}
													@else
													{{$currency->symbole."".number_format(floatval($price),$currency->decimal_digit)}}
													@endif
												</span>
											</div>
										</div>
										<div class="col-md-6">
                                            <div class="col-group">
                                                <label for="" class="font-weight-bold">{{trans('lang.status')}}:</label>
                                                    <select class="form-control model" name="statut" id="statut">
                                                    <option value="in progress" {{$rentals->statut == 'in progress' ? 'selected="selected' : '' }}>In Progress</option>
                                                    <option value="accepted" {{$rentals->statut == 'accepted' ? 'selected="selected' : '' }}>Accepted</option>
                                                    <option value="rejected" {{$rentals->statut == 'rejected' ? 'selected="selected' : '' }}>Rejected</option>
                                                    <option value="completed" {{$rentals->statut == 'completed' ? 'selected="selected"' : '' }}>Completed</option>
                                                    </select>
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
@section('scripts')
<script>
                    $(document).ready(function () {
                        $('select[name="statut"]').on('change', function () {

                            var status = $(this).val();
                            var rental_id = '<?php echo $rentals->id; ?>';
							console.log(rental_id);
                            var url = "{{ route('vehicleRental.ChangeStatus',':id') }}";
                            url = url.replace(':id', rental_id);

                            if (status) {
                                $.ajax({
                                    url: url,
                                    type: "GET",
                                    data: {
										statut:status,
                                        _token: '{{csrf_token()}}',
                                    },

                                    dataType: 'json',
                                    success: function (data) {
										
                                      alert(data.data);
                                       
                                    }
                                });
                            } 
                        });


                    });
</script>
@endsection
