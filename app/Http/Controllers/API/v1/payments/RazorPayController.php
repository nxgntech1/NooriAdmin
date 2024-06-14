<?php

namespace App\Http\Controllers\api\v1\payments;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Flash;
use Razorpay\Api\Api;
use Response;

class RazorPayController extends Controller
{


    public function __init()
    {
        
    }

    public function createOrderid(Request $request)
    {
        $input=$request->all();
        $amount=$input['amount'];
        $receipt_id=$input['receipt_id'];
        $currency=$input['currency'];
        $razorpaykey=$input['razorpaykey'];
        $razorPaySecret=$input['razorPaySecret'];
        $client = new Api($razorpaykey, $razorPaySecret);

        try {
            
            $order  = $client->order->create([
          'receipt'         => $receipt_id,
          'amount'          => $amount,
          'currency'        => $currency
            ]);

            $attributes=$this->getProtectedValue($order,'attributes');
            return response()->json($attributes);

        }catch(Exception $e) {
            return response()->json(array('faild' => $e->getMessage()));
        }

        
        
    }

    public function getProtectedValue($obj, $name) {
      $array = (array)$obj;
      $prefix = chr(0).'*'.chr(0);
      return $array[$prefix.$name];
    }

}

