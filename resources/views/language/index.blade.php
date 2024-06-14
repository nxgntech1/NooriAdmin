@extends('layouts.app')

<?php
use Illuminate\Pagination\Paginator;
?>

@section('content')
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.language')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">languages</li>
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
                        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">{{trans('lang.processing')}}
                        </div>
                        <div class="userlist-topsearch d-flex mb-3">
                            <div class="userlist-top-left">
                            <a class="nav-link do_not_create" href="{{ route('language.create') }}"><i class="fa fa-plus mr-2"></i>{{trans('lang.language_create')}}</a>
                            </div>
                        <div id="users-table_filter" class="ml-auto">
                          <label>{{ trans('lang.search_by')}}
                            <div class="form-group mb-0">
                                <form action="{{ route('language') }}" method="get">
                                    @if(isset($_GET['selected_search']) &&  $_GET['selected_search'] != '')
                                    <?php //dd($_GET['selected_search']);?>
                                    <select name="selected_search" id="selected_search" class="form-control input-sm">

                                    <option value="prenom" @if ($_GET['selected_search']=='language')
                                    selected="selected" @endif >{{ trans('lang.language')}}</option>

                                    </select>
                                     @else
                                    <select name="selected_search" id="selected_search" class="form-control input-sm">
                                    <option value="prenom">{{ trans('lang.language')}}</option>

                                    </select>
                                    @endif
                                        <div class="search-box position-relative">
                                            @if(isset($_GET['search']) &&  $_GET['search'] != '')
                                            <input type = "text" class="search form-control" name="search" id = "search" value="{{$_GET['search']}}">
                                            @else
                                            <input type = "text" class="search form-control" name="search" id = "search">
                                            @endif
                                            <button type="submit" class="btn-flat position-absolute"><i class="fa fa-search"></i></button>
                                            <a class="btn btn-warning btn-flat" href="{{url('language')}}">Clear</a>
                                        </div>
                                </form>
                            </div>
                          </label>
                        </div>
                    </div>
                        <div class="table-responsive m-t-10">
                        <div class="error_top"></div>
									@if($errors->any())
							<div class="alert alert-danger" style="display:none;">
								<ul>
									@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
							@endif
                         
                            <table id="example24" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{trans('lang.extra_image')}}</th>
                                        <th >{{trans('lang.language')}}</th>
                                        <th >{{trans('lang.code')}}</th>
                                        <th >{{trans('lang.status')}}</th>
                                        <th>{{trans('lang.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody id="append_list12">
                                    @foreach($language as $customer)
                                        <tr>
                                        @if (file_exists(public_path('assets/images/flags'.'/'.$customer->flag)) && !empty($customer->flag))
                                            <td><img class="rounded" style="width:50px" src="{{asset('assets/images/flags').'/'.$customer->flag}}" alt="image"></td>
                                        @else
                                        <td><img class="rounded" style="width:50px" src="{{asset('assets/images/placeholder_image.jpg')}}" alt="image"></td>

                                        @endif
                                        <td>{{ $customer->language}}</td>
                                        <td>{{ $customer->code}}</td>
                                        <td>
                                            @if ($customer->status=="true")
                                                        <label class="switch"><input type="checkbox" checked id="{{$customer->id}}" name="publish"><span class="slider round"></span></label>
                                                    @else
                                                        <label class="switch"><input type="checkbox"  id="{{$customer->id}}" name="publish"><span class="slider round"></span></label>
                                                    @endif
                                        </td>
                                        <td class="action-btn"><a href="{{route('language.edit', ['id' => $customer->id])}}" class="do_not_edit"><i class="fa fa-edit"></i></a><a id="'+val.id+'" class="do_not_delete" name="user-delete" href="{{route('language.delete', ['id' => $customer->id])}}"><i class="fa fa-trash"></i></a>
                                         <!-- <a href="{{route('users.show', ['id' => $customer->id])}}" class="" data-toggle="tooltip" data-original-title="Details"><i class="fa fa-ellipsis-h"></i></a></td> -->
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <nav aria-label="Page navigation example" class="custom-pagination">
                                 {{ $language->appends(request()->query())->links() }}
                            </nav>
{{ $language->links('pagination.pagination') }}
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

<script type="text/javascript">


$(document).ready(function() {
 $(".shadow-sm").hide();
})
/* toggal publish action code start*/ 
var status = $("input[name='publish']").val();
        
  
    $(document).on("click", "input[name='publish']", function (e) {
        
       var ischeck = $(this).is(':checked');
       var id = this.id;
       var url = "{{ route('language.switch') }}";
       
       $.ajax({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           url: url,
           method: "POST",
           data: {'ischeck': ischeck, 'id': id},
           success: function (data) {
               console.log(data.error);
               if(data.error){
                   $(".error_top").show();
                   $(".error_top").html("");
                   $(".error_top").after("<div class='alert alert-danger'><ul><li>"+data.error+"</li></ul></div>");
               }
               window.location.reload();

           },
           error: function(response) {
              
                   },
       });

   });

/*toggal publish action code end*/
</script>

@endsection
