<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\ParcelCategory;
use Illuminate\Http\Request;
use DB;
class ParcelCategoryController extends Controller
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

        $output=[];
        $parcelCategory = ParcelCategory::where('status','=','yes')->get();

      if(count($parcelCategory)>0)
      {
        foreach ($parcelCategory as $row) {
          $row->id=(string)$row->id;
          if($row->image!=''){
          if (file_exists(public_path('assets/images/parcel_category' . '/' . $row->image))) {
            $row->image = asset('assets/images/parcel_category') . '/' . $row->image;
          } else {
            $row->image = asset('assets/images/placeholder_image.jpg');

          }

        }
          $output[]=$row;
        }
       //$output[]=$row;
       if(!empty($output)){
        $response['success']= 'success';
        $response['error']= null;
        $response['message']= 'Parcel Category fetch successful';
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
