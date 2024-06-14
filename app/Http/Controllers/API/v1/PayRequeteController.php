<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\Requests;
use App\Models\Commission;
use App\Models\Tax;

use Illuminate\Http\Request;
use DB;

class PayRequeteController extends Controller
{

    public function __construct()
    {
        $this->limit = 20;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function UpdatePayRequete(Request $request)
    {
        $currencyData = DB::table('tj_currency')->select('*')->where('statut', 'yes')->first();
        $currency = $currencyData->symbole ? $currencyData->symbole : '$';

        $id_requete = $request->get('id_ride');
        $id_user = $request->get('id_driver');
        $id_user_app = $request->get('id_user_app');
        $amount_new = floatval($request->get('amount'));
        $paymethod = $request->get('paymethod');
        $date_heure = date('Y-m-d H:i:s');
        $discount = floatval($request->get('discount'));
        $tax = $request->get('tax');
        $tax_json = json_encode($tax);
        $tip = floatval($request->get('tip'));
        $transaction_id = $request->get('transaction_id');
        $payment_status=$request->get('payment_status');
        $totalamount = $amount_new;
        if (!empty($discount)) {

            $totalamount = $totalamount - $discount;
        }

        $admin_commisions = Commission::where('statut', 'yes')->first();
        $commission_amount = 0;
        if (!empty($admin_commisions)) {
            if ($admin_commisions->type == 'Percentage') {
                $commission_amount = (($admin_commisions->value * $totalamount) / 100);
            } else {
                $commission_amount = $admin_commisions->value;
            }
        }
        $totalTaxAmount = 0;
        $taxHtml = '';
        if (!empty($tax)) {
            for ($i = 0; $i < sizeof($tax); $i++) {
                $data = $tax[$i];
                if ($data['type'] == "Percentage") {
                    $taxValue = (floatval($data['value']) * $totalamount) / 100;
                    $taxlabel = $data['libelle'];
                    $value = $data['value'] . "%";

                } else {
                    $taxValue = floatval($data['value']);
                    $taxlabel = $data['libelle'];
                    if ($currencyData->symbol_at_right == "true") {
                        $value = number_format($data['value'], $currencyData->decimal_digit) . "" . $currency;
                    } else {
                        $value = $currency . "" . number_format($data['value'], $currencyData->decimal_digit);
                    }

                }
                $totalTaxAmount += floatval(number_format($taxValue, $currencyData->decimal_digit));
                if ($currencyData->symbol_at_right == "true") {
                    $taxValueAmount = number_format($taxValue, $currencyData->decimal_digit) . "" . $currency;
                } else {
                    $taxValueAmount = $currency . "" . number_format($taxValue, $currencyData->decimal_digit);
                }
                $taxHtml = $taxHtml . "<p><b>" . $taxlabel . "(" . $value . "): </b>" . $taxValueAmount . "</p>";

            }
            $totalamount = floatval($totalamount) + $totalTaxAmount;

        }
        if ($taxHtml == '') {
            $taxHtml = $taxHtml . "0";
        }

        if (!empty($tip)) {

            $totalamount = $totalamount + $tip;
        }
        $totalDriverAmount = floatval($totalamount) - floatval($commission_amount);
        
        $sql_driver = DB::table('tj_conducteur')
            ->select('amount')
            ->where('id', '=', $id_user)
            ->first();
        $driverWallet = 0;
        if (!empty($sql_driver)) {
            if ($sql_driver->amount != '' && $sql_driver->amount != null) {
                $driverWallet = $sql_driver->amount;
            }
            $driverWallet = $driverWallet + $totalDriverAmount;
            DB::update('update tj_conducteur set amount = ? where id = ?', [$driverWallet, $id_user]);

        }
        $date = date('Y-m-d H:i:s');
        if (!empty($commission_amount)) {
            DB::table('tj_conducteur_transaction')->insert([
                'id_conducteur' => $id_user,
                'amount' => "-" . $commission_amount,
                'payment_method' => $paymethod,
                'id_ride' => $id_requete,
                'creer' => $date
            ]);
        }
        if (!empty($totalDriverAmount)) {
            DB::table('tj_conducteur_transaction')->insert([
                'amount' => $totalDriverAmount,
                'payment_method' => $paymethod,
                'id_conducteur' => $id_user,
                'id_ride' => $id_requete,
                'creer' => $date
            ]);
        }


        $sql_payment_method = DB::table('tj_payment_method')
            ->select('id')->where('libelle', '=', $paymethod)->first();

            if($sql_payment_method){
          		$id_payment = $sql_payment_method->id;
          	}else{
          		$response['success'] = 'Failed';
                  $response['error'] = 'Payment method not found';
          	  	return response()->json($response);
          	}

        $updatedata = DB::update('update tj_requete set statut_paiement = ?,id_payment_method = ?,tip_amount = ?,tax = ?,discount = ?,transaction_id = ?,admin_commission = ? where id = ?', ['yes', $id_payment, $tip, $tax_json, $discount, $transaction_id,$commission_amount, $id_requete]);


        if ($updatedata > 0) {
            $sql = Requests::where('id', $id_requete)->first();
            $row = $sql->toarray();
            $row['id'] = (string)$row['id'];
            $row['tax'] = json_decode($row['tax'], true);

            $row['discount'] = $row['discount'];
            $row['admin Commision']=$row['admin_commission'];
            $response['success'] = 'Success';
            $response['error'] = null;
            $response['data'] = $row;

            $tmsg = '';
            $terrormsg = '';

            $title = str_replace("'", "\'", "Payment of the ride");
            $msg = str_replace("'", "\'", "Your customer has just paid for his ride");

            $tab[] = array();
            $tab = explode("\\", $msg);
            $msg_ = "";
            for ($i = 0; $i < count($tab); $i++) {
                $msg_ = $msg_ . "" . $tab[$i];
            }
            $message = array("body" => $msg_, "title" => $title, "sound" => "mySound", "tag" => "ridecompleted");
            $query = DB::table('tj_conducteur')
                ->select('fcm_id')
                ->where('fcm_id', '!=', '')
                ->where('id', '=', $id_user)
                ->get();

            $tokens = array();
            if (!empty($query)) {
                foreach ($query as $user) {
                    if (!empty($user->fcm_id)) {
                        $tokens[] = $user->fcm_id;
                    }
                }
            }

            $temp = array();
            $data = array("type" => 'payment received');
            if (count($tokens) > 0) {
                GcmController::send_notification($tokens, $message, $data);
            }

            // Get user info
            $query = DB::table('tj_requete')
                ->crossJoin('tj_user_app')
                ->select('tj_user_app.fcm_id', 'tj_user_app.id', 'tj_user_app.nom', 'tj_user_app.prenom', 'tj_user_app.email')
                ->where('tj_requete.id_user_app', '=', DB::raw('tj_user_app.id'))
                ->where('tj_requete.id', '=', $id_requete)
                ->get();

            // Get Ride Info
            $query_ride = DB::table('tj_requete')
                ->select('distance', 'distance_unit','duree', 'montant', 'creer', 'trajet','discount','tax','tip_amount')
                ->where('id', '=', $id_requete)
                ->get();
            foreach ($query_ride as $ride) {
                $distance = $ride->distance;
				$distance_unit = $ride->distance_unit;
                $duree = $ride->duree;
                $date_heure = $ride->creer;
                $img_name = $ride->trajet;
            }

            $total = !empty($totalamount) ? $totalamount : 0;
            $subtotal = !empty($amount_new) ? number_format($amount_new, 2) : 0;
            $discount = !empty($discount) ? number_format($discount, 2) : 0;
            $tax = number_format($totalTaxAmount, 2);
            $tip_amount = !empty($tip) ? number_format($tip, 2) : 0;
            $total = number_format($total, 2);
            if ($currencyData->symbol_at_right == "true") {
                $total = $total . "" . $currency;
                $subtotal = $subtotal . "" . $currency;
                $discount = $discount . "" . $currency;
                $tip_amount = $tip_amount . "" . $currency;
                $tax = $tax . "" . $currency;

            } else {
                $total = $currency . "" . $total;
                $subtotal = $currency . "" . $subtotal;
                $discount = $currency . "" . $discount;
                $tip_amount = $currency . "" . $tip_amount;
                $tax = $currency . "" . $tax;

            }

            $tokens = array();
            $nom = "";
            $prenom = "";
            $email = "";
            if (!empty($query)) {
                foreach ($query as $user) {

                    if (!empty($user->fcm_id)) {
                        $tokens[] = $user->fcm_id;
                        $nom = $user->nom;
                        $prenom = $user->prenom;
                        $email = $user->email;
                    }
                }
            }

            if ($email != "") {
                $emailsubject = '';
                $emailmessage = '';
                $emailtemplate = DB::table('email_template')->select('*')->where('type', 'payment_receipt')->first();
                if (!empty($emailtemplate)) {
                    $emailsubject = $emailtemplate->subject;
                    $emailmessage = $emailtemplate->message;
                    $send_to_admin = $emailtemplate->send_to_admin;
                }

                $contact_us_email = DB::table('tj_settings')->select('contact_us_email')->value('contact_us_email');
				$contact_us_email = $contact_us_email?$contact_us_email:'none@none.com';

                $app_name = env('APP_NAME','Cabme');
                
                if ($send_to_admin == "true") {
                    $to = $email . "," . $contact_us_email;
                } else {
                    $to = $email;
                }

                //$subject = "Payment Receipt - ".$app_name;
                $emailsubject = str_replace("{AppName}", $app_name, $emailsubject);

                $emailmessage = str_replace("{AppName}", $app_name, $emailmessage);
                $emailmessage = str_replace("{UserName}", $prenom . " " . $nom, $emailmessage);
                $emailmessage = str_replace("{Distance}", $distance . " " . $distance_unit, $emailmessage);
                $emailmessage = str_replace("{Duree}", $duree, $emailmessage);
                $emailmessage = str_replace('{Subtotal}', $subtotal, $emailmessage);
                $emailmessage = str_replace('{Discount}', $discount, $emailmessage);
                $emailmessage = str_replace('{Tax}', $taxHtml, $emailmessage);
                $emailmessage = str_replace('{Tip}', $tip_amount, $emailmessage);
                $emailmessage = str_replace('{Total}', $total, $emailmessage);
                $emailmessage = str_replace('{Date}', $date, $emailmessage);

                // Always set content-type when sending HTML email
               	$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$headers .= 'From: '.$app_name.'<'.$contact_us_email.'>' . "\r\n";
                mail($to, $emailsubject, $emailmessage, $headers);
            }
        }
        else {
            $response['success'] = 'Failed';
            $response['error'] = 'Failed';
        }
        return response()->json($response);
    }

    public static function url()
    {
        $actual_link = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $site_url = preg_replace('/^www\./', '', parse_url($actual_link, PHP_URL_HOST));
        if (($_SERVER['HTTPS'] && $_SERVER['HTTPS'] === 'on')) {
            return "https://" . $site_url;
        } else {
            return "http://" . $site_url;
        }

    }
}
