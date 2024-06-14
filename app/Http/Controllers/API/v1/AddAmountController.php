<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\Driver;
use Illuminate\Http\Request;
use DB;
class AddAmountController extends Controller
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
  public function register(Request $request)
  {
    $id_user = $request->get('id_user');
    $cat_user = $request->get('cat_user');
    $amount_init =$request->get('amount');
    $paymethod = $request->get('paymethod');
    $transaction =$request->get('transaction_id');
    $payStatus='success';
    $date_heure = date('Y-m-d H:i:s');

    if($cat_user == "user_app"){

        $sql = DB::table('tj_user_app')
        ->select('amount')
        ->where('id','=',$id_user)
        ->get();
        foreach($sql as $row){
          $amount_ = $row->amount;
          $amount = $amount_+$amount_init;

        $updatedata = DB::update('update tj_user_app set amount = ?,modifier = ? where id = ?',[$amount,$date_heure,$id_user]);

        $query = DB::insert("insert into tj_transaction(amount,deduction_type,payment_method,payment_status,id_user_app, creer,modifier)
        values('".$amount_init."',1,'".$paymethod."','".$payStatus."','".$id_user."','".$date_heure."','".$date_heure."')");
        }
        $sql_notification = UserApp::where('id',$id_user)->first();
        $data = $sql_notification->toArray();
        $row->amount = $data['amount'];
        $email = $data['email'];
        if(!empty($email)){

        $emailsubject = '';
        $emailmessage = '';
        $emailtemplate = DB::table('email_template')->select('*')->where('type', 'wallet_topup')->first();
        if (!empty($emailtemplate)) {
          $emailsubject = $emailtemplate->subject;
          $emailmessage = $emailtemplate->message;
        }
        $currencyData = DB::table('tj_currency')->select('*')->where('statut', 'yes')->first();
        if ($currencyData->symbol_at_right == "true") {
          $amount_init = number_format($amount_init, $currencyData->decimal_digit) . $currencyData->symbole;
          $newBalance = number_format($data['amount'], $currencyData->decimal_digit) . $currencyData->symbole;
        } else {
          $amount_init = $currencyData->symbole . number_format($amount_init, $currencyData->decimal_digit);
          $newBalance = $currencyData->symbole . number_format($data['amount'], $currencyData->decimal_digit);

        }

        $contact_us_email = DB::table('tj_settings')->select('contact_us_email')->value('contact_us_email');
        $contact_us_email = $contact_us_email ? $contact_us_email : 'none@none.com';


        $app_name = env('APP_NAME', 'Cabme');

        $to = $email;
        $date = date('d F Y', strtotime($date_heure));


        $emailmessage = str_replace("{AppName}", $app_name, $emailmessage);
        $emailmessage = str_replace("{UserName}", $data['nom'] . " " . $data['prenom'], $emailmessage);
        $emailmessage = str_replace("{Amount}", $amount_init, $emailmessage);
        $emailmessage = str_replace("{PaymentMethod}", $paymethod, $emailmessage);
        $emailmessage = str_replace('{TransactionId}', $transaction, $emailmessage);
        $emailmessage = str_replace('{Balance}', $newBalance, $emailmessage);
        $emailmessage = str_replace('{Date}', $date, $emailmessage);

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: ' . $app_name . '<' . $contact_us_email . '>' . "\r\n";
        mail($to, $emailsubject, $emailmessage, $headers);

      }

        if(!empty($row)){
          $response['success'] = 'success';
          $response['error'] = null;
          $response['message'] = 'successfully';
          $response['data'] = $row;

        }else{
          $response['success'] = 'Failed';
          $response['error'] = 'Failed';

        }

    }
    elseif($cat_user == "driver"){
        $row = DB::table('tj_conducteur')
        ->select('amount')
        ->where('id','=',DB::raw($id_user))
        ->first();


          $amount_ = $row->amount;
          $amount = $amount_+$amount_init;

          $updatedata = DB::update('update tj_conducteur set amount = ? where id = ?',[$amount,$id_user]);
          $amount=$amount_init;

          DB::insert("insert into tj_conducteur_transaction(amount,payment_method,id_conducteur, creer,modifier)
          values('".$amount_init."','".$paymethod."','".$id_user."','".$date_heure."','".$date_heure."')");
        
        $sql_notification = Driver::where('id',$id_user)->first();
        $data = $sql_notification->toArray();
        $row->amount = $data['amount'];
        $email = $data['email'];

      if (!empty($email)) {

        $emailsubject = '';
        $emailmessage = '';
        $emailtemplate = DB::table('email_template')->select('*')->where('type', 'wallet_topup')->first();
        if (!empty($emailtemplate)) {
          $emailsubject = $emailtemplate->subject;
          $emailmessage = $emailtemplate->message;
          $send_to_admin = $emailtemplate->send_to_admin;
        }
        $currencyData = DB::table('tj_currency')->select('*')->where('statut', 'yes')->first();
        if ($currencyData->symbol_at_right == "true") {
          $amount_init = number_format($amount_init, $currencyData->decimal_digit) . $currencyData->symbole;
          $newBalance = number_format($data['amount'], $currencyData->decimal_digit) . $currencyData->symbole;
        } else {
          $amount_init = $currencyData->symbole . number_format($amount_init, $currencyData->decimal_digit);
          $newBalance = $currencyData->symbole . number_format($data['amount'], $currencyData->decimal_digit);

        }

        $contact_us_email = DB::table('tj_settings')->select('contact_us_email')->value('contact_us_email');
        $contact_us_email = $contact_us_email ? $contact_us_email : 'none@none.com';


        $app_name = env('APP_NAME', 'Cabme');
        
        if($send_to_admin=="true"){
          $to = $email.",".$contact_us_email;

        }else{
          $to = $email;

        }
        $date = date('d F Y', strtotime($date_heure));


        $emailmessage = str_replace("{AppName}", $app_name, $emailmessage);
        $emailmessage = str_replace("{UserName}", $data['nom'] . " " . $data['prenom'], $emailmessage);
        $emailmessage = str_replace("{Amount}", $amount_init, $emailmessage);
        $emailmessage = str_replace("{PaymentMethod}", $paymethod, $emailmessage);
        $emailmessage = str_replace('{TransactionId}', $transaction, $emailmessage);
        $emailmessage = str_replace('{Balance}', $newBalance, $emailmessage);
        $emailmessage = str_replace('{Date}', $date, $emailmessage);

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: ' . $app_name . '<' . $contact_us_email . '>' . "\r\n";
        mail($to, $emailsubject, $emailmessage, $headers);

      }

      if(!empty($row)){
          $response['success'] = 'success';
          $response['error'] = null;
          $response['message'] = 'successfully';
          $response['data'] = $row;

        }else
        {
          $response['success'] = 'Failed';
          $response['error'] = 'Failed';

        }
    }
    else{
      $response['success'] = 'Failed';
      $response['error'] = 'User category is incorrect';

    }
    return response()->json($response);
  }

}
