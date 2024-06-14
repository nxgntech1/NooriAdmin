@extends('layouts.app')

@section('content')
@php
$cityToCountry= file_get_contents(asset('tz-cities-to-countries.json'));
$cityToCountry=json_decode($cityToCountry,true);
$cityToCountry=array_unique($cityToCountry);
@endphp

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.tax_edit')}}</h3>
        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{!! route('tax') !!}">{{trans('lang.administration_tools_tax')}}</a>
                </li>
                <li class="breadcrumb-item active">{{trans('lang.tax_edit')}}</li>
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
                        <form action="{{route('tax.update',$Tax->id)}}" method="post" enctype="multipart/form-data"
                            id="create_driver">
                            @csrf
                            @method("PUT")

                            <div class="row restaurant_payout_create">
                                <div class="restaurant_payout_create-inner">
                                    <fieldset>
                                        <legend>{{trans('lang.tax_edit')}}</legend>

                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.label')}}</label>
                                            <div class="col-7">
                                                <input type="text" value="{{$Tax->libelle}}" class="form-control libelle" name="libelle">

                                            </div>
                                        </div>

                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.tax_table')}}</label>
                                            <div class="col-7">
                                                <input type="text" value="{{$Tax->value}}" class="form-control tax" name="tax">

                                            </div>
                                        </div>


                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.type')}}</label>
                                            <div class="col-7">
                                                <select class="form-control commission_type" name="type">
                                                    <option value="Percentage" {{($Tax->type=="Percentage")?"selected":""}}>{{trans("lang.percentage")}}</option>
                                                    <option value="Fixed" {{($Tax->type=="Fixed")?"selected":""}}>{{trans("lang.fix")}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row width-50">
                                            <label class="col-3 control-label">{{trans('lang.country')}}</label>
                                            <div class="col-7">
                                                <select name="country" class="form-control country">

                                                    @foreach($cityToCountry as $key=>$value)

                                                    <option value="{{$value}}" {{($value==$Tax->country)?"selected":""}}>{{$value}}</option>

                                                    @endforeach


                                                </select>

                                            </div>
                                        </div>

                                        <div class="form-group row width-50">
                                            <div class="form-check">
                                                <input type="checkbox" class="user_active" id="status" name="statut"
                                                    value="yes" {{$Tax->statut=="yes" ? "checked" : ""}}>
                                                <label class="col-3 control-label"
                                                    for="status">{{trans('lang.status')}}</label>

                                            </div>
                                        </div>

                                </div>



                                </fieldset>


                            </div>
                    </div>


                    <div class="form-group col-12 text-center btm-btn">
                        <button type="submit" class="btn btn-primary  create_user_btn"><i class="fa fa-save"></i> {{
                            trans('lang.save')}}</button>
                        <a href="{!! route('tax') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{
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
<script type="text/javascript">
    $('.country').select2();
</script>

@endsection