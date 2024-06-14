<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\VehicleType;
use DB;
use Illuminate\Http\Request;

class TransactionController extends Controller
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

    public function getData(Request $request)
    {
        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $id_user_app = $request->get('id_user_app');

        if (!empty($id_user_app)) {
            $sql = DB::table('tj_transaction')
                ->where('tj_transaction.id_user_app', '=', $id_user_app)
                ->orderBy('tj_transaction.id', 'desc')
                ->get();
            foreach ($sql as $row) {
                $row->creer = date("d", strtotime($row->creer)) . " " . $months[date("F", strtotime($row->creer))] . ", " . date("Y", strtotime($row->creer));
                $ride_id = $row->ride_id;

                $ride = DB::table('tj_requete')
                ->select('tj_requete.transaction_id','tj_requete.date_retour')
                ->where('id','=', $ride_id)
                ->get();
                foreach ($ride as $row_ride) {
                    $row->transaction_id = $row_ride->transaction_id;
                    $row->date_retour = date("d", strtotime($row_ride->date_retour)) . " " . $months[date("F", strtotime($row_ride->date_retour))] . ", " . date("Y", strtotime($row_ride->date_retour));
                }
                $row->id=(string)$row->id;
                $output[] = $row;


                if (!empty($row)) {

                    $response['success'] = 'success';
                    $response['error'] = null;
                    $response['message'] = 'Sucessfully';
                    $response['data'] = $output;
                } else {
                    $response['success'] = 'Failed';
                    $response['error'] = 'Failed to fetch data';
                    $response['message'] = null;
                }

            }
            if (empty($row)) {
                $response['success'] = 'Failed';
                $response['error'] = 'No Data Found';
                $response['message'] = null;
            }
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Id Required';
        }
        return response()->json($response);

    }

}
