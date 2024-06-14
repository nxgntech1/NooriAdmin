@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.create_notification')}}</h3>
        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('notifications') !!}">{{trans('lang.notification')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.create_notification')}}</li>
            </ol>
        </div>
    </div>

    <div class="card-body">

        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
            {{trans('lang.processing')}}
        </div>
        <div class="error_top"></div>
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{route('notifications.send')}}" method="post" enctype="multipart/form-data" id="create_driver">
            @csrf
            <div class="row restaurant_payout_create">
                <div class="restaurant_payout_create-inner">
                    <fieldset>
                        <legend>{{trans('lang.create_notification')}}</legend>

                        <div class="form-group row width-100">
                            <label class="col-3 control-label">{{trans('lang.title')}}</label>
                            <div class="col-7">
                                <input type="text" class="form-control title" name="title">
                                <div class="form-text text-muted">
                                    {{ trans("lang.notification_title_help") }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row width-100">
                            <label class="col-3 control-label">{{trans('lang.message')}}</label>
                            <div class="col-7">
                            <textarea name="message" class="form-control message" id="message" cols="30" rows="10">{{Request::old('message')}}</textarea>
                                <div class="form-text text-muted">
                                    {{ trans("lang.notification_message_help") }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row width-50">
                            <div class="form-check">
                                <input type="checkbox" class="" name="send_to[]" id="customer" value="customer">
                                <label class="col-3 control-label" for="customer">{{trans('lang.sendto_customer')}}</label>

                                <input type="checkbox" class="" name="send_to[]" id="driver" value="driver">
                                <label class="col-3 control-label" for="driver">{{trans('lang.sendto_driver')}}</label>

                            </div>
                        </div>

                    </fieldset>


                </div>
            </div>


            <div class="form-group col-12 text-center btm-btn">
                <button type="submit" class="btn btn-primary  create_user_btn"><i class="fa fa-save"></i> {{
                    trans('lang.save')}}</button>
                <a href="{!! route('notifications') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{
                    trans('lang.cancel')}}</a>
            </div>

        </form>

    </div>

    @endsection

    @section('scripts')



    @endsection