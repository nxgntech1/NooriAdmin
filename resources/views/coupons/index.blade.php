@extends('layouts.app')

@section('content')

<div class="page-wrapper">


    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.coupon_plural')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">
                    <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                </li>

                <li class="breadcrumb-item active">
                    {{trans('lang.coupon_table')}}
                </li>

            </ol>

        </div>

        <div>

        </div>

    </div>

    <div class="container-fluid">

        <div class="row">

            <div class="col-12">

                <?php if (@$id != '') { ?>
                    <div class="menu-tab">
                        <ul>
                            <li>
                                <a href="{{route('restaurants.view',$id)}}">{{trans('lang.tab_basic')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.foods',$id)}}">{{trans('lang.tab_foods')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.orders',$id)}}">{{trans('lang.tab_orders')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.reviews',$id)}}">{{trans('lang.tab_reviews')}}</a>
                            </li>
                            <li class="active">
                                <a href="{{route('restaurants.coupons',$id)}}">{{trans('lang.tab_promos')}}</a>
                            <li>
                                <a href="{{route('restaurants.payout',$id)}}">{{trans('lang.tab_payouts')}}</a>
                            </li>
                            <!-- <li class="active">
                            <a href="{{route('restaurants.coupons',$id)}}">{{trans('lang.tab_coupons')}}</a>
                            </li> -->
                        </ul>
                    </div>
                <?php } ?>

                <div class="card">
                    <div class="card-header" style="display:none">
                        <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                            <li class="nav-item">
                                <a class="nav-link active" href="{!! url()->current() !!}"><i
                                            class="fa fa-list mr-2"></i>{{trans('lang.coupon_table')}}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{!! route('coupons.create') !!}"><i
                                            class="fa fa-plus mr-2"></i>{{trans('lang.coupon_create')}}</a>
                            </li>

                        </ul>
                    </div>
                    <div class="card-body">

                        <div id="data-table_processing" class="dataTables_processing panel panel-default"
                             style="display: none;">
                            Processing...
                        </div>

                        <div class="userlist-topsearch d-flex mb-3">

                            <div class="userlist-top-left">
                                <a class="nav-link do_not_create" href="{!! route('coupons.create') !!}"><i
                                            class="fa fa-plus mr-2"></i>{{trans('lang.coupon_create')}}</a>
                            </div>

                            <div id="users-table_filter" class="ml-auto">
                                <label>{{ trans('lang.search_by')}}
                                <div class="form-group mb-0">
                                    <form action="{{ route('coupons') }}" method="get">
                                        @if(isset($_GET['selected_search']) && $_GET['selected_search'] != '')
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="code" @if ($_GET[
                                            'selected_search']=='code')
                                            selected="selected" @endif>{{trans('lang.coupon_code')}}</option>

                                            <option value="discount" @if ($_GET[
                                            'selected_search']=='discount')
                                            selected="selected" @endif>{{trans('lang.coupon_discount')}}</option>
                                        </select>
                                        @else
                                        <select name="selected_search" id="selected_search"
                                                class="form-control input-sm">
                                            <option value="code">{{trans('lang.coupon_code')}}</option>
                                            <option value="discount">{{trans('lang.coupon_discount')}}</option>
                                        </select>
                                        @endif
                                        <div class="search-box position-relative">
                                            @if(isset($_GET['search']) && $_GET['search'] != '')
                                            <input type="text" class="search form-control" name="search" id="search"
                                                   value="{{$_GET['search']}}">
                                            @else
                                            <input type="text" class="search form-control" name="search" id="search">
                                            @endif
                                            <button type="submit" class="btn-flat position-absolute">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <!-- <input type="search" id="search" class="search form-control" placeholder="Search" aria-controls="users-table"></label>&nbsp;<button onclick="searchtext();" class="btn btn-warning btn-flat">Search</button>&nbsp; -->
                                            <!-- <button onclick="searchclear();" class="btn btn-warning btn-flat">Clear</button> -->
                                            <a class="btn btn-warning btn-flat" href="{{url('coupons')}}">Clear</a>
                                        </div>
                                    </form>
                                </div>
                                </label>
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

                                    <th>{{trans('lang.coupon_code')}}</th>

                                    <th>{{trans('lang.coupon_discount')}}</th>

                                    <th>{{trans('lang.percent_type')}}</th>
                                    <th>{{trans('lang.coupon_type')}}</th>

                                    <th>{{trans('lang.coupon_description')}}</th>

                                    <th>{{trans('lang.coupon_expires_at')}}</th>

                                    <th>{{trans('lang.coupon_enabled')}}</th>

                                    <th>{{trans('lang.actions')}}</th>

                                </tr>

                                </thead>

                                <tbody id="append_list1">
                                @if(count($discounts) > 0)
                                @foreach($discounts as $discount)
                                <tr>
                                    <td class="delete-all"><input type="checkbox"
                                                                  id="is_open_{{$discount->id}}"
                                                                  class="is_open"
                                                                  dataid="{{$discount->id}}"><label
                                                class="col-3 control-label"
                                                for="is_open_{{$discount->id}}"></label></td>

                                    <td>{{ $discount->code}}</td>
                                    <td>{{ $discount->discount}}</td>
                                    <td>{{ $discount->type}}</td>
                                    <td>{{ $discount->coupon_type}}</td>
                                    <td>{{ $discount->discription}}</td>
                                    <td>
                                        <span class="date">{{ date('d F Y',strtotime($discount->expire_at))}}</span>
                                        <span class="time">{{ date('h:i A',strtotime($discount->expire_at))}}</span>
                                    </td>
                                    <td>@if ($discount->statut=="yes")
                                      <label class="switch"><input type="checkbox" checked id="{{$discount->id}}" name="publish"><span class="slider round"></span></label>
                                      @else <label class="switch"><input type="checkbox"  id="{{$discount->id}}" name="publish"><span class="slider round"></span></label>
                                       @endif
                                    </td>

                                    <!-- <td class="action-btn"><a href="{{route('coupons.edit', ['id' => $discount->id])}}"><i class="fa fa-edit"></i></a></td> -->
                                    <td class="action-btn">
                                        <a href="{{route('coupons.show', ['id' => $discount->id])}}" class=""
                                           data-toggle="tooltip" data-original-title="Details"><i
                                                    class="fa fa-eye"></i></a>
                                        <a href="{{route('coupons.edit', ['id' => $discount->id])}}" class="do_not_edit"><i
                                                    class="fa fa-edit"></i></a><a id="'+val.id+'" class="do_not_delete"
                                                                                  name="coupon-delete"
                                                                                  href="{{route('coupons.delete', ['id' => $discount->id])}}"><i
                                                    class="fa fa-trash"></i></a>
                                    </td>

                                </tr>
                                @endforeach
                                 @else
                                	<tr><td colspan="11" align="center">{{trans("lang.no_result")}}</td></tr>
                                @endif
                                </tbody>

                            </table>

                            <!-- <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item ">
                                        <a class="page-link" href="javascript:void(0);" id="users_table_previous_btn"
                                           onclick="prev()" data-dt-idx="0" tabindex="0">{{trans('lang.previous')}}</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript:void(0);" id="users_table_next_btn"
                                           onclick="next()" data-dt-idx="2" tabindex="0">{{trans('lang.next')}}</a>
                                    </li>
                                </ul>
                            </nav> -->
                            <nav aria-label="Page navigation example" class="custom-pagination">
                            <!-- {{ $discounts->links() }} -->
                            {{$discounts->appends(request()->query())->links()}} 
                            </nav>
                            {{ $discounts->links('pagination.pagination') }}
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
                var url = "{{url('coupons/delete', 'id')}}";
                url = url.replace('id', arrayUsers);

                $(this).attr('href', url);
            }
        } else {
            alert('Please Select Any One Record .');
        }
    });
    /* toggal publish action code start*/
$(document).on("click", "input[name='publish']", function (e) {
    
    var ischeck = $(this).is(':checked');
    var id = this.id;

    $.ajax({
      headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },
       url : 'coupon/switch',
       method:"POST",
       data:{'ischeck':ischeck,'id':id},
       success: function(data){

       },
    });

});

/*toggal publish action code end*/
</script>
@endsection
