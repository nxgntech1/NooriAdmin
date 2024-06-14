@extends('layouts.app')

@section('content')
    <div class="page-wrapper">


        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor">{{trans('lang.user_wallet_transaction_plural')}}</h3>

            </div>

            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item active">{{trans('lang.user_wallet_transaction_plural')}}</li>
                </ol>
            </div>

            <div>

            </div>

        </div>


        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{!! url()->current() !!}"><i
                                                class="fa fa-list mr-2"></i>{{trans('lang.user_wallet_transaction_table')}}
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">
                            <div id="data-table_processing" class="dataTables_processing panel panel-default"
                                 style="display: none;">{{trans('lang.processing')}}</div>
                            @if($id!='')
                                <form action="{{ url('walletstransaction',['id'=>$id]) }}" method="get">
                                    @else
                                        <form action="{{ route('walletstransaction') }}" method="get">
                                            @endif

                                            <div id="users-table_filter" class="pull-right">
                                                <label>{{trans('lang.search_by')}}
                                                    @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                                        <select name="selected_search" id="selected_search"
                                                                class="form-control input-sm">
                                                            <option value="transaction_id" @if ($_GET[
                                            'selected_search']=='transaction_id')
                                                            selected="selected" @endif>{{trans('lang.transaction_id')}}</option>

                                                            <option value="payment_status" @if ($_GET[
                                            'selected_search']=='payment_status')
                                                            selected="selected" @endif>{{trans('lang.payment_status')}}</option>


                                                        </select>
                                                    @else
                                                        <select name="selected_search" id="selected_search"
                                                                class="form-control input-sm">
                                                            <option value="transaction_id">{{ trans('lang.transaction_id')}}</option>
                                                            <option value="payment_status">{{ trans('lang.payment_status')}}</option>
                                                        </select>
                                                    @endif
                                                    <div class="form-group">

                                                        @if(isset($_GET['payment_status']) && $_GET['payment_status'] != '')
                                                            <select id="payment_status" class="form-control"
                                                                    name="payment_status">

                                                                <option class="success"
                                                                        @if($_GET['payment_status']=='success')selected="selected"
                                                                        @endif value="success">{{ trans('lang.success')}}</option>
                                                                <option value="refund success"
                                                                        @if($_GET['payment_status']=='refund success')selected="selected" @endif>{{ trans('lang.refund_success')}}</option>
                                                                <option value="canceled"
                                                                        @if($_GET['payment_status']=='canceled')selected="selected" @endif>{{ trans('lang.canceled')}}</option>
                                                                <option value="pending"
                                                                        @if($_GET['payment_status']=='pending')selected="selected" @endif>{{ trans('lang.pending')}}</option>
                                                            </select>
                                                            <input type="text" id="search" name="search"
                                                                   class="search form-control" placeholder="Search"
                                                                   aria-controls="users-table" style="display: none">

                                                        @elseif(isset($_GET['search']) && $_GET['search'] != '')
                                                            <input type="text" id="search" name="search"
                                                                   class="search form-control" placeholder="Search"
                                                                   aria-controls="users-table"
                                                                   value="{{$_GET['search']}}">
                                                            <select id="payment_status" class="form-control"
                                                                    name="payment_status" style="display: none">

                                                                <option class="success"

                                                                        value="success">{{ trans('lang.success')}}</option>
                                                                <option value="refund success"
                                                                >{{ trans('lang.refund_success')}}</option>
                                                                <option value="canceled"
                                                                >{{ trans('lang.canceled')}}</option>
                                                                <option value="pending"
                                                                >{{ trans('lang.pending')}}</option>
                                                            </select>
                                                        @else
                                                            <select id="payment_status" class="form-control"
                                                                    name="payment_status" style="display: none">

                                                                <option class="success" value="success">{{ trans('lang.success')}}</option>
                                                                <option value="refund success"
                                                                >{{ trans('lang.refund_success')}}</option>
                                                                <option value="canceled"
                                                                >{{ trans('lang.canceled')}}</option>
                                                                <option value="pending"
                                                                >{{ trans('lang.pending')}}</option>
                                                            </select>

                                                            <input type="text" id="search" name="search"
                                                                   class="search form-control" placeholder="Search"
                                                                   aria-controls="users-table">
                                                    @endif

                                                </label>

                                                &nbsp;<button onclick="searchtext();"
                                                              class="btn btn-warning btn-flat">{{trans('lang.search')}}</button>&nbsp;
                                                @if($id!='')
                                                    <a href="{{ url('walletstransaction',['id'=>$id]) }}"
                                                       class="btn btn-warning btn-flat">{{trans('lang.clear')}}</a>
                                                @else
                                                    <a href="{{ route('walletstransaction') }}"
                                                    class="btn btn-warning btn-flat">{{trans('lang.clear')}}</a>
                                                @endif


                                            </div>
                                        </form>
                        </div>


                        <div class="table-responsive m-t-10">


                            <table id="example24"
                                   class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                   cellspacing="0" width="100%">

                                <thead>

                                <tr>
                                    <th>{{ trans('lang.transaction_id')}}</th>
                                    <th>{{ trans('lang.users')}}</th>
                                    <th>{{trans('lang.amount')}}</th>
                                    <th>{{trans('lang.date')}}</th>
                                    <th>{{trans('lang.payment_method')}}</th>
                                    <th>{{trans('lang.payment_status')}}</th>
                                </tr>

                                </thead>

                                <tbody id="append_list1">
                                  @if(count($transaction) > 0)
                                @foreach($transaction as $data)

                                    <tr>
                                    <!-- <td>{{ $data->id }}</td> -->
                                        <td>{{ $data->id }}</td>
                                        <td>
                                            <a href="{{ route('users.show',['id'=>$data->userId]) }}">{{ $data->firstname }} {{ $data->lastname }}</a>
                                        </td>
                                        <td>
                                         @if($currency->symbol_at_right=="true")
                                            @if($data->deduction_type=="0")
                                            <span style="color:red">(-{{number_format($data->amount,$currency->decimal_digit)."".$currency->symbole }})</span>
                                            @else
                                            <span style="color:green">{{number_format($data->amount,$currency->decimal_digit)."".$currency->symbole }}</span>
                                            @endif
                                         @else
                                            @if($data->deduction_type=="0")
                                               <span style="color:red">(-{{ $currency->symbole."".number_format($data->amount,$currency->decimal_digit) }})</span>
                                             @else
                                              <span style="color:green">{{ $currency->symbole."".number_format($data->amount,$currency->decimal_digit) }}</span>
                                             @endif
                    
                                         @endif   
                                           
                                        </td>
                                        <td>
                                            <span class="date">{{ date('d F Y',strtotime($data->creer))}}</span>
                                            <span class="time">{{ date('h:i A',strtotime($data->creer))}}</span>
                                        </td>
                                        @if($data->image)
                                            <td><img class="rounded" style="width:50px"
                                                src="{{asset('/assets/images/payment_method/'.$data->image)}}"
                                                 alt="image"></td>
											@else
											<td>{{ $data->payment_method}}</td>
                                        @endif
                                        <td>
                                            @if($data->payment_status == 'success')
                                                <span class="badge badge-success">{{ $data->payment_status }}</span>
                                            @elseif($data->payment_status == 'pending')
                                                <span class="badge badge-warning">{{ $data->payment_status }}</span>
                                            @elseif($data->payment_status == 'canceled')
                                                <span class="badge badge-danger">{{ $data->payment_status }}</span>
                                            @elseif($data->payment_status == 'rufund success')
                                                <span class="badge badge-success">{{ $data->payment_status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                 @else
                                	<tr><td colspan="11" align="center">{{trans("lang.no_result")}}</td></tr>
                                @endif

                                </tbody>

                            </table>
                            <nav aria-label="Page navigation example" class="custom-pagination">
                            {{$transaction->appends(request()->query())->links()}}
                            </nav>
                            {{ $transaction->links('pagination.pagination') }}
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
            $(".shadow-sm").hide();
            if($('#selected_search').val()=="transaction_id"){
              jQuery('#payment_status').val('');
            }else{
              jQuery('#search').val('');
            }
        })
        $(document.body).on('change', '#selected_search', function () {

            if (jQuery(this).val() == 'payment_status') {
                jQuery('#payment_status').show();
                  jQuery('#payment_status').val('success');
                jQuery('#search').val('');
                jQuery('#search').hide();
            } else {

                jQuery('#payment_status').hide();
                jQuery('#payment_status').val('');
                jQuery('#search').show();

            }
        });
    </script>



@endsection
