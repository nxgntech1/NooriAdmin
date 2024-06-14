@extends('layouts.app')

@section('content')


<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-themecolor">{{trans('lang.cms_plural')}}</h3>
		</div>

		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
				<li class="breadcrumb-item"><a href= "{!! route('cms') !!}" >{{trans('lang.cms_plural')}}</a></li>
				<li class="breadcrumb-item active">{{trans('lang.page_edit')}}</li>
			</ol>
		</div>
   </div> 

		<div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card pb-4">

			<div class="card-body">
				
				<div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">{{trans('lang.processing')}}</div>
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
        <form method="post" action="{{ route('cms.updateCms',$cmss->cms_id) }}" enctype="multipart/form-data">
          @csrf
          @method("PUT")
      <div class="row restaurant_payout_create">
        <div class="restaurant_payout_create-inner">

          <fieldset>
            <legend>{{trans('lang.page_edit')}}</legend>
            <div class="form-group row width-100">
              <label class="col-3 control-label">{{trans('lang.page_name')}}</label>
              <div class="col-7">
                <input type="text" class="form-control" name="cms_name" value="{{$cmss->cms_name}}" id="name"  onkeyup="return seo_url(this.value)">
                <div class="form-text text-muted">{{ trans("lang.page_name") }}</div>
              </div>
            </div>
            
            <div class="form-group row width-100">
              <label class="col-3 control-label">{{trans('lang.page_slug')}}</label>
              <div class="col-7">
                <input type="text" class="form-control" name="cms_slug" value="{{$cmss->cms_slug}}" id="cms_slug" onChange="this.value=this.value.toLowerCase();">
                <!--<div class="form-text text-muted">{{ trans("lang.page_slug") }}</div>-->
                <div class="form-text text-muted slug-info">http://yoursite.com/{{$cmss->cms_slug}}</div>
                <input type="hidden" id="total_slug" value="0" />
              </div>
            </div>

            <div class="form-group row width-100">
              <label class="col-3 control-label">{{trans('lang.page_description')}}</label>
              <div class="col-7">
                <textarea class="form-control col-7" name="cms_desc" id="description">{{$cmss->cms_desc}}</textarea>
                <div class="form-text text-muted">{{trans('lang.page_description')}}</div>
              </div>
            </div>

            <div class="form-group row width-100">
              <div class="form-check">
                @if ($cmss->cms_status === "on")
                   <input type="checkbox" class="cms_enabled" id="cms_enabled" name="cms_status" checked="checked">
                @elseif ($cmss->cms_status === "Publish")
                   <input type="checkbox" class="cms_enabled" id="cms_enabled" name="cms_status" checked="checked">
                
                @else
                   <input type="checkbox" class="cms_enabled" id="cms_enabled" name="cms_status" >
                @endif
                
                <label class="col-3 control-label" for="cms_enabled">{{trans('lang.status')}}</label>
              </div>
            </div>
          </fieldset>

        </div>
      </div>
    </div>
    <div class="form-group col-12 text-center btm-btn" >
      <button type="submit" class="btn btn-primary  save_user_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
      <a href="{!! route('cms') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
    </div>

</div>
</form>
</div>
</div>
</div>
</div>

@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>

        // $('#description').summernote();

          $('#description').summernote({
	        height: 400,
	        width: 1000,
	        toolbar: [
	    	    ['style', ['bold', 'italic', 'underline', 'clear']],
			    ['font', ['strikethrough', 'superscript', 'subscript']],
			    ['fontsize', ['fontsize']],
			    ['color', ['color']],
			    ['forecolor', ['forecolor']],
			    ['backcolor', ['backcolor']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['height', ['height']],
			    ['view', ['fullscreen', 'codeview', 'help']],
	  		]
	    });

         function seo_url(str){
            str = str.replace(/[^a-zA-Z0-9]+/g,'-').toLowerCase();
		$("#cms_slug").val(str);
            $(".slug-info").text('http://yoursite.com/'+str);
            checkSlug();         
          }
          
         async function checkSlug(){
        	var cms_slug = $("#cms_slug").val();
        	var pages = await ref.where('cms_slug','==',cms_slug).get();
        	$("#total_slug").val(pages.docs.length);
        }

</script>
@endsection