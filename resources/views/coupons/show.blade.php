@extends('layouts.app')

@section('content')

<div class="page-wrapper userdetail-page">

        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor">{{trans('lang.coupon_detail')}}</h3>

            </div>

            <div class="col-md-7 align-self-center">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">{{trans('lang.dashboard')}}</a></li>

                    <li class="breadcrumb-item"><a href="{!! url('coupons') !!}">{{trans('lang.coupon_plural')}}</a></li>

                    <li class="breadcrumb-item active">{{trans('lang.coupon_detail')}}</li>

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
                                             <h4 class="card-title"> Details of {{$discount->code}}</h4>
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
		                                    	<label for="" class="font-weight-bold">{{trans('lang.coupon_code')}}:</label>
		                                    	<span>{{ $discount->code}}</span> 
		                                	</div>
		                                </div>
		
		                                <div class="col-md-6">
		                                  	<div class="col-group">  
		                                    	<label for="" class="font-weight-bold">{{trans('lang.coupon_discount')}}:</label> 
		                                    	<span>{{ $discount->discount}}</span> 
		                                   	</div>
		                                </div>
                                        <div class="col-md-6">
		                                  	<div class="col-group">  
		                                    	<label for="" class="font-weight-bold">{{trans('lang.type')}}:</label> 
		                                    	<span>{{ $discount->type}}</span> 
		                                   	</div>
		                                </div>
                                        <div class="col-md-6">
		                                  	<div class="col-group">  
		                                    	<label for="" class="font-weight-bold">{{trans('lang.coupon_description')}}:</label> 
		                                    	<span>{{ $discount->discription}}</span> 
		                                   	</div>
		                                </div>
                                        <div class="col-md-6">
		                                  	<div class="col-group">  
		                                    	<label for="" class="font-weight-bold">{{trans('lang.coupon_expires_at')}}:</label> 
		                                    	@if($discount->expire_at!='0000-00-00 00:00:00')
                                                      <span class="date">{{ date('d F Y',strtotime($discount->expire_at))}}</span>
                                                      <span class="time">{{ date('h:i A',strtotime($discount->expire_at))}}</span>
                                                      @endif
		                                   	</div>
		                                </div>
										<div class="col-md-6">
		                                  	<div class="col-group">  
		                                    	<label for="" class="font-weight-bold">{{trans('lang.coupon_type')}}:</label> 
		                                    	<span>{{ $discount->coupon_type}}</span> 
		                                   	</div>
		                                </div>
		                            	<div class="col-md-12">
		                                    <div class="col-group-btn">
		                                        @if ($discount->statut=="yes")
		                                            <a href="{{route('coupons.changeStatus', ['id' => $discount->id])}}" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Activate">Enable<i class="fa fa-check"></i> </a>
		                                        @else
		                                        <a href="{{route('coupons.changeStatus', ['id' => $discount->id])}}" class="btn btn-warning btn-sm" data-toggle="tooltip" data-original-title="Activate"> Disable<i class="fa fa-check"></i> </a>
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
