<?php

namespace App\Http\Controllers;

use App\Models\Complaints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComplaintsController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if ($request->has('search') && $request->search != '' && $request->selected_search == 'title') {
            $search = $request->input('search');
            $complaints = DB::table('tj_complaints')
                ->join('tj_user_app', 'tj_complaints.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_complaints.id_conducteur', '=', 'tj_conducteur.id')
                ->leftjoin('parcel_orders', 'tj_complaints.id_parcel', '=', 'parcel_orders.id')
                ->leftjoin('tj_requete', 'tj_complaints.id_ride', '=', 'tj_requete.id')
                ->select('tj_complaints.id', 'tj_complaints.title', 'tj_complaints.description', 'tj_complaints.user_type','tj_complaints.created', 'tj_complaints.id_ride', 'tj_complaints.id_parcel', 'tj_complaints.status', 'tj_conducteur.prenom as driverName', 'tj_user_app.prenom as userName', 'tj_conducteur.id as driverId', 'tj_user_app.id as userId','parcel_orders.id as parcelId','tj_requete.id as rideId')
                ->orderBy('tj_complaints.created', 'DESC')
                ->where('tj_complaints.title', 'LIKE', '%' . $search . '%')
                ->paginate(20);

        } elseif ($request->has('search') && $request->search != '' && $request->selected_search == 'message') {
            $search = $request->input('search');
            $complaints = DB::table('tj_complaints')
                ->join('tj_user_app', 'tj_complaints.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_complaints.id_conducteur', '=', 'tj_conducteur.id')
                ->leftjoin('parcel_orders', 'tj_complaints.id_parcel', '=', 'parcel_orders.id')
                ->leftjoin('tj_requete', 'tj_complaints.id_ride', '=', 'tj_requete.id')
                ->select('tj_complaints.id', 'tj_complaints.title', 'tj_complaints.description', 'tj_complaints.user_type', 'tj_complaints.created', 'tj_complaints.id_ride', 'tj_complaints.id_parcel', 'tj_complaints.status', 'tj_conducteur.prenom as driverName', 'tj_user_app.prenom as userName', 'tj_conducteur.id as driverId', 'tj_user_app.id as userId', 'parcel_orders.id as parcelId', 'tj_requete.id as rideId')
                ->orderBy('tj_complaints.created', 'DESC')
                ->where('tj_complaints.description', 'LIKE', '%' . $search . '%')
                ->paginate(20);
        }       
         elseif ($request->has('status') && $request->status != '' && $request->selected_search == 'status') {
            $search = $request->input('status');
            $complaints = DB::table('tj_complaints')
                ->join('tj_user_app', 'tj_complaints.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_complaints.id_conducteur', '=', 'tj_conducteur.id')
                ->leftjoin('parcel_orders', 'tj_complaints.id_parcel', '=', 'parcel_orders.id')
                ->leftjoin('tj_requete', 'tj_complaints.id_ride', '=', 'tj_requete.id')
                ->select('tj_complaints.id', 'tj_complaints.title', 'tj_complaints.description', 'tj_complaints.user_type', 'tj_complaints.created', 'tj_complaints.id_ride', 'tj_complaints.id_parcel', 'tj_complaints.status', 'tj_conducteur.prenom as driverName', 'tj_user_app.prenom as userName', 'tj_conducteur.id as driverId', 'tj_user_app.id as userId', 'parcel_orders.id as parcelId', 'tj_requete.id as rideId')
                ->orderBy('tj_complaints.created', 'DESC')
                ->where('tj_complaints.status', 'LIKE', '%' . $search . '%')
                ->paginate(20);        
        } else {
            $complaints = DB::table('tj_complaints')
                ->join('tj_user_app', 'tj_complaints.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_complaints.id_conducteur', '=', 'tj_conducteur.id')
                ->leftjoin('parcel_orders', 'tj_complaints.id_parcel', '=', 'parcel_orders.id')
                ->leftjoin('tj_requete', 'tj_complaints.id_ride', '=', 'tj_requete.id')
                ->select('tj_complaints.id', 'tj_complaints.title', 'tj_complaints.description', 'tj_complaints.user_type', 'tj_complaints.created', 'tj_complaints.id_ride', 'tj_complaints.id_parcel', 'tj_complaints.status', 'tj_conducteur.prenom as driverName', 'tj_user_app.prenom as userName', 'tj_conducteur.id as driverId', 'tj_user_app.id as userId', 'parcel_orders.id as parcelId', 'tj_requete.id as rideId')
                ->orderBy('tj_complaints.created', 'DESC')
                ->paginate(20);

        }

        return view("complaints.index")->with("complaints", $complaints);
    }

    public function deleteComplaints($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $user = Complaints::find($id[$i]);
                    $user->delete();
                }

            } else {
                $user = Complaints::find($id);
                $user->delete();
            }

        }

        return redirect()->back();
    }

    public function show($id)
    {

        $complaints = Complaints::join('tj_user_app', 'tj_complaints.id_user_app', '=', 'tj_user_app.id')
            ->join('tj_conducteur', 'tj_complaints.id_conducteur', '=', 'tj_conducteur.id')
            ->select('tj_complaints.*')
            ->addSelect('tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_conducteur.phone as driver_phone', 'tj_conducteur.email as driver_email')
            ->addSelect('tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_user_app.phone as user_phone', 'tj_user_app.email as user_email')
            ->where('tj_complaints.id', $id)->first();
       
        return response()->json($complaints);

        //return view("complaints.show")->with("complaints", $complaints);
    }
    public function update(Request $request)
    {
        $id = $request->get('complaint_id');
        $status = $request->get('complaint_status');
        Complaints::where('id', $id)
                    ->update(['status' => $status]);
        return redirect('complaints');
    }
}