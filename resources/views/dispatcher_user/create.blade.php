@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.dispatcher_user_create')}}</h3>
        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('dispatcher-users') !!}">{{trans('lang.dispatcher_user')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.dispatcher_user_create')}}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card pb-4">

                    <div class="card-body">

                        <div id="data-table_processing" class="dataTables_processing panel panel-default"
                             style="display: none;">
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
                        <form action="{{route('dispatcher-users.store')}}" method="post" enctype="multipart/form-data"
                              id="create_driver">
                            @csrf
                            <div class="row restaurant_payout_create">
                                <div class="restaurant_payout_create-inner">
                                    <fieldset>
                                        <legend>{{trans('lang.user_details')}}</legend>

                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.first_name')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control user_first_name"
                                                       name="first_name" value="{{ Request::old('first_name')}}">
                                                <div class="form-text text-muted">
                                                    {{ trans("lang.user_first_name_help") }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.last_name')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control user_last_name" name="last_name"
                                                       value="{{ Request::old('last_name')}}">
                                                <div class="form-text text-muted">
                                                    {{ trans("lang.user_last_name_help") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.user_phone')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control user_phone" name="phone"
                                                       value="{{ Request::old('phone')}}">
                                                <div class="form-text text-muted w-50">
                                                    {{ trans("lang.user_phone_help") }}
                                                </div>
                                            </div>

                                        </div>


                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.email')}}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control user_email" name="email"
                                                       value="{{ Request::old('email')}}">
                                                <div class="form-text text-muted">
                                                    {{ trans("lang.user_email_help") }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.password')}}</label>
                                            <div class="col-7">
                                                <input type="password" class="form-control user_password"
                                                       name="password" value="{{ Request::old('password')}}">
                                                <div class="form-text text-muted">
                                                    {{ trans("lang.user_password_help") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.confirm_password')}}</label>
                                            <div class="col-7">
                                                <input type="password" class="form-control confirm_password"
                                                       name="confirm_password"
                                                       value="{{ Request::old('confirm_password')}}">
                                                <div class="form-text text-muted">
                                                    {{ trans("lang.user_password_help") }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row width-100">
                                            <label class="col-1 control-label">{{trans('lang.profile_image')}}</label>
                                            <input type="file" class="col-6" name="profile_picture"
                                                   onchange="readURL(this);">
                                                <img class="rounded d-none" style="width:50px" id="uploding_image" src="#"
                                                     alt="image">

                                        </div>

                                        <div class="form-group row width-50">
                                            <div class="form-check">
                                                <input type="checkbox" class="user_active" id="user_active"
                                                       name="status">
                                                <label class="col-3 control-label" for="user_active">{{trans('lang.active')}}</label>

                                            </div>
                                        </div>

                                    </fieldset>

                                </div>
                            </div>


                            <div class="form-group col-12 text-center btm-btn">
                                <button type="submit" class="btn btn-primary  create_user_btn"><i
                                            class="fa fa-save"></i> {{ trans('lang.save')}}
                                </button>
                                <a href="{!! route('dispatcher-users') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{
                                    trans('lang.cancel')}}</a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>


    <script type="text/javascript">
        function readURL(input) {
            console.log(input.files);
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#uploding_image').removeClass('d-none');
                    $('#uploding_image').attr('src', e.target.result);


                }

                reader.readAsDataURL(input.files[0]);
            }
        }

    </script>
    @endsection
