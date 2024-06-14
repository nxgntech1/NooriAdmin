@extends('layouts.app')

@section('content')
    <div class="page-wrapper">


        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor">{{trans('lang.driver_wallet_transaction_plural')}}</h3>

            </div>

            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item active">{{trans('lang.driver_wallet_transaction_plural')}}</li>
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
                                                class="fa fa-list mr-2"></i>{{trans('lang.driver_wallet_transaction_table')}}
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">
                            <div id="data-table_processing" class="dataTables_processing panel panel-default"
                                 style="display: none;">{{trans('lang.processing')}}</div>

                            @if($id!='')
                                <form action="{{ url('walletstransactions/driver',['id'=>$id]) }}" method="get">
                                    @else
                                        <form action="{{ route('walletstransactions.driver') }}" method="get">
                                            @endif


                                            <div id="users-table_filter" class="pull-right">
                                                <label>{{trans('lang.search_by')}}
                                                    @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                                        <select name="selected_search" id="selected_search"
                                                                class="form-control input-sm">
                                                            <option value="transaction_id" @if ($_GET[
                                            'selected_search']=='transaction_id')
                                                            selected="selected" @endif>{{trans('lang.transaction_id')}}</option>

                                                        </select>
                                                    @else
                                                        <select name="selected_search" id="selected_search"
                                                                class="form-control input-sm">
                                                            <option value="transaction_id">{{ trans('lang.transaction_id')}}</option>

                                                        </select>
                                                    @endif
                                                    <div class="form-group">

                                                        @if(isset($_GET['payment_status']) && $_GET['payment_status'] != '')

                                                            <input type="text" id="search" name="search"
                                                                   class="search form-control" placeholder="Search"
                                                                   aria-controls="users-table" style="display: none">

                                                        @elseif(isset($_GET['search']) && $_GET['search'] != '')
                                                            <input type="text" id="search" name="search"
                                                                   class="search form-control" placeholder="Search"
                                                                   aria-controls="users-table"
                                                                   value="{{$_GET['search']}}">

                                                        @else

                                                            <input type="text" id="search" name="search"
                                                                   class="search form-control" placeholder="Search"
                                                                   aria-controls="users-table">
                                                    @endif

                                                </label>

                                                &nbsp;<button onclick="searchtext();"
                                                              class="btn btn-warning btn-flat">{{trans('lang.search')}}</button>&nbsp;


                                                @if($id!='')
                                                    <a href="{{ url('walletstransactions/driver',['id'=>$id]) }}"
                                                    class="btn btn-warning btn-flat">{{trans('lang.clear')}}</a>
                                                @else
                                                    <a href="{{ route('walletstransactions.driver') }}"
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
                                    @if($id=='')
                                    <th>{{ trans('lang.driver')}}</th>
                                    @endif
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
                                        @if($id=='')
                                        <td>
                                            <a href="{{ route('driver.show',['id'=>$data->userId]) }}">{{ $data->firstname }} {{ $data->lastname }}</a>
                                        </td>
                                        @endif
                                        <td>
                                         @if($currency->symbol_at_right=="true")
                                            @if(substr($data->amount,0,1)=="-")
                                            <span style="color:red">(-{{number_format(floatval(substr($data->amount,1)),$currency->decimal_digit)."".$currency->symbole }})</span>
                                            @else
                                            <span style="color:green">{{number_format(floatval($data->amount),$currency->decimal_digit)."".$currency->symbole }}</span>
                                            @endif
                                         @else
                                             @if(substr($data->amount,0,1)=="-")
                                               <span style="color:red">(-{{ $currency->symbole."".number_format(floatval(substr($data->amount,1)),$currency->decimal_digit) }})</span>
                                             @else
                                              <span style="color:green">{{ $currency->symbole."".number_format(floatval($data->amount),$currency->decimal_digit) }}</span>
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
                                                <span class="badge badge-success">{{trans('lang.success')}}</span>
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
      /*  $(document).ready(function () {
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
        });*/
    </script>



@endsection
