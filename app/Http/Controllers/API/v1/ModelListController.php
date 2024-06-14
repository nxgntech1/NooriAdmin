<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;
use DB;
class ModelListController extends Controller
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
        $vehicle_type = $request->get('vehicle_type');
        $brand = $request->get('brand');
        $output=[];
        if(!empty($brand)){
          $brands = DB::table('brands')
          ->select('id','name')
          ->where('name', '=', $brand)
          ->get();
          foreach($brands as $rowbrand)
          $brandId = $rowbrand->id;
          if(!empty($brandId)){
            $chkdriver = DB::table('car_model')
            ->select('id','name')
            ->where('brand_id', $brandId)
            ->where('vehicle_type_id', $vehicle_type)
            ->where('status','=','yes')
            ->get();


        if(!empty($chkdriver))
        {
          foreach ($chkdriver as $row) {
            $row->id=(string)$row->id;
            $output[]=$row;
          }

        //  $output[]=$row;
        if(!empty($row)){
          $response['success']= 'success';
          $response['error']= null;
          $response['message']= 'model fetch successful';
          $response['data'] = $output;
        } else {
          $response['success']= 'Failed';
          $response['error']= 'No Data Found';
          $response['message']= null;
      }
          } else {
            $response['success']= 'Failed';
            $response['error']= 'No Data Found';
            $response['message']= null;
          }
        }
          else{
            $response['success']= 'Failed';
            $response['error']= 'No Data Found';
            $response['message']= null;
          }
        }
       else {
        $response['success']= 'Failed';
        $response['error']= 'Brand name is required';
        $response['message']= null;
    }


    return response()->json($response);
  }

}
