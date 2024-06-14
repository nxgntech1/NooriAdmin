@extends('layouts.app')

@section('content')
<div class="page-wrapper">

                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-themecolor">Pending Cash collection</h3>
                    </div>
                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="http://127.0.0.1:8000/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Pending Cash collection</li>
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
                                    <div id="data-table_processing" class="dataTables_processing panel panel-default"
                                        style="display: none;">Processing...
                                    </div>
                                    <div class="userlist-topsearch d-flex mb-3">
                                        <div class="userlist-top-left">
                                            <!-- Swamy Goud -->
                                            <!-- <a class="nav-link" href="http://127.0.0.1:8000/users/create"><i
                                                    class="fa fa-plus mr-2"></i>Create User</a> -->
                                        </div>
                                        <div class="ml-auto collection_blk user-detail">
                                            <div class="col-group">
												<label>Total Trips: <span>15</span></label>
											</div>
                                            <div class="col-group">
												<label>Pending Amount: <span>30,000</span></label>
											</div>
                                        </div>
                                        <!-- <div id="users-table_filter" class="ml-auto">
                                            <label>Search By :
                                                <div class="form-group mb-0">
                                                    <form action="http://127.0.0.1:8000/users" method="get">

                                                        <select name="selected_search" id="selected_search"
                                                            class="form-control input-sm">
                                                            <option value="prenom">Name</option>
                                                            <option value="email">Email</option>
                                                            <option value="phone">Phone</option>
                                                        </select>
                                                        <div class="search-box position-relative">
                                                            <input type="text" class="search form-control" name="search"
                                                                id="search">
                                                            <button type="submit" class="btn-flat position-absolute"><i
                                                                    class="fa fa-search"></i></button>
                                                            
                                                            <a class="btn btn-warning btn-flat"
                                                                href="http://127.0.0.1:8000/users">Clear</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </label>
                                        </div> -->
                                    </div>
                                    <div class="table-responsive m-t-10">
                                        <table id="example24"
                                            class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                            cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                            class="col-3 control-label" for="is_active"><a
                                                                id="deleteAll" class="do_not_delete"
                                                                href="javascript:void(0)"><i class="fa fa-trash"></i>
                                                                All</a></label></th>
                                                    <th>Image</th>
                                                    <th>Driver Name</th>
                                                    <th>Total Cash Trips</th>
                                                    <th>Pending Amount</th>
                                                   
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="append_list12">
                                                <tr>
                                                    <td class="delete-all"><input type="checkbox" id="is_open_11"
                                                            class="is_open" dataid="11"><label
                                                            class="col-3 control-label" for="is_open_11"></label></td>



                                                    <td><img class="rounded" style="width:50px"
                                                            src="http://127.0.0.1:8000/assets/images/placeholder_image.jpg"
                                                            alt="image"></td>

                                                    <td>Sandeep Kumar
                                                    </td>
                                                    <td>10</td>
                                                    <td>10,000</td>
                                                    

                                                    <td class="action-btn">
                                                        <a href="http://127.0.0.1:8000/users/show/11" class=""
                                                            data-toggle="tooltip" data-original-title="Details"><i
                                                                class="fa fa-eye"></i></a>
                                                        <a href="http://127.0.0.1:8000/users/edit/11"><i
                                                                class="fa fa-edit"></i></a><a id="'+val.id+'"
                                                            class="do_not_delete" name="user-delete"
                                                            href="http://127.0.0.1:8000/user/delete/11"><i
                                                                class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="delete-all"><input type="checkbox" id="is_open_10"
                                                            class="is_open" dataid="10"><label
                                                            class="col-3 control-label" for="is_open_10"></label></td>



                                                    <td><img class="rounded" style="width:50px"
                                                            src="http://127.0.0.1:8000/assets/images/placeholder_image.jpg"
                                                            alt="image"></td>

                                                    <td>Jagan Mohan
                                                    </td>
                                                    <td>15</td>
                                                    <td>15,000</td>
                                                    

                                                    <td class="action-btn">
                                                        <a href="http://127.0.0.1:8000/users/show/10" class=""
                                                            data-toggle="tooltip" data-original-title="Details"><i
                                                                class="fa fa-eye"></i></a>
                                                        <a href="http://127.0.0.1:8000/users/edit/10"><i
                                                                class="fa fa-edit"></i></a><a id="'+val.id+'"
                                                            class="do_not_delete" name="user-delete"
                                                            href="http://127.0.0.1:8000/user/delete/10"><i
                                                                class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="delete-all"><input type="checkbox" id="is_open_9"
                                                            class="is_open" dataid="9"><label
                                                            class="col-3 control-label" for="is_open_9"></label></td>



                                                    <td><img class="rounded" style="width:50px"
                                                            src="http://127.0.0.1:8000/assets/images/placeholder_image.jpg"
                                                            alt="image"></td>

                                                    <td>Uma Challa
                                                    </td>
                                                    <td>5</td>
                                                    <td>5,000</td>


                                                    <td class="action-btn">
                                                        <a href="http://127.0.0.1:8000/users/show/9" class=""
                                                            data-toggle="tooltip" data-original-title="Details"><i
                                                                class="fa fa-eye"></i></a>
                                                        <a href="http://127.0.0.1:8000/users/edit/9"><i
                                                                class="fa fa-edit"></i></a><a id="'+val.id+'"
                                                            class="do_not_delete" name="user-delete"
                                                            href="http://127.0.0.1:8000/user/delete/9"><i
                                                                class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            
                                            </tbody>
                                        </table>


                                        <!--  -->
                                        <nav aria-label="Page navigation example" class="custom-pagination">

                                        </nav>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>       
@endsection

@section('scripts')
@endsection