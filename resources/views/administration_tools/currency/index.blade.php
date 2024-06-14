@extends('layouts.app')

@section('content')
<div class="page-wrapper">


    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.administration_tools_currency')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.administration_tools')}}</li>
                <li class="breadcrumb-item active">{{trans('lang.administration_tools_currency')}}</li>
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

                        <div class="userlist-topsearch d-flex mb-3">
                            <div class="userlist-top-left">
                                <a class="nav-link do_not_create" href="{{ route('currency.create')}}"><i class="fa fa-plus mr-2"></i>{{trans('lang.currency_create')}}</a>
                            </div>
                            <div id="users-table_filter" class="ml-auto">
                                <label>{{ trans('lang.search_by')}}
                                <div class="form-group mb-0">

                                    <form action="{{ route('currency') }}" method="get">
                                        @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="libelle" @if ($_GET[
                                            'selected_search']=='libelle')
                                            selected="selected" @endif>{{ trans('lang.Name')}}</option>
                                            <option value="symbole" @if ($_GET[
                                            'selected_search']=='symbole')
                                            selected="selected" @endif>{{ trans('lang.currency_symbol')}}</option>
                                        </select>
                                        @else
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="libelle">{{ trans('lang.Name')}}</option>
                                            <option value="symbole">{{ trans('lang.currency_symbol')}}</option>
                                        </select>
                                        @endif
                                        <div class="search-box position-relative">
                                            @if(isset($_GET['search']) && $_GET['search'] != '')
                                            <input type="text" class="search form-control" name="search" id="search"
                                                   value="{{$_GET['search']}}">
                                            @else
                                            <input type="text" class="search form-control" name="search" id="search">
                                            @endif

                                            <button type="submit" class="btn-flat position-absolute"><i
                                                        class="fa fa-search"></i></button>
                                            <a class="btn btn-warning btn-flat"
                                               href="{{url('administration_tools/currency')}}">Clear</a>
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
                            <table id="example24"
                                   class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                   cellspacing="0" width="100%">

                                <thead>

                                <tr>
                                  <th class="delete-all"><input type="checkbox" id="is_active"><label
                                             class="col-3 control-label" for="is_active"><a id="deleteAll"
                                                                                            class="do_not_delete"
                                                                                            href="javascript:void(0)"><i
                                                     class="fa fa-trash"></i> All</a></label></th>
                                    <th>{{trans('lang.currency_name')}}</th>
                                    <th>{{trans('lang.currency_symbol')}}</th>
                                    <th>{{trans('lang.currency_status')}}</th>

                                    <th>{{trans('lang.actions')}}</th>

                                </tr>

                                </thead>

                                <tbody id="append_list1">
                                 @if(count($currencies) > 0)
                                @foreach($currencies as $currency)
                                <tr>
                                  <td class="delete-all"><input type="checkbox"
                                                                 id="is_open_{{$currency->id}}"
                                                                 class="is_open"
                                                                 dataid="{{$currency->id}}"><label
                                               class="col-3 control-label"
                                               for="is_open_{{$currency->id}}"></label></td>
                                    <td>{{ $currency->libelle}}</td>
                                    <td>{{ $currency->symbole}}</td>
                                    <td>@if ($currency->statut=="yes") <label class="switch"><input type="checkbox"
                                                                                                    checked value="{{$currency->statut}}"
                                                                                                    id="{{$currency->id}}"
                                                                                                    name="publish" class="switchToggal"><span
                                                    class="slider round"></span></label>
                                        @else <label class="switch"><input type="checkbox" id="{{$currency->id}}"
                                                                           name="publish" value="{{$currency->statut}}" class="switchToggal"><span
                                                    class="slider round"></span></label><span>
                                      @endif
                                    </td>
                                    <!-- <td>
                                        @if ($currency->statut=="yes")
                                            <span class="badge badge-success">{{ $currency->statut }}<span>
                                        @else
                                            <span class="badge badge-warning">{{ $currency->statut }}<span>
                                        @endif
                                    </td>     -->

                                    <td class="action-btn"><a href="{{route('currency.show', ['id' => $currency->id])}}"
                                                              class="" data-toggle="tooltip"
                                                              data-original-title="View détails"><i
                                                    class="fa fa-eye"></i></a>
                                        <a href="{{route('edit_currency', ['id' => $currency->id])}}" class="do_not_edit"><i
                                                    class="fa fa-edit"></i></a>
                                        <a href="{{route('currency.delete', ['id' => $currency->id])}}" class="do_not_delete"><i
                                                                class="fa fa-trash"></i></a>
                                    </td>
                                    {{--
                                    <div class="modal fade" id="exampleModal_{{$currency->id}}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                        {{trans('lang.currency_plural')}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post"
                                                          action="{{ route('currency.update',$currency->id) }}"
                                                          enctype="multipart/form-data">
                                                        @csrf
                                                        @method("PUT")
                                                        <div class="form-group">
                                                            <label>{{trans('lang.currency_plural')}}</label>
                                                            <input type="text" class="form-control" name="libelle"
                                                                   value="{{$currency->libelle}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{trans('lang.currency_symbol')}}</label>
                                                            <input type="text" class="form-control" name="symbol"
                                                                   value="{{$currency->symbole}}">
                                                        </div>
                                                        <div class="form-group">


                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">{{trans('lang.close')}}
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">
                                                                {{trans('lang.save_changes')}}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        --}}
                                </tr>
                                @endforeach
                                    @else
                                	<tr><td colspan="11" align="center">{{trans("lang.no_result")}}</td></tr>
                                @endif

                                </tbody>

                            </table>

                            <nav aria-label="Page navigation example" class="custom-pagination">
                            {{$currencies->appends(request()->query())->links()}}
                            </nav>
{{ $currencies->Links('pagination.pagination') }}
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

<script>
     var status = $("input[name='publish']").val();
        
    /* toggal publish action code start*/
    $(document).on("click", "input[name='publish']", function (e) {
        
        var ischeck = $(this).is(':checked');
        var id = this.id;
        var url = "{{ route('currency.switch') }}";
        
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
        
         $("#example24 tr").each(function(){
        	$(this).find(".switch input").not('#'+id).prop('checked',false);
	     });

    });
    $("#is_active").click(function () {
        $("#example24 .is_open").prop('checked', $(this).prop('checked'));

    });
    $("#deleteAll").click(function () {
        if ($('#example24 .is_open:checked').length) {
            if (confirm('Are You Sure want to Delete Selected Data ?')) {
                var arrayUsers = [];
                $('#example24 .is_open:checked').each(function () {
                    var dataId = $(this).attr('dataId');
                    arrayUsers.push(dataId);

                });

                arrayUsers = JSON.stringify(arrayUsers);
                var url = "{{url('administration_tools/currency/delete', 'id')}}";
                url = url.replace('id', arrayUsers);

                $(this).attr('href', url);
            }
        } else {
            alert('Please Select Any One Record .');
        }
    });
    
</script>

@endsection
