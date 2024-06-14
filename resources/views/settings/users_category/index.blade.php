@extends('layouts.app')

@section('content')
<div class="page-wrapper">

    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.user_category')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <!-- <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li> -->
                <li class="breadcrumb-item active">{{trans('lang.user_category')}}</li>
            </ol>
        </div>

    </div>


    <div class="container-fluid">

        <div class="row">

            <div class="col-12">

                <div class="card">


                    <div class="card-body">

                        <div id="data-table_processing" class="dataTables_processing panel panel-default"
                             style="display: none;">{{trans('lang.processing')}}
                        </div>
                        <div class="userlist-topsearch d-flex mb-3">
                            <div class="userlist-top-left">
                                <a class="nav-link" href="javascript:void(0);" data-toggle="modal"
                                   data-target="#exampleModal"><i class="fa fa-plus mr-2"></i>{{trans('lang.create_user_category')}}</a>
                            </div>
                            <div id="users-table_filter" class="ml-auto">
                                <div class="form-group mb-0">
                                    <form action="{{ route('users_category') }}" method="get">
                                        @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="libelle">{{trans('lang.name')}}</option>

                                        </select>
                                        @else
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="libelle">{{trans('lang.name')}}</option>
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
                                            <!-- <input type="search" id="search" class="search form-control" placeholder="Search" aria-controls="users-table"></label>&nbsp;<button onclick="searchtext();" class="btn btn-warning btn-flat">Search</button>&nbsp; -->
                                            <!-- <button onclick="searchclear();" class="btn btn-warning btn-flat">Clear</button> -->
                                            <a class="btn btn-warning btn-flat"
                                               href="{{url('users_category')}}">Clear</a>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>

                        <div class="table-responsive m-t-10">

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
                                    <th>{{trans('lang.no')}}</th>
                                    <th>{{trans('lang.name')}}</th>
                                    <th>{{trans('lang.created')}}</th>
                                    <th>{{trans('lang.modified')}}</th>
                                    <th>{{trans('lang.actions')}}</th>

                                </tr>

                                </thead>

                                <tbody id="append_list1">

                                @foreach($userscategories as $userscategory)
                                <tr>
                                    <td class="delete-all"><input type="checkbox"
                                                                  id="is_open_{{$userscategory->id}}"
                                                                  class="is_open"
                                                                  dataid="{{$userscategory->id}}"><label
                                                class="col-3 control-label"
                                                for="is_open_{{$userscategory->id}}"></label></td>

                                    <td>{{ $userscategory->id}}</td>
                                    <td>{{ $userscategory->libelle}}</td>
                                    <td>{{ $userscategory->creer}}</td>
                                    <td>{{ $userscategory->modifier}}</td>
                                    <td class="action-btn"><a class="edit_type_userscategory" href="javascript:void(0);"
                                                              data-toggle="modal"
                                                              data-target="#exampleModal_{{$userscategory->id}}"><i
                                                    class="fa fa-edit"></i></a><a id="'+val.id+'" class="do_not_delete"
                                                                                  href="{{route('userscategory.delete', ['id' => $userscategory->id])}}"><i
                                                    class="fa fa-trash"></i></a></td>
                                    <div class="modal fade" id="exampleModal_{{$userscategory->id}}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                        {{trans('lang.user_category')}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
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
                                                    <form method="post"
                                                          action="{{ route('userscategory.update',$userscategory->id) }}"
                                                          enctype="multipart/form-data" >
                                                        @csrf
                                                        @method("PUT")
                                                        <div class="form-group">
                                                            <label>{{trans('lang.name')}}</label>
                                                            <input type="text" class="form-control" name="name" value="{{old('name')}}"
                                                                   value="{{$userscategory->libelle}}">
                                                        </div>
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
                                </tr>

                                @endforeach
                                </tbody>

                            </table>

                            <nav aria-label="Page navigation example" class="custom-pagination">
                                {{ $userscategories->links() }}
                            </nav>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('lang.user_category')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
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
                <form method="post" action="{{route('userscategory.store')}}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group" id="userscategorymodal">
                        <label for="exampleFormControlInput1">{{trans('lang.name')}}</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter Name"
                               wire:model="name" name="name">
                        @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{trans('lang.close')}}
                </button>
                <button type="submit" class="btn btn-primary">{{trans('lang.save')}}</button>
                </form>
            </div>


            @endsection

            @section('scripts')

            <script type="text/javascript">

                $(document).ready(function () {
                    $(".shadow-sm").hide();

// if($( "#userscategorymodal" ).hasClass( "error" )){
//     alert("check modal");
//     $("#exampleModal").show();
// }

                })
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
                            var url = "{{url('users_category/delete', 'id')}}";
                            url = url.replace('id', arrayUsers);

                            $(this).attr('href', url);
                        }
                    } else {
                        alert('Please Select Any One Record .');
                    }
                });

            </script>

            @endsection
