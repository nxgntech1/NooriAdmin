<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\Driver;
use App\Models\Currency;
use App\Models\Country;
use Illuminate\Http\Request;
use DB;
class GetDriverWithdrawalsController extends Controller
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
  public function index()
  {

    $users = UserApp::all();
    $users = UserApp::paginate($this->limit);
    return response()->json($users);
  }

  public function WithdrawalsList(Request $request)
  {


        $user_id = $request->get('driver_id');
        $date_heure = date('Y-m-d H:i:s');

        if(!empty($user_id)){

            $sql = DB::table('withdrawals')
            ->orderBy('creer', 'Desc')
            ->where('id_conducteur', $user_id)
            ->get();
                if(count($sql) > 0){
                    foreach($sql as $row){
                        $row->id=(string)$row->id;
                        $sql_bank=Driver::where('id', $row->id_conducteur)->get();
                        foreach($sql_bank as $row_bank){
                            $row->bank_name = $row_bank->bank_name;
                            $row->branch_name = $row_bank->branch_name;
                            $row->account_no = $row_bank->account_no;
                            $row->other_info = $row_bank->other_info;
                            $row->ifsc_code = $row_bank->ifsc_code;
                        }

                        $output[] = $row;
                    }


                        $response['success']= 'success';
                        $response['error']= null;
                        $response['message']= 'successfully';
                        $response['data'] = $output;


                }

                else{
                    $response['success']= 'Failed';
                    $response['error']= 'Driver Not Found';
                }

            }

        else{
            $response['success']= 'Failed';
            $response['error']= 'Some fields not found';
        }


    return response()->json($response);
  }

}
