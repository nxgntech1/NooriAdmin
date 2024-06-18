@extends('layouts.app')

@section('content')
<div class="page-wrapper">

                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-themecolor"> {{trans('lang.cash_collection_detail')}} </h3>
                    </div>
                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                            <li class="breadcrumb-item active"> <a href="{{url('cash_collection/collected')}}">{{trans('lang.cash_collected')}}</a> </li>
                            <li class="breadcrumb-item active"> {{trans('lang.cash_collection_detail')}} </li>
                        </ol>
                    </div>
                    <div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-body">
                                    <div id="data-table_processing" class="dataTables_processing panel panel-default"
                                        style="display: none;">Processing...
                                    </div>
                                    <div class="userlist-topsearch d-flex mb-3">
                                        <div class="userlist-top-left">
                                           <div class="driver_namecollect"> {{trans('lang.driver_name')}} : <span> {{$drivername}} </span><br>
                                           {{trans('lang.collected_date')}} : {{$transactionDate}} </div> 
                                            <!-- <a class="nav-link" href="http://127.0.0.1:8000/users/create"><i
                                                    class="fa fa-plus mr-2"></i>Create User</a> -->
                                        </div>
                                        <div class="ml-auto collection_blk user-detail">
                                            <div class="col-group">
												<label>{{trans('lang.total_trips')}}: <span>{{$totaltrips}}</span></label>
											</div>
                                            <div class="col-group">
												<label>{{trans('lang.total_amount')}}: <span>{{$currency->symbole . "" . number_format($totalamount,$currency->decimal_digit)}}</span></label>
											</div>
                                        </div>
                                        <!-- <div id="users-table_filter" class="ml-auto">
                                            <label>Search By :
                                                <div class="form-group mb-0">
                                                    <form action="http://127.0.0.1:8000/users" method="get">

                                                        <select name="selected_search" id="selected_search"
                                                            class="form-control input-sm">
                                                            <option value="prenom">Name</option>
                                                            <option value="email">Email</option>
                                                            <option value="phone">Phone</option>
                                                        </select>
                                                        <div class="search-box position-relative">
                                                            <input type="text" class="search form-control" name="search"
                                                                id="search">
                                                            <button type="submit" class="btn-flat position-absolute"><i
                                                                    class="fa fa-search"></i></button>
                                                            
                                                            <a class="btn btn-warning btn-flat"
                                                                href="http://127.0.0.1:8000/users">Clear</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </label>
                                        </div> -->
                                    </div>
                                    <div class="table-responsive m-t-10">
                                        <table id="example24"
                                            class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                            cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>{{trans('lang.extra_image')}}</th>
                                                    <th>{{trans('lang.car_model_brand')}}</th>
                                                    <th>{{trans('lang.bookingtype_name')}}</th>
                                                    <th>{{trans('lang.trip_date_time')}}</th>
                                                    <th>{{trans('lang.total_amount')}} </th>
                                                </tr>
                                            </thead>
                                            <tbody id="append_list12">
                                            @if(count($pendingcollections) > 0)
                                            @foreach($pendingcollections as $value)
                                                <tr>
                                                @if (!empty($value->primary_image_id))
                                                        <td> <img class="rounded" style="width:50px"
                                                                src="{{asset('assets/images/vehicle').'/'.$value->primary_image_id}}"
                                                                alt="image"></td>
                                                        @else
                                                        <td><img class="rounded" style="width:50px"
                                                                src="{{ asset('assets/images/placeholder_img_car.png')}}" alt="image"></td>

                                                        @endif
                                                        <td>{{$value->brandname}} / {{$value->modelname}} / {{$value->numberplate}}</td>
                                                        <td>{{$value->bookingtype}}</td>
                                                        <td>{{$value->ride_required_on_date}}</td>
                                                        <td>{{$currency->symbole . "" . number_format( $value->montant,$currency->decimal_digit)}}</td>
                                                </tr>
                                            @endforeach
                                            @else
		                                		<tr><td colspan="11" align="center">{{trans("lang.no_result")}}</td></tr>
		                                	@endif
                                            </tbody>
                                        </table>


                                        <!--  -->
                                        <nav aria-label="Page navigation example" class="custom-pagination">

                                        </nav>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>       
@endsection

@section('scripts')
@endsection