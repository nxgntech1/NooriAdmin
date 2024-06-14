<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\Driver;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Settings;
use Illuminate\Http\Request;
use DB;
class DriverWithdrawalsController extends Controller
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

  public function Withdrawals(Request $request)
  {

        $user_id = $request->get('driver_id');
        $amount = $request->get('amount');
        $note = $request->get('note');
        $date_heure = date('Y-m-d H:i:s');
        $setting=Settings::first();
        $minWithdrawAmount=$setting->minimum_withdrawal_amount;
        if(!empty($user_id) && !empty($amount)){

          $chkid = Driver::where('id',$user_id)->first();
                if($chkid){
                    $driverAmount=$chkid->amount;
                    if($driverAmount>=$minWithdrawAmount)
                    {
                    if($driverAmount>=$amount){
                      $withdrawAmount=$amount;
                      $insertdata = DB::insert("insert into withdrawals(id_conducteur,amount,note,statut,creer,modifier)
                      values('".$user_id."','".$withdrawAmount."','".$note."','pending','".$date_heure."','".$date_heure."')");
                      $id=DB::getPdo()->lastInsertId();
                   if($id > 0){
                     $withdrawals =DB::table('withdrawals')
                         ->select('*')
                         ->where('id',$id)
                         ->first();

                          $row['widrawals_statut'] = $withdrawals->statut;
                          $row['widrawals_amount'] = $withdrawals->amount;


                          $response['success']= 'success';
                          $response['error']= null;
                          $response['message']= 'amount Withdrawals successfully';
                          $response['data'] = $row;
                              $emailsubject = '';
                              $emailmessage = '';
                              $emailtemplate = DB::table('email_template')->select('*')->where('type', 'payout_request')->first();
                              if (!empty($emailtemplate)) {
                                $emailsubject = $emailtemplate->subject;
                                $emailmessage = $emailtemplate->message;
                                $send_to_admin = $emailtemplate->send_to_admin;
                              }

                              $email = DB::table('tj_settings')->select('contact_us_email')->value('contact_us_email');
                              $email = $email ? $email : 'none@none.com';
                              $to = '';
                              if($send_to_admin=="true"){
                                $to = $email;
                              }
                              $currencyData = DB::table('tj_currency')->select('*')->where('statut', 'yes')->first();
                              if ($currencyData->symbol_at_right == "true") {
                                $amount = number_format($withdrawals->amount, $currencyData->decimal_digit) . $currencyData->symbole;
                              } else {
                                $amount = $currencyData->symbole . number_format($withdrawals->amount, $currencyData->decimal_digit);
                              }

                              $app_name = env('APP_NAME', 'Cabme');
                              $date = date('d F Y');
                              $emailsubject = str_replace("{PayoutRequestId}", $withdrawals->id,$emailsubject);

                              $emailmessage = str_replace("{AppName}", $app_name, $emailmessage);
                              $emailmessage = str_replace("{UserName}", $chkid->prenom . " " . $chkid->nom, $emailmessage);
                              $emailmessage = str_replace("{Amount}", $amount, $emailmessage);
                              $emailmessage = str_replace("{UserContactInfo}", $chkid->phone, $emailmessage);
                              $emailmessage = str_replace('{UserId}', $chkid->id, $emailmessage);
                              $emailmessage = str_replace('{PayoutRequestId}', $withdrawals->id, $emailmessage);
                              $emailmessage = str_replace('{Date}', $date, $emailmessage);


                              // Always set content-type when sending HTML email
                              $headers = "MIME-Version: 1.0" . "\r\n";
                              $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                              $headers .= 'From: ' . $app_name . '<' . $email . '>' . "\r\n";
                              mail($to, $emailsubject, $emailmessage, $headers);
                } else{
                      $response['success']= 'Failed';
                      $response['error']= 'Failed to withdrawals';
                  }
                }

                else{
                  $response['success']= 'Failed';
                  $response['error']= 'Unsufficient Balance';
                }
              }else{
                $response['success']= 'Failed';
                $response['error']= 'Unsufficient minimum wallet balance to withdraw'; 
              }

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
