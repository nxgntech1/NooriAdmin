<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Models\VehicleLocation;
use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\GcmController;
use DB;
class ContactUsController extends Controller
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
  public function contact(Request $request)
  {
  
    $title = $request->get('title');
    $user_message = $request->get('user_message');
    $user_id = $request->get('user_id');
    $user_name = $request->get('user_name');
    $user_cat = $request->get('user_cat');

    if($user_cat == "user_app"){
        $sql = DB::table('tj_user_app')
        ->select('nom','prenom','email','phone')
        ->where('id','=',$user_id)
        ->get();
       foreach($sql as $row){
        $user_name = $row->nom." ".$row->prenom;
        $email = $row->email;
        $phone = $row->phone; 
       }
       
    }else{
        $sql = DB::table('tj_conducteur')
        ->select('nom','prenom','email','phone')
        ->where('id','=',$user_id)
        ->get();
       
        foreach($sql as $row){
            $user_name = $row->nom." ".$row->prenom;
            $email = $row->email;
            $phone = $row->phone; 
           }
    }
   

    $sql = DB::table('tj_user')
    ->select('email')
    ->where('id_categorie_user','=',1)
    ->get();
    if(!empty($sql)){
    foreach($sql as $data){
    $to = $row->email;
        
            $subject = $title;
            $message = '
                <body style="margin:100px; background: #ffc600; ">
                    <div width="100%" style="background: #ffc600; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
                        <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px; background: #fff;">
                         
                            <div style="padding: 40px; background: #fff;">
                                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <h2>Hello Admin,</h2><br>
                                            You received an email from : '.$user_name.'</br>
                                            Here are the details:</br>
                                            <b>Email:</b> '.$email.'</br>
                                            <b>Phone Number:</b> '.$phone.'</br>
                                            <b>Message:</b> '.$user_message.'</br>
                                            Thank You
                                            </br>                                                
                                            
                                            <br/>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </body>
            ';
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\b";
            $headers .= 'From: '.$user_name . "\r\n";
            mail($to,$subject,$message,$headers);
        }
            $response['success']= 'success';
            $response['error']= null;
            $response['message']= 'Successfully';
            $response['data'] = $row;
    } else {
        $response['success']= 'Failed';
        $response['error']= 'Failed to Contact';
    }
    return response()->json($response);
  }
       
    
   
  

}