<?php

namespace App\Http\Controllers;

use App\Models\Sos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SosController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function index(Request $request)
    {


        if ($request->has('search') && $request->search != '') {
            $search = $request->input('search');
            $sos = DB::table('tj_sos')
                ->join('tj_requete', 'tj_sos.ride_id', '=', 'tj_requete.id')
                ->join('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->select('tj_user_app.prenom as userPreNom', 'tj_user_app.nom as userNom', 'tj_user_app.phone as user_phone', 'tj_user_app.photo_path as user_photo', 'tj_requete.latitude_depart', 'tj_requete.longitude_depart', 'tj_requete.destination_name')
                ->addSelect('tj_conducteur.nom as driverNom', 'tj_conducteur.prenom as driverPreNom', 'tj_conducteur.phone as driver_phone', 'tj_conducteur.photo_path as driver_photo')
                ->addSelect('tj_requete.*', 'tj_sos.*')
                ->where('tj_sos.status', 'LIKE', '%' . $search . '%')
                ->paginate(10);

        }elseif ($request->has('status') && $request->status != '' && $request->selected_search=='status') {
            $search = $request->input('status');
            $sos = DB::table('tj_sos')
                ->join('tj_requete', 'tj_sos.ride_id', '=', 'tj_requete.id')
                ->join('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
                ->select('tj_user_app.prenom as userPreNom', 'tj_user_app.nom as userNom', 'tj_user_app.phone as user_phone', 'tj_user_app.photo_path as user_photo', 'tj_requete.latitude_depart', 'tj_requete.longitude_depart', 'tj_requete.destination_name')
                ->addSelect('tj_conducteur.nom as driverNom', 'tj_conducteur.prenom as driverPreNom', 'tj_conducteur.phone as driver_phone', 'tj_conducteur.photo_path as driver_photo')
                ->addSelect('tj_requete.*', 'tj_sos.*')
                ->where('tj_sos.status', 'LIKE', '%' . $search . '%')
                ->paginate(10);

        } else {
          $sos = DB::table('tj_sos')
              ->join('tj_requete', 'tj_sos.ride_id', '=', 'tj_requete.id')
              ->join('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
              ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
              ->select('tj_user_app.prenom as userPreNom', 'tj_user_app.nom as userNom', 'tj_user_app.phone as user_phone', 'tj_user_app.photo_path as user_photo', 'tj_requete.latitude_depart', 'tj_requete.longitude_depart', 'tj_requete.destination_name')
              ->addSelect('tj_conducteur.nom as driverNom', 'tj_conducteur.prenom as driverPreNom', 'tj_conducteur.phone as driver_phone', 'tj_conducteur.photo_path as driver_photo')
              ->addSelect('tj_requete.*', 'tj_sos.*')
              ->paginate(10);
        }


        return view("sos.index")->with("sos", $sos);
    }

    public function delete()
    {

    }

    public function show($id)
    {
        $sos = DB::table('tj_sos')
            ->join('tj_requete', 'tj_sos.ride_id', '=', 'tj_requete.id')
            ->join('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
            ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
            ->select('tj_user_app.id as userID','tj_user_app.prenom as userPreNom', 'tj_user_app.nom as userNom', 'tj_user_app.prenom as userFirstNom', 'tj_user_app.phone as user_phone', 'tj_user_app.photo_path as user_photo', 'tj_requete.latitude_depart', 'tj_requete.longitude_depart', 'tj_requete.latitude_arrivee', 'tj_requete.longitude_arrivee', 'tj_requete.destination_name')
            ->addSelect('tj_conducteur.id as driverID','tj_conducteur.nom as driverNom', 'tj_conducteur.prenom as driverPreNom', 'tj_conducteur.phone as driver_phone', 'tj_conducteur.photo_path as driver_photo')
            ->addSelect('tj_requete.*', 'tj_sos.*')
            ->where('tj_sos.id', $id)->first();
        return view('sos.show')->with('sos', $sos);
    }

    public function deleteSos($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $user = Sos::find($id[$i]);
                    $user->delete();
                }

            } else {
                $user = Sos::find($id);
                $user->delete();
            }

        }

        return redirect()->back();
    }

    public function sosUpdate(Request $request, $id)
    {

        $sosData = Sos::find($id);

        if ($sosData) {
            $sosData->status = $request->input('order_status');
            $sosData->save();
        }

        return redirect()->back();

    }
}
