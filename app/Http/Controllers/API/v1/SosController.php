<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Sos;
use DB;
use Illuminate\Http\Request;

class SosController extends Controller
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


    public function storeSos(Request $request)
    {

        $ride_id = $request->get('ride_id');
        $lat = $request->get('latitude');
        $long = $request->get('longitude');
        $creer = date('Y-m-d H:i:s');

        $status = "initiated";

        $check = DB::table('tj_requete')->select('id')->where('id', '=', $ride_id)->get();
        if (count($check) > 0) {

            $check_sos = DB::table('tj_sos')->where('ride_id', '=', $ride_id)->get();

            if (count($check_sos) > 0) {
                $response['success'] = 'Failed';
                $response['error'] = 'SOS Request Already Submitted';
            } else {
                $insertdata = DB::insert("insert into tj_sos(ride_id,latitude,longitude,creer,status)
                                values ('" . $ride_id . "','" . $lat . "','" . $long . "','" . $creer . "','" . $status . "') ");
                if ($insertdata) {
                    $id = DB::getPdo()->lastInsertId();
                    $get_user = Sos::where('id', $id)->first();
                    $row = $get_user->toArray();
                    $row['id']=(string)$row['id'];
                    $response['success'] = 'success';
                    $response['error'] = null;
                    $response['message'] = 'SOS Request Submitted ';
                    $response['data'] = $row;
                } else {
                    $response['success'] = 'Failed';
                    $response['error'] = 'Unable To Request SOS';
                }
            }

        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'ID Not Found';
        }

        return response()->json($response);
    }

}
