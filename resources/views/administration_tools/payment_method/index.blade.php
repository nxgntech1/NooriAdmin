@extends('layouts.app')

@section('content')
        <div class="page-wrapper">

            <div class="row page-titles">

                <div class="col-md-5 align-self-center">

                    <h3 class="text-themecolor">{{trans('lang.administration_tools_payment_method')}}</h3>

                </div>

                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('lang.administration_tools')}}</li>
                        <li class="breadcrumb-item active">{{trans('lang.administration_tools_payment_method')}}</li>
                    </ol>
                </div>

            </div>

      

            <div class="container-fluid">

                <div class="row">

                    <div class="col-12">

                        <div class="card">
                            <!-- <div class="card-header">
                                <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                                    <li class="nav-item">
                                      <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.payment_method_table')}}</a>
                                    </li>                                  
                                </ul>
                            </div> -->
                            <div class="card-body">
                          
                                <div class="userlist-topsearch d-flex mb-3">   
                                    <!-- <div class="userlist-top-left">  
                                        <a class="nav-link" href="{!! route('users.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.user_create')}}</a>
                                    </div>  -->  
                                <div id="users-table_filter" class="ml-auto">
                                    <div class="form-group mb-0">   
                                        <form action="{{ route('payment_method') }}" method="get">
                                            @if(isset($_GET['selected_search']) &&  $_GET['selected_search'] != '')
                                            <select name="selected_search" id="selected_search" class="form-control input-sm">
                                            <option value="libelle ">{{ trans('lang.Name')}}</option>
                                            
                                            </select>
                                            @else
                                            <select name="selected_search" id="selected_search" class="form-control input-sm">
                                            <option value="libelle ">{{ trans('lang.Name')}}</option>
                                            
                                            </select>
                                            @endif
                                            <div class="search-box position-relative">
                                                @if(isset($_GET['search']) &&  $_GET['search'] != '')
                                                <input type = "text" class="search form-control" name="search" id = "search" value="{{$_GET['search']}}">
                                                @else
                                                <input type = "text" class="search form-control" name="search" id = "search">
                                                @endif
                                                <button type="submit" class="btn-flat position-absolute"><i class="fa fa-search"></i></button>
                                                <a class="btn btn-warning btn-flat" href="{{url('administration_tools/payment_method')}}">Clear</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                                <div class="table-responsive m-t-10">

                                    <table id="example24" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">

                                        <thead>

                                            <tr>

                                                <th>{{trans('lang.payment_method_name')}}</th>
                                                <th>{{trans('lang.payment_method_image')}}</th>
                                                <!-- <th>{{trans('lang.payment_method_status')}}</th> -->
                                                <th>{{trans('lang.payment_method_created')}}</th>
                                                <th>{{trans('lang.payment_method_modifier')}}</th>
                                                <th>{{trans('lang.actions')}}</th>

                                            </tr>

                                        </thead>

                                        <tbody id="append_list1">
                                                
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td>{{ $payment->libelle}}</td>
                                                @if ($payment->image == "")
                                                    <td>
                                                         not found
                                                    </td>
                                                @else
                                                    <td>
                                                        <img class="rounded" style="width:50px" src="{{asset('/assets/images/payment_method/'.$payment->image)}}" alt="image">
                                                    </td>
                                                @endif
                                               
                                                <!-- <td>
                                                    @if ($payment->statut=="yes")
                                                        <span class="badge badge-success">{{ $payment->statut }}<span>
                                                    @else
                                                        <span class="badge badge-warning">{{ $payment->statut }}<span>
                                                    @endif
                                                </td>     -->
                                                <td>{{ $payment->creer}}</td>
                                                <td>{{ $payment->modifier}}</td>
                                                <td><a href="{{route('payment_method.show', ['id' => $payment->id])}}" class="" data-toggle="tooltip" data-original-title="View détails"><i class="fa fa-ellipsis-h"></i></a></td>
                                            </tr>
                                        @endforeach     
    
                                        </tbody>

                                    </table>

                                    <nav aria-label="Page navigation example" class="custom-pagination">
                                        {{ $payments->Links() }}
                                    </nav> 
 {{ $payments->Links('pagination.pagination') }}
 
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



@endsection

@section('scripts')

<script type="text/javascript">
 
$(document).ready(function() {
 $(".shadow-sm").hide();
})



</script>

@endsection
