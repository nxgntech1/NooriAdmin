<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use DB;
class PaymentMethodController extends Controller
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

  public function getData(Request $request)
  {

    $sql = DB::table('tj_payment_method')
    ->select('*')
    ->where('statut','=','yes')
    ->get();

    // output data of each row
    $output = array();
    foreach($sql as $row)
    {
        $row->id=(string)$row->id;
        if($row->image != ''){
            if(file_exists(public_path('assets/images/payment_method'.'/'.$row->image )))
            {
                $image_user = asset('assets/images/payment_method').'/'. $row->image;
            }
            else
            {
                $image_user =asset('assets/images/placeholder_image.jpg');

            }
            $row->image = $image_user;
        }
        $output[] = $row;
    }

        if(!empty($sql)){
            $response['success']= 'success';
            $response['error']= null;
            $response['message'] = 'Successfully';
            $response['data'] = $output;
        }else{
            $response['success']= 'Failed';
            $response['error']= 'Failed to fetch data';
        }

        return response()->json($response);

    }



}
