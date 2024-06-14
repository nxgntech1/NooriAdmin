@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">

            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.drivers_payout_plural')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('/driversPayouts')}}">{{trans('lang.drivers_payout_plural')}}</a>
                    </li>
                    <li class="breadcrumb-item">{{trans('lang.drivers_payout_create')}}</li>
                </ol>
            </div>
        </div>

        <div class="card-body">
            <div id="data-table_processing" class="dataTables_processing panel panel-default"
                 style="display: none;">{{trans('lang.processing')}}</div>
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
            @if (Session::has('msg'))
                <div class="alert alert-danger">{{Session::get('msg')}}</div>
            @endif
            <form action="{{route('driversPayouts.store')}}" method="post"  enctype="multipart/form-data" id="create_driver">
                @csrf
            <div class="row restaurant_payout_create">
                <div class="restaurant_payout_create-inner">
                    <fieldset>
                        <legend>{{trans('lang.drivers_payout_create')}}</legend>

                        <div class="form-group row width-50">
                            <label class="col-4 control-label">{{ trans('lang.drivers_payout_driver_id')}}</label>
                            <div class="col-7">
                                <select id="select_restaurant" class="form-control" name="driverId">
                                    @foreach($driver as $value)
                                    <option value="{{$value->id}}">{{ $value->prenom }} {{$value->nom}}</option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted">
                                    {{ trans("lang.drivers_payout_driver_id_help") }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row width-50">
                            <label class="col-4 control-label">{{trans('lang.drivers_payout_amount')}}</label>
                            <div class="col-7">
                                <input type="number" class="form-control payout_amount" name="payout">
                                <div class="form-text text-muted">
                                    {{ trans("lang.drivers_payout_amount_placeholder") }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row width-100">
                            <label class="col-2 control-label">{{ trans('lang.restaurants_payout_note')}}</label>
                            <div class="col-12">
                                <textarea type="text" rows="7" class="form-control form-control payout_note" name="note"></textarea>
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-group col-12 text-center btm-btn">
                        <button type="submit" class="btn btn-primary save_restaurant_payout_btn"><i
                                    class="fa fa-save"></i> {{trans('lang.save')}}</button>
                        <a href="{!! route('driversPayouts') !!}" class="btn btn-default"><i
                                    class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
                    </div>
                </div>
            </div>
            </form>

        </div>



    </div>
    </div>


@endsection

@section('scripts')


@endsection
