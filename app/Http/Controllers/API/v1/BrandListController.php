<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;
use DB;
class BrandListController extends Controller
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

    $driver = Vehicle::all();
    $driver = Vehicle::paginate($this->limit);
    return response()->json($driver);
  }

  public function getData(Request $request)
  {

        $output=[];
        $chkdriver = DB::table('brands')
        ->select('id','name')
        ->where('status','=','yes')
        ->get();

      if(!empty($chkdriver))
      {
        foreach ($chkdriver as $row) {
          $row->id=(string)$row->id;
          $output[]=$row;
        }
       
        if(!empty($row)){
        $response['success']= 'success';
        $response['error']= null;
        $response['message']= 'Brand fetch successful';
        $response['data'] = $output;
        } else {
            $response['success']= 'Failed';
            $response['error']= 'Error while fetch data';
        }
      }else {
        $response['success']= 'Failed';
        $response['error']= 'No Data Found';
        $response['message']= null;
      }

    return response()->json($response);
  }

}
