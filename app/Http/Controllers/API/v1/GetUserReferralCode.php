<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\Settings;
use DB;
use Illuminate\Http\Request;

class GetUserReferralCode extends Controller
{

    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getData(Request $request)
    {

        $user_id = $request->get('id_user');

        $referral = Referral::where('user_id', $user_id)->first();
        if (!empty($referral)) {
            $setting = Settings::first();
            $row['referral_amount'] = (string)$setting->referral_amount;
            $row['referral_code'] = $referral->referral_code;
            $response['success'] = 'success';
            $response['error'] = null;
            $response['message'] = 'successfully updated';
            $response['data'] = $row;

        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Not Found';
        }

        return response()->json($response);
    }

}
