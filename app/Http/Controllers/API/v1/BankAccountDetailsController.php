<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use DB;
class BankAccountDetailsController extends Controller
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

      $users = Driver::all();
      $users = Driver::paginate($this->limit);
      return response()->json($users);
    }

  	public function getData(Request $request)
  	{

   		$user_id = $request->get('driver_id');

	    if(!empty($user_id)){
		    $row = DB::table('tj_conducteur')
	    	->select('bank_name','branch_name','holder_name','account_no','other_info','ifsc_code')
	    	->where('id',$user_id)
	    	->first();
        
        if($row->bank_name==null){
          $row->bank_name='';
        }
        if($row->branch_name==null){
          $row->branch_name='';
        }
        if($row->holder_name==null){
          $row->holder_name='';
        }
        if($row->account_no==null){
          $row->account_no='';
        }
        if($row->other_info==null){
          $row->other_info='';
        }
        if($row->ifsc_code==null){
          $row->ifsc_code='';
        }

         if($row->bank_name=='' && $row->branch_name=='' && $row->holder_name=='' && $row->account_no=='' && $row->other_info=='' && $row->ifsc_code==''){
           $response['success']= 'Failed';
           $response['error']= 'Failed to fetch bank details';
         }
	    	else{
	        	$response['success']= 'success';
	        	$response['error']= null;
	        	$response['message']= 'Bank details fetch successfully';
	        	$response['data'] = $row;
	    	}

	  }else{
	    	$response['success']= 'Failed';
		    $response['error']= 'Driver Id required';
	  }

	    return response()->json($response);
	}

	 public function register(Request $request)
	 {

        $user_id = $request->get('driver_id');
        $bank_name = $request->get('bank_name');
        $branch_name = $request->get('branch_name');
        $holder_name = $request->get('holder_name');
        $account_no = $request->get('account_no');
        $other_info = $request->get('information');
        $ifsc_code = $request->get('ifsc_code');
        $date_heure = date('Y-m-d H:i:s');

        if(!empty($user_id)){

          	$driver = Driver::where('id',$user_id)->first();

	        if($driver){

	            $updatedata = DB::update('update tj_conducteur set bank_name = ?,branch_name = ?,holder_name = ?,account_no = ?, other_info = ?,ifsc_code = ?,modifier = ? where id = ?',[$bank_name,$branch_name,$holder_name,$account_no,$other_info,$ifsc_code,$date_heure,$user_id]);

	            if($updatedata){

	                $row = DB::table('tj_conducteur')->select('bank_name','branch_name','holder_name','account_no','other_info')->where('id',$user_id)->first();

	                $response['success']= 'success';
	                $response['error']= null;
	                $response['message']= 'Bank details added successfully';
	                $response['data'] = $row;
	            }else{
	                $response['success']= 'Failed';
	                $response['error']= 'Failed to add bank details';
	            }

	        }else{
                $response['success']= 'Failed';
                $response['error']= 'Driver Not Found';
            }

		}else{
            $response['success']= 'Failed';
            $response['error']= 'Not Found';
        }

    	return response()->json($response);
  	}
}
