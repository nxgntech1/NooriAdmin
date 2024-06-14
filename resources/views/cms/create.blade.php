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
        <li class="breadcrumb-item active">{{trans('lang.create_page')}}</li>
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
       
        <form action="{{ route('cms.store') }}" method="post"  enctype="multipart/form-data" id="">
          @csrf

          <div class="error_top" style="display:none"></div>
          <div class="row restaurant_payout_create">
              <div class="restaurant_payout_create-inner">
                <fieldset>
                
                  <legend>{{trans('lang.create_page')}}</legend>
                  
                  
                        <div class="form-group row width-100">
                          <label class="col-3 control-label">{{trans('lang.page_name')}}</label>
                          <div class="col-7">           
                            <input type="text" class="form-control" value="{{ Request::old('cms_name') }}" name="cms_name" id="cms_name" onkeyup="return seo_url(this.value)">
                            <div class="form-text text-muted">{{trans('lang.add_page_name')}}</div>
                          </div>
                        </div>
                       
                        <div class="form-group row width-100">
                          <label class="col-3 control-label">{{trans('lang.page_slug')}}</label>
                          <div class="col-7">
                            <input type="text" class="form-control" name="cms_slug" id="cms_slug" value="{{ Request::old('cms_slug') }}" onChange="this.value=this.value.toLowerCase();">
                            <!-- <div class="form-text text-muted"></div> -->
                            <div class="form-text text-muted slug-info"></div>
                            <input type="hidden" id="total_slug" value="0" />
                          </div>                
                        </div>   

                        <div class="form-group row width-100">
                          <label class="col-3 control-label">{{trans('lang.page_description')}}</label>
                          <div class="col-7">
                          <textarea class="form-control col-7 summernote" name="cms_desc" id="description">{{ Request::old('cms_desc') }}</textarea>
                            <div class="form-text text-muted">{{trans('lang.add_page_description')}}</div>
                          </div>    
                        </div>

                        <div class="form-group row width-100">
                            <div class="form-check">
                                <input type="checkbox" class="publish" id="publish" name="cms_status" value="{{ Request::old('cms_status') }}">
                                <label class="col-3 control-label" for="publish">{{trans('lang.status')}}</label>
                            </div>
                        </div>

                </fieldset>
                      
                   </div>              
              </div>
          </div>

          <div class="form-group col-12 text-center btm-btn">
            <button type="submit" class="btn btn-primary save_driver_btn" ><i class="fa fa-save"></i> {{ trans('lang.save')}}</button>
            <a href="{!! route('cms') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
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
            $(".slug-info").val(pages.docs.length);
            checkSlug();         
          }
          
         async function checkSlug(){
        	var cms_slug = $("#cms_slug").val();
        	var pages = await ref.where('cms_slug','==',cms_slug).get();
        	$("#total_slug").val(pages.docs.length);
        }

</script>
@endsection