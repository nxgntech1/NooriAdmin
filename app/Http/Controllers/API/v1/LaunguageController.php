<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\language;
use Illuminate\Http\Request;
use DB;
class LaunguageController extends Controller
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

                $sql =DB::table('language')->get();
   
            //$row = $sql->toArray();
            $output = array();
            foreach($sql as $row){
               
          
            
            if($row->flag != ''){
            if(file_exists(public_path('assets/images/flags'.'/'.$row->flag )))
            {
                $image_user = asset('assets/images/flags').'/'. $row->flag;
            }
            else
            {
                $image_user =asset('assets/images/placeholder_image.jpg');
      
            }
            $row->flag = $image_user;
           }    
           $row->id=(string)$row->id; 
      
          $output[] = $row;

          }
          
          
     

    if($sql->count() > 0){
     
      $response['success']= 'Success';
      $response['error']= null;
      $response['message']='Successfully fetched data';
      $response['data']=$output;
    }else{
      $response['success']= 'Failed';
      $response['error']= 'Not Found';
    }
    return response()->json($response);


}

}