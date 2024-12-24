<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\Driver;
use App\Models\Currency;
use App\Models\Country;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class DiscountController extends Controller
{

   public function __construct()
   {
      $this->limit=20;
   }
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

  public function discountList(Request $request)
  {
    $rideType =$request->get('ride_type');
    $userid =$request->header('user_id');
    
    $today = Carbon::now();

    if(!empty($rideType) && $rideType=="Multiple"){
        $sql = DB::table('tj_discount')
        ->where('statut','=','yes')
        ->where('coupon_type','=','Multiple')
        ->where('expire_at','>=',$today)
        ->get();
      }
      else if(!empty($rideType) && $rideType=="Single")
      {
        $sql = DB::table('tj_discount')
        ->leftJoin('user_coupon_used_log', function($join) use ($userid) {
          $join->on('tj_discount.id', '=', 'user_coupon_used_log.couponid')
               ->where('user_coupon_used_log.userid', '=', $userid);
      })
      ->select('tj_discount.*')
        ->where('statut','=','yes')
        ->where('coupon_type','=','Single')
        ->where('expire_at','>=',$today)
        ->whereNull('user_coupon_used_log.id')
        ->get();
      }
      else{
        $sql = DB::table('tj_discount')
        ->leftJoin('user_coupon_used_log', function($join) use ($userid) {
            $join->on('tj_discount.id', '=', 'user_coupon_used_log.couponid')
                 ->where('user_coupon_used_log.userid', '=', $userid)
                 ->where('tj_discount.coupon_type', '=', 'Single'); // Included in the join condition
        })
        ->select('tj_discount.*')
        ->where('tj_discount.statut', '=', 'yes')
        ->where('tj_discount.expire_at', '>=', $today)
        ->whereNull('user_coupon_used_log.id') // Ensures the coupon has not been used by the user
        ->get();
      }
      
      if(!empty($sql)){
          foreach($sql as $row){
              $row->id=(string)$row->id;
              $row->expire_at= date('d F Y',strtotime($row->expire_at)).' '. date('h:i A',strtotime($row->expire_at));
              $output[] = $row;
          }

          if(!empty($output)){
              $response['success']= 'success';
              $response['error']= null;
              $response['message']= 'successfully';
              $response['data'] = $output;
          }else{
            $response['success']= 'Failed';
            $response['error']= 'No Data Found';
            $response['message']= null;
          }

      } else{
          $response['success']= 'Failed';
          $response['error']= 'Not Found';
      }

      return response()->json($response);
  }

}
