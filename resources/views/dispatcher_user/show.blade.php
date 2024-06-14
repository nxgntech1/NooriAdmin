@extends('layouts.app')

@section('content')

    <div class="page-wrapper userdetail-page">

        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor">{{trans('lang.user_details')}}</h3>

            </div>

            <div class="col-md-7 align-self-center">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">{{trans('lang.dashboard')}}</a></li>

                    <li class="breadcrumb-item"><a href="{!! url('users') !!}">{{trans('lang.dispatcher_user')}}</a>
                    </li>

                    <li class="breadcrumb-item active">{{trans('lang.user_details')}}</li>

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

                                    <div class="user-profile col-md-2">

                                        <div class="profile-img">

                                            @if (file_exists(public_path('assets/images/dispatcher_users'.'/'.$user->profile_picture_path)) && !empty($user->profile_picture_path))
                                                <td><img class="profile-pic"
                                                         src="{{asset('assets/images/dispatcher_users').'/'.$user->profile_picture_path}}"
                                                         alt="image"></td>
                                            @else
                                                <td><img class="profile-pic"
                                                         src="{{asset('assets/images/placeholder_image.jpg')}}"
                                                         alt="image"></td>

                                            @endif
                                        </div>

                                    </div>
                                    <div class="user-title col-md-8">
                                        <h4 class="card-title"> Details
                                            of {{$user->first_name}} {{$user->last_name}}</h4>
                                    </div>
                                </div>
                            </div>


                            <div class="user-detail" role="tabpanel">

                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs">

                                    <li role="presentation" class="">
                                        <a href="#information" aria-controls="information" role="tab" data-toggle="tab"
                                           class="{{ (Request::get('tab') == 'information' || Request::get('tab') == '') ? 'active show' : '' }}">Information</a>
                                    </li>

                                    <li role="presentation" class="">
                                        <a href="#rides" aria-controls="rides" role="tab" data-toggle="tab"
                                           class="{{ (Request::get('tab') == 'rides') ? 'active show' : '' }}">Rides</a>
                                    </li>

                                   {{-- <li role="presentation" class="">
                                        <a href="#transactions" aria-controls="transactions" role="tab"
                                           data-toggle="tab"
                                           class="{{ (Request::get('tab') == 'transactions') ? 'active show' : '' }}">Wallet
                                            Transactions</a>
                                    </li>--}}


                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">

                                    <div role="tabpanel"
                                         class="tab-pane {{ (Request::get('tab') == 'information' || Request::get('tab') == '') ? 'active' : '' }}"
                                         id="information">

                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="col-group">
                                                    <label for="" class="font-weight-bold">{{trans('lang.user_phone')}}
                                                        :</label>
                                                    <span>{{ $user->phone}}</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="col-group">
                                                    <label for="" class="font-weight-bold">{{trans('lang.email')}}
                                                        :</label>
                                                    <span>{{ $user->email}}</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="col-group">
                                                    <label for="" class="font-weight-bold">{{trans('lang.status')}}
                                                        :</label>
                                                    @if($user->status=="yes")
                                                        <span class="badge badge-success">Enabled</span>
                                                    @else
                                                        <span class="badge badge-warning">Disabled</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="col-group">
                                                    <label for="" class="font-weight-bold">{{trans('lang.created_at')}}
                                                        :</label>
                                                    <span class="date">{{ date('d F Y',strtotime($user->created_at))}}</span>
                                                    <span class="time">{{ date('h:i A',strtotime($user->created_at))}}</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="col-group">
                                                    <label for="" class="font-weight-bold">{{trans('lang.edited')}}
                                                        :</label>
                                                    @if($user->updated_at!='0000-00-00 00:00:00')
                                                        <span class="date">{{ date('d F Y',strtotime($user->updated_at))}}</span>
                                                        <span class="time">{{ date('h:i A',strtotime($user->updated_at))}}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{--<div class="col-md-6">
                                                <div class="col-group">
                                                    <label for=""
                                                           class="font-weight-bold">{{trans('lang.wallet_balance')}}
                                                        :</label>
                                                    <span>{{$currency->symbole." ".number_format($user->amount,$currency->decimal_digit)}}</span>
                                                </div>
                                            </div>--}}

                                            <div class="col-md-12">
                                                <div class="col-group-btn">
                                                    @if ($user->status=="no")
                                                        <a href="{{route('dispatcher-users.changestatus', ['id' => $user->id])}}"
                                                           class="btn btn-success btn-sm" data-toggle="tooltip"
                                                           data-original-title="Activate">{{trans('lang.enable_account')}}
                                                            <i class="fa fa-check"></i> </a>
                                                    @else
                                                        <a href="{{route('dispatcher-users.changestatus', ['id' => $user->id])}}"
                                                           class="btn btn-warning btn-sm" data-toggle="tooltip"
                                                           data-original-title="Activate"> Disable account <i
                                                                    class="fa fa-check"></i> </a>
                                                    @endif
                                                </div>
                                            </div>


                                        </div>

                                    </div>

                                    <div role="tabpanel"
                                         class="tab-pane {{ Request::get('tab') == 'rides' ? 'active' : '' }}"
                                         id="rides">
                                        @if(count($rides) > 0)
                                            <div class="table-responsive">
                                                <table class="display nowrap table table-hover table-striped table-bordered table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>{{trans('lang.ride_id')}}</th>
                                                        <th>{{trans('lang.pickup_location')}}</th>
                                                        <th>{{trans('lang.dropup_location')}}</th>
                                                        <th>{{trans('lang.status')}}</th>
                                                        <th>{{trans('lang.created')}}</th>
                                                        <th>{{trans('lang.actions')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="append_list12">
                                                    @foreach($rides as $ride)
                                            <tr>
                                                <td><a href="{{route('ride.show', ['id' => $ride->id])}}">{{ $ride->id}}</a></td>
                                                <td>{{ $ride->depart_name}}</td>
                                                <td>{{ $ride->destination_name}}</td>

                                                <td>  @if($ride->statut=='new') 
                                                <span class="badge badge-primary">{{ trans('lang.new')}}</span>
                                                      @elseif($ride->statut=='on ride')
                                                      <span class="badge badge-warning"> {{ trans('lang.on_ride')}}</span>
                                                      @elseif($ride->statut=='confirmed') 
                                                      <span class="badge badge-success">{{ trans('lang.confirmed')}}</span>
                                                      @elseif($ride->statut=='canceled') 
                                                      <span class="badge badge-danger">{{ trans('lang.canceled')}}</span>
                                                      @elseif($ride->statut=='completed')
                                                      <span class="badge badge-success"> {{ trans('lang.completed')}}</span>
                                                      @elseif($ride->statut=='rejected')
                                                      <span class="badge badge-danger"> {{ trans('lang.rejected')}}</span>
                                                      @elseif($ride->statut=='driver_rejected') 
                                                      <span class="badge badge-danger">{{ trans('lang.driver_rejected')}}</span>
                                                      @endif</td>
                                                <td class="dt-time">

                                                        <span class="date">{{ date('d F Y',strtotime($ride->creer))}}</span>
                                                        <span class="time">{{ date('h:i A',strtotime($ride->creer))}}</span>

                                                </td>
                                                <td class="action-btn">
                                                    <a href="{{route('ride.show', ['id' => $ride->id])}}" class=""
                                                        data-toggle="tooltip" data-original-title="Details"><i
                                                          class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <nav aria-label="Page navigation example" class="custom-pagination">
                                    {{$rides->appends(request()->query())->links()}}
                                </nav>
                                {{ $rides->links('pagination.pagination') }}
                                            </div>
                                        @else
                                            <p>
                                            <center>No results found.</center></p>
                                        @endif
                                    </div>


                                    <div role="tabpanel"
                                         class="tab-pane {{ Request::get('tab') == 'transactions' ? 'active' : '' }}"
                                         id="transactions">
                                        @if(count($transactions) > 0)
                                            <div class="table-responsive">
                                                <table class="display nowrap table table-hover table-striped table-bordered table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>{{trans('lang.transaction_id')}}</th>
                                                        <th>{{trans('lang.amount')}}</th>
                                                        <th>{{trans('lang.date')}}</th>
                                                        <th>{{trans('lang.payment_method')}}</th>
                                                        <th>{{trans('lang.status')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="append_list12">
                                                    @foreach($transactions as $transaction)
                                                        <tr>
                                                            <td>{{ $transaction->id}}</td>
                                                            <td>{{ $currency->symbole." ".number_format($transaction->amount,$currency->decimal_digit)}}</td>
                                                            <td>{{ date('d F Y h:i A',strtotime($transaction->creer))}}</td>
                                                            <td>{{ $transaction->payment_method}}</td>
                                                            <td>
                                                                @if($transaction->deduction_type == 1)
                                                                    <span class="badge badge-success">Credit<span>
			                                            @else
                                                                                <span class="badge badge-warning">Debit<span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                <nav aria-label="Page navigation example" class="custom-pagination">
                                                    {{ $transactions->appends(['tab'=>'transactions'])->links() }}
                                                </nav>
                                            </div>
                                        @else
                                            <p>
                                            <center>No results found.</center></p>
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
@endsection
