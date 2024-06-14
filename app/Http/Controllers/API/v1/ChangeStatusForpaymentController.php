<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Requests;
use Illuminate\Http\Request;
use DB;
class ChangeStatusForpaymentController extends Controller
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


  public function ChangeStatus(Request $request)
  {

        $id_requete = $request->get('id_ride');

        $updatedata = DB::update('update tj_requete set id_payment_method = ? where id = ?',[5,$id_requete]);

        if ($updatedata > 0) {
            $sql = Requests::where('id',$id_requete)->first();
            $row = $sql->toarray();
            $row['id']=(string)$row['id'];
            $row['tax'] = json_decode($row['tax'], true);
            $row['stops'] = json_decode($row['stops'], true);

      $response['success']='Status Changed Successfully';
            $response['error']= null;
            $response['data'] = $row;

        }else {
            $response['success']='Failed';
            $response['error']= 'Failed';
        }
   return response()->json($response);
  }
}
