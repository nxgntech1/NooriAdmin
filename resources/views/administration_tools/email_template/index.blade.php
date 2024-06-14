@extends('layouts.app')

@section('content')
<div class="page-wrapper">

    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.email_template_plural')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{trans('lang.email_template_plural')}}
                </li>
            </ol>
        </div>

    </div>

    <div class="container-fluid">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        <div class="table-responsive m-t-10">

                            <table id="example24"
                                class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                cellspacing="0" width="100%">

                                <thead>

                                    <tr>
                                        <th>{{trans("lang.type")}}</th>
                                        <th>{{trans('lang.subject')}}</th>
                                        <th>{{trans('lang.created')}}</th>
                                        <th>{{trans('lang.actions')}}</th>
                                    </tr>

                                </thead>

                                <tbody id="append_list1">

                                    @foreach($templates as $template)
                                    <tr>

                                        <td>
                                            @if($template->type=="payment_receipt")
                                            {{trans("lang.payment_receipt")}}
                                            @elseif($template->type=="wallet_topup"){{trans("lang.wallet_topup")}}
                                            @elseif($template->type=="payout_approve_disapprove"){{trans("lang.payout_approve_disapprove")}}
                                            @elseif($template->type=="payout_request"){{trans("lang.payout_request")}}
                                            @elseif($template->type=="new_registration"){{trans("lang.new_registration")}}
                                            @elseif($template->type=="reset_password"){{trans("lang.reset_password")}}
                                            @endif
        
                                        </td>
                                        <td class="address-td">{{ $template->subject}}</td>

                                        <td class="dt-time"><span class="date">{{ date('d F
                                                Y',strtotime($template->created_at))}}</span>
                                            <span class="time">{{ date('h:i A',strtotime($template->created_at))}}</span>
                                        </td>
                                        <td class="action-btn">
                                            <a href="{{route('email_template.edit', ['id' => $template->id])}}"
                                                class="do_not_edit"><i class="fa fa-edit"></i></a>
                                        </td>
                                    </tr>

                                    @endforeach
                                </tbody>

                            </table>


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