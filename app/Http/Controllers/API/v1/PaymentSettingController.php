<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\PaymentSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\GcmController;
use DB;
class PaymentSettingController extends Controller
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
    $sql = DB::table('payment_settings')
    ->crossJoin('tj_payment_method')
    ->select('payment_settings.id',
    'payment_settings.secret_key',
    'payment_settings.public_key', 'payment_settings.encryption_key',

     'payment_settings.isEnabled',
     'payment_settings.isSandboxEnabled', 'payment_settings.id_payment_method',
     'tj_payment_method.libelle')
    ->where('payment_settings.id_payment_method','=',DB::raw('tj_payment_method.id'))
    ->where('tj_payment_method.libelle','=','FlutterWave')
    ->get();


        foreach ($sql as $row)
        {
           $row->id=(string)$row->id;
        }


        $sql_cash = DB::table('payment_settings')
        ->crossJoin('tj_payment_method')
        ->select('payment_settings.id',  'payment_settings.isEnabled',
         'tj_payment_method.libelle','payment_settings.id_payment_method')
        ->where('payment_settings.id_payment_method','=',DB::raw('tj_payment_method.id'))
        ->where('tj_payment_method.libelle','=','Cash')
        ->get();


            foreach ($sql_cash as $row_cash)
            {
                $row_cash->id=(string)$row_cash->id;
            }

            $sql_payfast = DB::table('payment_settings')
            ->crossJoin('tj_payment_method')
            ->select('payment_settings.id', 'payment_settings.merchant_Id', 'payment_settings.merchant_key',
            'payment_settings.cancel_url',
            'payment_settings.notify_url', 'payment_settings.return_url', 'payment_settings.isEnabled',
            'payment_settings.isSandboxEnabled', 'payment_settings.id_payment_method',
             'tj_payment_method.libelle')
            ->where('payment_settings.id_payment_method','=',DB::raw('tj_payment_method.id'))
            ->where('tj_payment_method.libelle','=','PayFast')
            ->get();


                foreach ($sql_payfast as $row_payfast)
                {
                    $row_payfast->id=(string)$row_payfast->id;
                }

                $sql_wallet = DB::table('payment_settings')
                ->crossJoin('tj_payment_method')
                ->select('payment_settings.id',  'payment_settings.isEnabled',
                'tj_payment_method.libelle','payment_settings.id_payment_method')
                ->where('payment_settings.id_payment_method','=',DB::raw('tj_payment_method.id'))
                ->where('tj_payment_method.libelle','=','Wallet')
                ->get();


                    foreach ($sql_wallet as $row_wallet)
                    {
                        $row_wallet->id=(string)$row_wallet->id;
                    }

                    $sql_strip = DB::table('payment_settings')
                    ->crossJoin('tj_payment_method')
                    ->select('payment_settings.id', 'payment_settings.key','payment_settings.clientpublishableKey',
                    'payment_settings.secret_key', 'payment_settings.isEnabled',
                    'payment_settings.isSandboxEnabled', 'payment_settings.id_payment_method',
                     'tj_payment_method.libelle')
                    ->where('payment_settings.id_payment_method','=',DB::raw('tj_payment_method.id'))
                    ->where('tj_payment_method.libelle','=','Stripe')
                    ->get();


                        foreach ($sql_strip as $row_strip)
                        {
                            $row_strip->id=(string)$row_strip->id;
                        }

                        $sql_paystack = DB::table('payment_settings')
                        ->crossJoin('tj_payment_method')
                        ->select('payment_settings.id',
                        'payment_settings.secret_key',
                        'payment_settings.public_key',
                        'payment_settings.callback_url',  'payment_settings.isEnabled',
                         'payment_settings.isSandboxEnabled', 'payment_settings.id_payment_method',
                        'tj_payment_method.libelle')
                        ->where('payment_settings.id_payment_method','=',DB::raw('tj_payment_method.id'))
                        ->where('tj_payment_method.libelle','=','PayStack')
                        ->get();


                            foreach ($sql_paystack as $row_paystack)
                            {
                                $row_paystack->id=(string)$row_paystack->id;
                            }
                            $sql_rezorpay = DB::table('payment_settings')
                            ->crossJoin('tj_payment_method')
                            ->select('payment_settings.id',  'payment_settings.key',
                            'payment_settings.secret_key',  'payment_settings.isEnabled',
                             'payment_settings.isSandboxEnabled', 'payment_settings.id_payment_method',
                            'tj_payment_method.libelle')
                            ->where('payment_settings.id_payment_method','=',DB::raw('tj_payment_method.id'))
                            ->where('tj_payment_method.libelle','=','Razorpay')
                            ->get();


                                foreach ($sql_rezorpay as $row_rezorpay)
                                {
                                   $row_rezorpay->id=(string)$row_rezorpay->id;
                                }
                                $sql_paytm = DB::table('payment_settings')
                                ->crossJoin('tj_payment_method')
                                ->select('payment_settings.id',  'payment_settings.merchant_Id', 'payment_settings.merchant_key',

                                'payment_settings.isEnabled',
                                 'payment_settings.isSandboxEnabled', 'payment_settings.id_payment_method',
                                 'tj_payment_method.libelle')
                                ->where('payment_settings.id_payment_method','=',DB::raw('tj_payment_method.id'))
                                ->where('tj_payment_method.libelle','=','Paytm')
                                ->get();


                                    foreach ($sql_paytm as $row_paytm)
                                    {
                                       $row_paytm->id=(string)$row_paytm->id;
                                    }
                                    $sql_paypal = DB::table('payment_settings')
                                    ->crossJoin('tj_payment_method')
                                    ->select('payment_settings.id', 'payment_settings.app_id',
                                    'payment_settings.secret_key', 'payment_settings.merchant_Id','payment_settings.private_key',
                                    'payment_settings.public_key',  'payment_settings.tokenization_key',
                                     'payment_settings.isEnabled',
                                    'payment_settings.isLive', 'payment_settings.id_payment_method',
                                    'payment_settings.username', 'payment_settings.password','tj_payment_method.libelle')
                                    ->where('payment_settings.id_payment_method','=',DB::raw('tj_payment_method.id'))
                                    ->where('tj_payment_method.libelle','=','PayPal')
                                    ->get();


                                        foreach ($sql_paypal as $row_paypal)
                                        {
                                             $row_paypal->id=(string)$row_paypal->id;
                                        }
                                        $sql_tax = DB::table('tj_tax')
                                        ->select('id','libelle',
                                         'type','value')
                                         ->where('statut','=','yes')
                                        ->get();


                                            foreach ($sql_tax as $tax)
                                            {

                                                $tax->name = $tax->libelle;
                                                $tax->tax_type = $tax->type;
                                                $tax->tax_amount = $tax->value;
                                                $tax->id=(string)$tax->id;
                                                unset($tax->libelle);
                                                unset($tax->type);
                                                unset($tax->value);

                                            }
                                            
                                            $sql_mercadopago = DB::table('payment_settings')
                                            ->crossJoin('tj_payment_method')
                                            ->select('payment_settings.id',  'payment_settings.public_key',
                                            'payment_settings.accesstoken',  'payment_settings.isEnabled',
                                             'payment_settings.isSandboxEnabled', 'payment_settings.id_payment_method')
                                            ->where('payment_settings.id_payment_method','=',DB::raw('tj_payment_method.id'))
                                            ->where('tj_payment_method.libelle','=','Mercadopago')
                                            ->get();


                                                foreach ($sql_mercadopago as $row_mercadopago)
                                                {
                                                    $row_mercadopago->id=(string)$row_mercadopago->id;
                                                }

    if($row_cash){
        $response['success'] = 'success';
        $response['error'] = null;
        $response['message'] = 'successfully';
        $response['Strip'] = $row_strip;
        $response['Cash'] = $row_cash;
        $response['PayFast'] = $row_payfast;
        $response['My Wallet'] = $row_wallet;
        $response['PayStack'] = $row_paystack;
        $response['FlutterWave'] = $row;
        $response['Razorpay'] = $row_rezorpay;
        $response['Mercadopago'] = $row_mercadopago;
        $response['Paytm'] = $row_paytm;
        $response['PayPal'] = $row_paypal;
        $response['tax'] = $tax;
    }

    return response()->json($response);
  }





}
