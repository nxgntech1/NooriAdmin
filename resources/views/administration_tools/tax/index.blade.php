@extends('layouts.app')

@section('content')
<div class="page-wrapper">

    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.administration_tools_tax')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.administration_tools')}}</li>
                <li class="breadcrumb-item active">{{trans('lang.administration_tools_tax')}}</li>
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
                                <a class="nav-link do_not_create" href="{{ route('tax.create')}}"><i
                                        class="fa fa-plus mr-2"></i>{{trans('lang.tax_create')}}</a>
                            </div>
                            <div id="users-table_filter" class="ml-auto">
                                <label>{{ trans('lang.search_by')}}
                                    <div class="form-group mb-0">

                                        <form action="{{ route('tax') }}" method="get">
                                            @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                            <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                                <option value="libelle" @if ($_GET[ 'selected_search' ]=='libelle' )
                                                    selected="selected" @endif>{{ trans('lang.label')}}</option>
                                                <option value="country" @if ($_GET[ 'selected_search' ]=='country' )
                                                    selected="selected" @endif>{{ trans('lang.country')}}
                                                </option>
                                                 <option value="tax" @if ($_GET[ 'selected_search' ]=='tax' )
                                                    selected="selected" @endif>{{ trans('lang.tax_table')}}
                                                </option>
                                                <option value="type" @if ($_GET[ 'selected_search' ]=='type' )
                                                    selected="selected" @endif>{{ trans('lang.type')}}
                                                </option>


                                            </select>
                                            @else
                                            <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                                <option value="libelle">{{ trans('lang.label')}}</option>
                                                <option value="symbole">{{ trans('lang.country')}}</option>
                                            </select>
                                            @endif
                                            <div class="search-box position-relative">
                                                @if(isset($_GET['search']) && $_GET['search'] != '')
                                                <input type="text" class="search form-control" name="search" id="search"
                                                    value="{{$_GET['search']}}">
                                                @else
                                                <input type="text" class="search form-control" name="search"
                                                    id="search">
                                                @endif
                                                <button type="submit" class="btn-flat position-absolute"><i
                                                        class="fa fa-search"></i></button>
                                                <a class="btn btn-warning btn-flat"
                                                    href="{{url('administration_tools/tax')}}">Clear</a>
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
                                                    class="do_not_delete" href="javascript:void(0)"><i
                                                        class="fa fa-trash"></i> All</a></label>
                                        </th>
                                        <th>{{trans('lang.label')}}</th>
                                        <th>{{trans('lang.tax_table')}}</th>
                                        <th>{{trans('lang.type')}}</th>
                                        <th>{{trans('lang.status')}}</th>
                                        <th>{{trans('lang.country')}}</th>
                                        <th>{{trans('lang.actions')}}</th>

                                    </tr>

                                </thead>

                                <tbody id="append_list1">
                                    @if(count($taxes) > 0)
                                    @foreach($taxes as $tax)
                                    <tr>
                                        <td class="delete-all"><input type="checkbox" id="is_open_{{$tax->id}}"
                                                class="is_open" dataid="{{$tax->id}}"><label
                                                class="col-3 control-label" for="is_open_{{$tax->id}}"></label>
                                        </td>
                                        <td>{{ $tax->libelle}}</td>
                                        <td>{{ $tax->value}}</td>
                                        <td>{{ $tax->type}}</td>

                                        <td>@if ($tax->statut=="yes") 
                                            <label class="switch"><input type="checkbox"
                                                    checked value="{{$tax->statut}}" id="{{$tax->id}}"
                                                    name="publish" class="switchToggal"><span class="slider round"></span></label>
                                            @else <label class="switch"><input type="checkbox" id="{{$tax->id}}"
                                                    name="publish" value="{{$tax->statut}}" class="switchToggal"><span
                                                    class="slider round"></span></label><span>
                                                @endif
                                            </td>
                                        <td>{{ $tax->country}}</td>

                                        <td class="action-btn">
                                            <a href="{{route('tax.edit', ['id' => $tax->id])}}"
                                                class="do_not_edit"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('tax.delete', ['id' => $tax->id])}}"
                                                class="do_not_delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="11" align="center">{{trans("lang.no_result")}}</td>
                                    </tr>
                                    @endif

                                </tbody>

                            </table>

                            <nav aria-label="Page navigation example" class="custom-pagination">
                                {{$taxes->appends(request()->query())->links()}}
                            </nav>
                            {{ $taxes->Links('pagination.pagination') }}
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

    $(document).ready(function () {
        $(".shadow-sm").hide();
    })

    /* toggal publish action code start*/
    $(document).on("click", "input[name='publish']", function (e) {
        
        var ischeck = $(this).is(':checked');
        var id = this.id;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '../tax/switch',
            method: "POST",
            data: { 'ischeck': ischeck, 'id': id },
            success: function (data) {

            },
        });

    });

    /*toggal publish action code end*/

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
                    var url = "{{url('administration_tools/tax/delete', 'id')}}";
                    url = url.replace('id', arrayUsers);

                    $(this).attr('href', url);
                }
            } else {
                alert('Please Select Any One Record .');
            }
        });


</script>

@endsection