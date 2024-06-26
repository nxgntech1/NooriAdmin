@extends('layouts.app')

@section('content')
<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.user_profile')}}</h3>
		</div>

		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
				<li class="breadcrumb-item"><a href= "{!! route('users') !!}" >{{trans('lang.user_profile')}}</a></li>
				<li class="breadcrumb-item active">{{trans('lang.user_edit')}}</li>
			</ol>
		</div>

</div>

 <div class="profile-form">
            @if (Session::has('message'))
                <div class="alert alert-error">{{Session::get('message')}}</div>
            @endif

            <div class="card-body">
                
                <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">Processing...</div>

                  <div class="column">
                      <form method="post" action="{{ route('users.profile.update',$user->id) }}" enctype="multipart/form-data">
                        @csrf
                   
                   <div class="row restaurant_payout_create">
                    <div class="restaurant_payout_create-inner">
                        <fieldset>     
                   <legend>Profile Details</legend> 
                   <div class="form-group row center">
                        <label class="col-3 control-label">{{trans('lang.profile_image')}}</label>
                        <div class="col-7">
                            <input type="file" class="" name="profileimage" id="profileimage"
                                value="{{Request::old('photo')}}" >
                        </div>
                        <div id="image_preview" style="display: none; padding-left: 15px;">
                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 control-label">{{trans('lang.user_name')}}</label>
                       <div class="col-7"> 
                        <input type="text" class=" col-6 form-control" name="name" value="<?php echo $user->name; ?>">
                        <div class="form-text text-muted">
                                {{ trans("lang.user_name_help") }}
                        </div>
                    </div>
                    </div>
                    <div class="form-group row width-50">
                        <label class="col-5 control-label">{{trans('lang.old_password')}}</label>
                       <div class="col-7"> 
                        <input type="password" class=" col-6 form-control" name="old_password" >
                        <div class="form-text text-muted">
                                {{ trans("lang.old_password_help") }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row width-50">
                        <label class="col-5 control-label">{{trans('lang.new_password')}}</label>
                        <div class="col-7"> 
                        <input type="password" class=" col-6 form-control" name="password" >
                        <div class="form-text text-muted">
                                {{ trans("lang.user_password_help") }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row width-50">
                        <label class="col-5 control-label">{{trans('lang.confirm_password')}}</label>
                       <div class="col-7"> 
                        <input type="password" class=" col-6 form-control" name="confirm_password" >
                        <div class="form-text text-muted">
                                {{ trans("lang.confirm_password_help") }}
                            </div>
                        </div>
                    </div>
                      <div class="form-group row width-50">
                        <label class="col-5 control-label">{{trans('lang.user_email')}}</label>
                        <div class="col-7"> 
                        <input type="text" class=" col-6 form-control" value="<?php echo $user->email; ?>" name="email">
                        <div class="form-text text-muted">
                                {{ trans("lang.user_email_help") }}
                            </div>
                        </div>
                    </div>
                   </fieldset> 
                  </div>
                  </div>
                </div>

        <div class="form-group col-12 text-center btm-btn" >
            <button type="submit" class="btn btn-primary  save_user_btn" id="save_user_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
            @if (Auth::check() && Auth::user()->hasRole('admin'))
            <a href="{!! route('dashboard') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
            @elseif(Auth::check() && Auth::user()->hasRole('user'))
            <a href="{!! route('userDashboard') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
            @endif
            
        </div>
     </form>

    </div>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
    $('#profileimage').on('change', function() {
        $('#image_preview').html('');
        var files = $(this)[0].files;

        if(files.length > 0) {
            for(var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image_preview').append('<img src="' + e.target.result + '" style="width:150px;height:auto;margin-right:10px" class="img-thumbnail">');
                    $('#image_preview').show();
                }
                reader.readAsDataURL(files[i]);
            }
        }
    });
    });
</script>

@endsection