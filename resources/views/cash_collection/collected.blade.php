@extends('layouts.app')

@section('content')
<div class="page-wrapper">

                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-themecolor">{{trans('lang.cash_collected')}} </h3>
                    </div>
                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                            <li class="breadcrumb-item active"> {{trans('lang.cash_collected')}} </li>
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
                                            <!-- Swamy Goud -->
                                            <!-- <a class="nav-link" href="http://127.0.0.1:8000/users/create"><i
                                                    class="fa fa-plus mr-2"></i>Create User</a> -->
                                        </div>
                                        <div class="ml-auto collection_blk user-detail">
                                            <div class="col-group">
												<label>{{trans('lang.total_trips')}}: <span>{{$totaltrips}}</span></label>
											</div>
                                            <div class="col-group">
												<label>{{trans('lang.pending_amount')}}: <span>{{$currency->symbole . "" . number_format($totalamount,$currency->decimal_digit)}}</span></label>
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
                                                    <th>{{trans('lang.driver_name')}}</th>
                                                    <th>{{trans('lang.total_cash_trips')}}</th>
                                                    <th>{{trans('lang.amount_collected')}}</th>
                                                    <th>{{trans('lang.collected_date')}}</th>
                                                    <th>{{trans('lang.view')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="append_list12">
                                            @if(count($cashcollections) > 0)
                                            @foreach($cashcollections as $value)
                                                <tr>
                                                    
                                                @if (!empty($value->photo_path))
                                                <td> <img class="rounded" style="width:50px"
                                                        src="{{asset('assets/images/driver').'/'.$value->photo_path}}"
                                                        alt="image"></td>
                                                @else
                                                <td><img class="rounded" style="width:50px"
                                                        src="{{ asset('assets/images/placeholder_image.jpg')}}" alt="image"></td>

                                                @endif
                                                            
                                                    <td>{{$value->drivername}}</td>
                                                    <td>{{$value->rides}}</td>
                                                    <td>{{$currency->symbole . "" . number_format($value->pendingamount,$currency->decimal_digit)}}</td>
                                                    <td>{{$value->updated_at}}</td>

                                                    <td class="action-btn">
                                                        <a href="{{route('cash_collection.coldetail', ['id' => $value->id,'transactionid'=> $value->cod_collected_transaction_id])}}" class=""
                                                            data-toggle="tooltip" data-original-title="Details"><i
                                                                class="fa fa-eye"></i></a>
                                                    </td>
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