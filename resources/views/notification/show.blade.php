@extends('layouts.app')

@section('content')

<div class="page-wrapper ridedetail-page">

	<div class="row page-titles">

		<div class="col-md-5 align-self-center">

			<h3 class="text-themecolor">{{trans('lang.notifications')}}</h3>

		</div>

		<div class="col-md-7 align-self-center">

			<ol class="breadcrumb">

				<li class="breadcrumb-item">
					<a href="{!! url('/dashboard') !!}">{{trans('lang.home')}}</a>
				</li>

				<li class="breadcrumb-item">
					<a href="{!! route('rides.all') !!}">{{trans('lang.all_notifications')}}</a>
				</li>

				<li class="breadcrumb-item active">
				{{trans('lang.notifications')}}
				</li>

			</ol>

		</div>

	</div>

	<div class="container-fluid">

		<div class="row">

			<div class="col-12">

				<div class="card">

					<div class="card-body p-0 pb-5">

						<div class="user-detail" role="tabpanel">

							<div class="row">
								
								<div class="col-12">	
									
								
						                
									
						            <div class="box">
						                <div class="box-header bb-2 border-primary">
						                    <h3 class="box-title">{{trans('lang.notifications')}}</h3>
						                </div>
						                <div class="box-body">
						                    <table class="table table-hover">
						                        <thead>
						                            <tr>
						                                <th>{{trans('lang.title')}}</th>
						                                <th>{{trans('lang.message')}}</th>
						                                <th>{{trans('lang.type')}}</th>
                                                        <th>{{trans('lang.status')}}</th>
						                            </tr>
						                        </thead>
						                        <tbody>
						                            <tr>
						                                <td>{{ $notifications->titre }}</td>
						                                <td class="address-td">{{ $notifications->message}}</td>
						                                <td>{{ $notifications->type}}</td>
                                                        <th>{{ $notifications->statut }}</th>
						                            </tr>
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
	</div>
</div>

<script type="text/javascript">

</script>

@endsection
