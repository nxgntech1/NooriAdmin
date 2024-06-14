@extends('layouts.app')

@section('content')
    <div class="page-wrapper">


        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor">{{trans('lang.drivers_payout_plural')}}</h3>

            </div>

            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item active">{{trans('lang.drivers_payout_plural')}}</li>
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
                            <div class="userlist-topsearch d-flex mb-3">
                                <div class="userlist-top-left">
                                    <a class="nav-link" href="{{ route('driversPayouts.create')}}"><i
                                                class="fa fa-plus mr-2"></i>{{trans('lang.drivers_payout_create')}}</a>
                                </div>
                                <div id="users-table_filter" class="ml-auto">
                                    <label>{{ trans('lang.search_by')}}
                                    <div class="form-group mb-0">
                                        <form action="{{ route('driversPayouts') }}" method="get">
                                            @if(isset($_GET['selected_search']) &&  $_GET['selected_search'] != '')
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="note" @if ($_GET['selected_search']=='note')
                                                    selected="selected" @endif>{{ trans('lang.drivers_payout_note')}}</option>

                                                </select>
                                            @else
                                                <select name="selected_search" id="selected_search"
                                                        class="form-control input-sm">
                                                    <option value="note">{{ trans('lang.drivers_payout_note')}}</option>

                                                </select>
                                            @endif
                                            <div class="search-box position-relative">
                                                @if(isset($_GET['search']) &&  $_GET['search'] != '')
                                                    <input type="text" class="search form-control" name="search"
                                                           id="search" value="{{$_GET['search']}}">
                                                @else
                                                    <input type="text" class="search form-control" name="search"
                                                           id="search">
                                                @endif

                                                <button type="submit" class="btn-flat position-absolute"><i
                                                            class="fa fa-search"></i></button>
                                                <a class="btn btn-warning btn-flat" href="{{url('driversPayouts')}}">Clear</a>
                                            </div>
                                        </form>
                                    </div>
                                    </label>
                                </div>
                            </div>


                            <div class="table-responsive m-t-10">


                                <table id="example24"
                                       class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                       cellspacing="0" width="100%">

                                    <thead>

                                    <tr>
                                        <th>{{ trans('lang.driver')}}</th>
                                        <th>{{trans('lang.paid_amount')}}</th>
                                        <th>{{trans('lang.drivers_payout_note')}}</th>
                                        <th>{{trans('lang.drivers_payout_paid_date')}}</th>
                                    </tr>

                                    </thead>

                                    <tbody id="append_list1">
                                    @if(count($withdrawal) > 0)
                                    @foreach($withdrawal as $value)
                                        <tr>
                                            <td>{{$value->prenom}} {{$value->nom}}</td>
                                            <td>
                                                @if($currency->symbol_at_right=="true")
                                                <span style="color:red">({{number_format($value->amount,$currency->decimal_digit)."".$currency->symbole}})</span>
                                                @else
                                                <span style="color:red">({{$currency->symbole."".number_format($value->amount,$currency->decimal_digit)}})</span>
                                                @endif
                                             </td>
                                            <td>{{$value->note}} </td>
                                            <td>{{date('d F Y h:i A',strtotime($value->creer))}} </td>
                                        </tr>
                                    @endforeach
                                     @else
                                	<tr><td colspan="11" align="center">{{trans("lang.no_result")}}</td></tr>
                                @endif		

                                    </tbody>

                                </table>
                                <nav aria-label="Page navigation example" class="custom-pagination">
                                {{$withdrawal->appends(request()->query())->links()}}
                                </nav>
                                {{ $withdrawal->links('pagination.pagination') }}
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


@endsection
