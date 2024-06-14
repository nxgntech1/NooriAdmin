@extends('layouts.app')

@section('content')
        <div class="page-wrapper">

            <div class="row page-titles">

                <div class="col-md-5 align-self-center">

                    <h3 class="text-themecolor">{{trans('lang.administration_tools_country')}}</h3>

                </div>

                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('lang.administration_tools')}}</li>
                        <li class="breadcrumb-item active">{{trans('lang.administration_tools_country')}}</li>
                    </ol>
                </div>

            </div>



            <div class="container-fluid">

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                        <div class="card-body">
                        <div class="userlist-topsearch d-flex mb-3">
                                 <div class="userlist-top-left">
                                 <a class="nav-link do_not_create" href="{{route('country.create')}}" ><i class="fa fa-plus mr-2"></i>{{trans('lang.country_create')}}</a>
                                </div>
                            <div id="users-table_filter" class="ml-auto">
                              <label>{{ trans('lang.search_by')}}
                                <div class="form-group mb-0">

                                    <form action="{{ route('country') }}" method="get">
                                        @if(isset($_GET['selected_search']) &&  $_GET['selected_search'] != '')
                                        <select name="selected_search" id="selected_search" class="form-control input-sm">
                                        <option value="libelle" @if ($_GET['selected_search']=='libelle')
                                    selected="selected" @endif>{{ trans('lang.Name')}}</option>
                                        <option value="code" @if ($_GET['selected_search']=='code')
                                    selected="selected" @endif>{{ trans('lang.code')}}</option>
                                        </select>
                                        @else
                                        <select name="selected_search" id="selected_search" class="form-control input-sm">
                                        <option value="libelle">{{ trans('lang.Name')}}</option>
                                        <option value="code">{{ trans('lang.code')}}</option>
                                    </select>
                                    @endif
                                    <div class="search-box position-relative">
                                        @if(isset($_GET['search']) &&  $_GET['search'] != '')
                                        <input type = "text" class="search form-control" name="search" id = "search" value="{{$_GET['search']}}">
                                        @else
                                        <input type = "text" class="search form-control" name="search" id = "search">
                                        @endif
                                        <button type="submit" class="btn-flat position-absolute"><i class="fa fa-search"></i></button>
                                        <a class="btn btn-warning btn-flat" href="{{url('administration_tools/country')}}">Clear</a>
                                    </div>
                                    </form>
                                </div>
                               </label>
                            </div>
                        </div>

                                <div class="table-responsive m-t-10">

                                    <table id="example24" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">

                                        <thead>

                                            <tr>

                                                <th>{{trans('lang.country_name')}}</th>
                                                <th>{{trans('lang.country_code')}}</th>
                                                <th>{{trans('lang.country_status')}}</th>
                                                <th>{{trans('lang.country_created')}}</th>
                                                <th>{{trans('lang.country_modified')}}</th>
                                                <th>{{trans('lang.actions')}}</th>

                                            </tr>

                                        </thead>

                                        <tbody id="append_list1">
                                         @if(count($countries) > 0)
                                            @foreach($countries as $country)
                                            <tr>
                                                <td>{{ $country->libelle}}</td>
                                                <td>{{ $country->code}}</td>
                                                <td>
                                                    @if ($country->statut=="yes")
                                                        <label class="switch"><input type="checkbox" checked id="{{$country->id}}" name="publish"><span class="slider round"></span></label>
                                                    @else
                                                        <label class="switch"><input type="checkbox"  id="{{$country->id}}" name="publish"><span class="slider round"></span></label>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="date">{{ date('d F Y',strtotime($country->creer))}}</span>
                                                    <span class="time">{{ date('h:i A',strtotime($country->creer))}}</span>
                                                </td>
                                                <td>
                                                    @if($country->modifier!='0000-00-00 00:00:00')
                                                    <span class="date">{{ date('d F Y',strtotime($country->modifier))}}</span>
                                                    <span class="time">{{ date('h:i A',strtotime($country->modifier))}}</span>
                                                    @endif
                                                </td>
                                                <td class="action-btn"><a href="{{route('country.show', ['id' => $country->id])}}" class="" data-toggle="tooltip" data-original-title="View détails"><i class="fa fa-eye"></i></a>
                                                  <a href="{{ route('country.edit',$country->id) }}" class="do_not_edit"><i class="fa fa-edit"></i></a>
                                                </td>
                                               {{-- <div class="modal fade" id="exampleModal_{{$country->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">{{trans('lang.administration_tools_country')}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>
                                              <div class="modal-body">
                                              <form method="post" action="{{ route('country.update',$country->id) }}" enctype="multipart/form-data">
                                              @csrf
                                              @method("PUT")
                                              <div class="form-group">
                                                <label>{{trans('lang.country_name')}}</label>
                                                <input type="text" class="form-control" name="libelle" value="{{$country->libelle}}">
                                                </div>
                                                <div class="form-group">
                                                 <label>{{trans('lang.country_code')}}</label>
                                                <input type="text" class="form-control" name="code" value="{{$country->code}}">
                                                </div>
                                                <div class="form-group">

                                                <!-- <div class="form-check">

                                                    @if ($country->statut === "yes")
                                                    <input type="checkbox" id="status" name="status" checked="checked">
                                                        @else
                                                        <input type="checkbox" id="status" name="status">
                                                        @endif
                                                    <label class="col-3 control-label" for="user_active">{{trans('lang.status')}}</label>
                                                </div>
               -->
            </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.close')}}</button>
                                                <button type="submit" class="btn btn-primary save_changes">{{trans('lang.save_changes')}}</button>
                                              </div>
                                            </form>
                                            </div>
                                          </div>
                                        </div>--}}
                                            </tr>
                                            @endforeach
                                              @else
                                	<tr><td colspan="11" align="center">{{trans("lang.no_result")}}</td></tr>
                                @endif

                                        </tbody>

                                    </table>

                                    <nav aria-label="Page navigation example" class="custom-pagination">
                                    {{$countries->appends(request()->query())->links()}}
                                    </nav>
                                {{ $countries->Links('pagination.pagination') }}
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
$(document).on("click", "input[name='publish']", function (e) {
  
var ischeck = $(this).is(':checked');
var id = this.id;
console.log(id);
$.ajax({
  headers: {
 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
   url : '../country/switch',
   method:"POST",
   data:{'ischeck':ischeck,'id':id},
   success: function(data){

   },
});

});

</script>

@endsection
