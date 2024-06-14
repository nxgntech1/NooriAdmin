<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class PayoutRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function payout($id=null){
     $currency = Currency::where('statut', 'yes')->first();
            if($id!=null || $id!=''){
                $withdrawal=DB::table('withdrawals')->join('tj_conducteur','tj_conducteur.id','=','withdrawals.id_conducteur')
                    ->select('withdrawals.*','tj_conducteur.nom','tj_conducteur.prenom')
                    ->where('withdrawals.id_conducteur','=',$id)->orderBy('id','desc')->paginate(20);
            }
            else{
                $withdrawal=DB::table('withdrawals')->join('tj_conducteur','tj_conducteur.id','=','withdrawals.id_conducteur')
                    ->select('withdrawals.*','tj_conducteur.nom','tj_conducteur.prenom')->
                where('withdrawals.statut','=','pending')->orderBy('id','desc')->paginate(20);
            }
        return view("payoutRequest.index")->with("withdrawal", $withdrawal)->with('currency',$currency);

   }
   
   public function getBankDetails(Request $request){
     $id=$request->input('id');
    $bankDetails= DB::table('tj_conducteur')->select('*')->where('id','=',$id)->get();

    $bankName=$bankDetails[0]->bank_name;
    $branchName=$bankDetails[0]->branch_name;
    $accNo=$bankDetails[0]->account_no;
    $other_info=$bankDetails[0]->other_info;
    $holderName=$bankDetails[0]->holder_name;
    $data=array('bankName'=>$bankName,'branchName'=>$branchName,'accNo'=>$accNo,'other_info'=>$other_info,'holderName'=>$holderName);
     echo json_encode($data);
   }

  public function acceptWithdrawal(Request $request){
    $id=$request->input('id');
    $withdrawal=Withdrawal::find($id);
    $driver_id=$withdrawal->id_conducteur;
    $withdraw_amount=$withdrawal->amount;
    $driver=DB::table('tj_conducteur')->select('amount','email','nom','prenom')->where('id','=',$driver_id)->first();
    $newDriverAmount=$driver->amount-$withdraw_amount;
    DB::table('tj_conducteur')->where('id','=',$driver_id)->update(['amount' => $newDriverAmount]);
    if($withdrawal){
      $withdrawal->statut='success';
      $withdrawal->save();

    }
    $date = date('d F Y');

    if(!empty($driver->email)){
      $emailsubject = '';
      $emailmessage = '';
      $emailtemplate = DB::table('email_template')->select('*')->where('type', 'payout_approve_disapprove')->first();
      if (!empty($emailtemplate)) {
        $emailsubject = $emailtemplate->subject;
        $emailmessage = $emailtemplate->message;
        $send_to_admin = $emailtemplate->send_to_admin;
      }
      $currencyData = DB::table('tj_currency')->select('*')->where('statut', 'yes')->first();
      if ($currencyData->symbol_at_right == "true") {
        $amount = number_format($withdraw_amount, $currencyData->decimal_digit) . $currencyData->symbole;
      } else {
        $amount = $currencyData->symbole . number_format($withdraw_amount, $currencyData->decimal_digit);
      }
      $contact_us_email = DB::table('tj_settings')->select('contact_us_email')->value('contact_us_email');
      $contact_us_email = $contact_us_email ? $contact_us_email : 'none@none.com';


      $app_name = env('APP_NAME', 'Cabme');
      if($send_to_admin=="true"){
        $to = $driver->email . "," . $contact_us_email;
      }else{
        $to = $driver->email;
      }
      $emailsubject = str_replace('{RequestId}', $id, $emailsubject);

      $emailmessage = str_replace("{AppName}", $app_name, $emailmessage);
      $emailmessage = str_replace("{UserName}", $driver->nom . " " . $driver->prenom, $emailmessage);
      $emailmessage = str_replace("{Amount}", $amount, $emailmessage);
      $emailmessage = str_replace("{Status}", 'Success', $emailmessage);
      $emailmessage = str_replace('{RequestId}', $id, $emailmessage);
      $emailmessage = str_replace('{Date}', $date, $emailmessage);

      // Always set content-type when sending HTML email
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= 'From: ' . $app_name . '<' . $contact_us_email . '>' . "\r\n";
      mail($to, $emailsubject, $emailmessage, $headers);

    }

  }
  
  public function rejectWithdrawal(Request $request)
  {
    $id = $request->input('id');
    $withdrawal = Withdrawal::find($id);
    $driver_id = $withdrawal->id_conducteur;
    $withdraw_amount = $withdrawal->amount;
    $driver = DB::table('tj_conducteur')->select('amount', 'email', 'nom', 'prenom')->where('id', '=', $driver_id)->first();

    if ($withdrawal) {
      $withdrawal->statut = 'rejected';
      $withdrawal->save();
    }
    $date = date('d F Y');
    if (!empty($driver->email)) {
      $emailsubject = '';
      $emailmessage = '';
      $emailtemplate = DB::table('email_template')->select('*')->where('type', 'payout_approve_disapprove')->first();
      if (!empty($emailtemplate)) {
        $emailsubject = $emailtemplate->subject;
        $emailmessage = $emailtemplate->message;
        $send_to_admin = $emailtemplate->send_to_admin;

      }
      $currencyData = DB::table('tj_currency')->select('*')->where('statut', 'yes')->first();
      if ($currencyData->symbol_at_right == "true") {
        $amount = number_format($withdraw_amount, $currencyData->decimal_digit) . $currencyData->symbole;
      } else {
        $amount = $currencyData->symbole . number_format($withdraw_amount, $currencyData->decimal_digit);
      }
      $contact_us_email = DB::table('tj_settings')->select('contact_us_email')->value('contact_us_email');
      $contact_us_email = $contact_us_email ? $contact_us_email : 'none@none.com';


      $app_name = env('APP_NAME', 'Cabme');

      if ($send_to_admin == "true") {
        $to = $driver->email . "," . $contact_us_email;
      } else {
        $to = $driver->email;
      }
      $emailsubject = str_replace('{RequestId}', $id, $emailsubject);

      $emailmessage = str_replace("{AppName}", $app_name, $emailmessage);
      $emailmessage = str_replace("{UserName}", $driver->nom . " " . $driver->prenom, $emailmessage);
      $emailmessage = str_replace("{Amount}", $amount, $emailmessage);
      $emailmessage = str_replace("{Status}", 'Rejected', $emailmessage);
      $emailmessage = str_replace('{RequestId}', $id, $emailmessage);
      $emailmessage = str_replace('{Date}', $date, $emailmessage);

      // Always set content-type when sending HTML email
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= 'From: ' . $app_name . '<' . $contact_us_email . '>' . "\r\n";
      mail($to, $emailsubject, $emailmessage, $headers);

    }
  }

}
