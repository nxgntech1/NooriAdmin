<?php

namespace App\Http\Controllers;

use App\Models\Complaints;
use App\Models\Note;
use App\Models\Currency;
use App\Models\ParcelOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParcelOrdersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all(Request $request, $id = null)
    {

        $currency = Currency::where('statut', 'yes')->first();
        if ($request->selected_search == 'userName' && $request->has('search') && $request->search != '') {
            $search = $request->input('search');
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->leftjoin('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.tip', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.id as driver_id', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom');
            if ($id != '' || $id != null) {
                $rides->where('tj_user_app.prenom', 'LIKE', '%' . $search . '%')->where('parcel_orders.id_conducteur', '=', $id);
                $rides->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%')->where('parcel_orders.id_conducteur', '=', $id);
                $rides->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%')->where('parcel_orders.id_conducteur', '=', $id);
            } else {
                $rides->where('tj_user_app.prenom', 'LIKE', '%' . $search . '%');
                $rides->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%');
                $rides->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%');
            }
            if ($id != '' || $id != null) {
                $rides->where('parcel_orders.id_conducteur', '=', $id);
            }

            $rides = $rides->orderBy('parcel_orders.id', 'desc')->paginate(20);
        } else if ($request->selected_search == 'driverName' && $request->has('search') && $request->search != '') {
            $search = $request->input('search');
            //$searchs = explode(" ", $search);
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->leftjoin('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.tip', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.id as driver_id', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom');
            if ($id != '' || $id != null) {
                $rides->where('tj_conducteur.prenom', 'LIKE', '%' . $search . '%')->where('parcel_orders.id_conducteur', '=', $id);
                $rides->orwhere('tj_conducteur.nom', 'LIKE', '%' . $search . '%')->where('parcel_orders.id_conducteur', '=', $id);
                $rides->orWhere(DB::raw('CONCAT(tj_conducteur.prenom, " ",tj_conducteur.nom)'), 'LIKE', '%' . $search . '%')->where('parcel_orders.id_conducteur', '=', $id);
            } else {
                $rides->where('tj_conducteur.prenom', 'LIKE', '%' . $search . '%');
                $rides->orwhere('tj_conducteur.nom', 'LIKE', '%' . $search . '%');
                $rides->orWhere(DB::raw('CONCAT(tj_conducteur.prenom, " ",tj_conducteur.nom)'), 'LIKE', '%' . $search . '%');

            }
            if ($id != '' || $id != null) {
                $rides->where('parcel_orders.id_conducteur', '=', $id);
            }

            $rides = $rides->orderBy('parcel_orders.id', 'desc')->paginate(20);
        } else if ($request->selected_search == 'status' && $request->has('ride_status') && $request->ride_status != '') {
            $search = $request->input('ride_status');
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->leftjoin('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.tip', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.id as driver_id', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom')
                ->where('parcel_orders.status', 'LIKE', '%' . $search . '%');
            if ($id != '' || $id != null) {
                $rides->where('parcel_orders.id_conducteur', '=', $id);
            }

            $rides = $rides->orderBy('parcel_orders.id', 'desc')->paginate(20);
        } else {
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->leftjoin('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.tip', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.id as driver_id', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom');
            if ($id != '' || $id != null) {
                $rides->where('parcel_orders.id_conducteur', '=', $id);
            }

            $rides = $rides->orderBy('parcel_orders.id', 'desc')->paginate(20);
        }

        return view("parcel_order.all")->with("rides", $rides)->with('currency', $currency)->with('id', $id);
    }


    public function confirmed(Request $request)
    {
        $currency = Currency::where('statut', 'yes')->first();
        if ($request->selected_search == 'userPrenom' && $request->has('search') && $request->search != '') {

            $search = $request->input('search');
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'parcel_orders.id_user_app', 'parcel_orders.id_conducteur')
                ->orderBy('parcel_orders.created_at', 'DESC')
                ->where('parcel_orders.status', 'confirmed')
                ->where(function ($query) use ($search) {
                    $query->orwhere('tj_user_app.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%');
                })
                ->paginate(20);
        } else if ($request->selected_search == 'driverPrenom' && $request->has('search') && $request->search != '') {

            $search = $request->input('search');
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'parcel_orders.id_user_app', 'parcel_orders.id_conducteur')
                ->orderBy('parcel_orders.created_at', 'DESC')
                ->where('parcel_orders.status', 'confirmed')
                ->where(function ($query) use ($search) {
                    $query->orWhere('tj_conducteur.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_conducteur.nom', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(tj_conducteur.prenom, " ",tj_conducteur.nom)'), 'LIKE', '%' . $search . '%');

                })
                ->paginate(20);
        } else if ($request->selected_search == 'status' && $request->has('ride_status') && $request->ride_status != '') {
            $search = $request->input('ride_status');
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'parcel_orders.id_user_app', 'parcel_orders.id_conducteur')
                ->orderBy('parcel_orders.created_at', 'DESC')
                ->where('parcel_orders.status', 'confirmed')
                ->where(function ($query) use ($search) {
                    $query->orwhere('parcel_orders.status', 'LIKE', '%' . $search . '%');

                })
                ->paginate(20);
        } else {
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.discount', 'parcel_orders.id_user_app', 'parcel_orders.id_conducteur', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom')
                ->orderBy('parcel_orders.created_at', 'DESC')
                ->where('parcel_orders.status', 'confirmed')
                ->paginate(20);
        }

        return view("parcel_order.confirmed")->with("rides", $rides)->with('currency', $currency);
    }


    public function rejected(Request $request)
    {
        $currency = Currency::where('statut', 'yes')->first();
        if ($request->selected_search == 'userPrenom' && $request->has('search') && $request->search != '') {
            $search = $request->input('search');
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'parcel_orders.id_user_app', 'parcel_orders.id_conducteur')
                ->orderBy('parcel_orders.created_at', 'DESC')
                ->Where(function ($query) {
                    $query->where('parcel_orders.status', 'rejected')
                        ->orwhere('parcel_orders.status', 'canceled');
                })
                ->where(function ($query) use ($search) {
                    $query->orwhere('tj_user_app.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%');
                })
                ->paginate(20);
        } else if ($request->selected_search == 'driverPrenom' && $request->has('search') && $request->search != '') {
            $search = $request->input('search');
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'parcel_orders.id_user_app', 'parcel_orders.id_conducteur')
                ->orderBy('parcel_orders.created_at', 'DESC')
                ->Where(function ($query) {
                    $query->where('parcel_orders.status', 'rejected')
                        ->orwhere('parcel_orders.status', 'canceled');
                })
                ->where(function ($query) use ($search) {
                    $query->orWhere('tj_conducteur.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_conducteur.nom', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(tj_conducteur.prenom, " ",tj_conducteur.nom)'), 'LIKE', '%' . $search . '%');
                })
                ->paginate(20);
        } else if ($request->selected_search == 'status' && $request->has('ride_status') && $request->ride_status != '') {
            $search = $request->input('ride_status');
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'parcel_orders.id_user_app', 'parcel_orders.id_conducteur')
                ->orderBy('parcel_orders.created_at', 'DESC')
                ->Where(function ($query) {
                    $query->where('parcel_orders.status', 'rejected')
                        ->orwhere('parcel_orders.status', 'canceled');
                })
                ->where(function ($query) use ($search) {
                    $query->orwhere('parcel_orders.status', 'LIKE', '%' . $search . '%');
                })
                ->paginate(20);
        } else {
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.discount', 'parcel_orders.id_user_app', 'parcel_orders.id_conducteur', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom')
                ->orderBy('parcel_orders.created_at', 'DESC')
                ->Where(function ($query) {
                    $query->where('parcel_orders.status', 'rejected')
                        ->orwhere('parcel_orders.status', 'canceled');
                })
                ->paginate(20);
        }
        return view("parcel_order.rejected")->with("rides", $rides)->with('currency', $currency);
    }

    public function completed(Request $request)
    {
        $currency = Currency::where('statut', 'yes')->first();
        if ($request->selected_search == 'userPrenom' && $request->has('search') && $request->search != '') {

            $search = $request->input('search');
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'parcel_orders.id_user_app', 'parcel_orders.id_conducteur')
                ->orderBy('parcel_orders.created_at', 'DESC')
                ->where('parcel_orders.status', 'completed')
                ->where(function ($query) use ($search) {
                    $query
                        ->orwhere('tj_user_app.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_user_app.nom', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%');
                })
                ->paginate(20);
        } else if ($request->selected_search == 'driverPrenom' && $request->has('search') && $request->search != '') {
            $search = $request->input('search');
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'parcel_orders.id_user_app', 'parcel_orders.id_conducteur')
                ->orderBy('parcel_orders.created_at', 'DESC')
                ->where('parcel_orders.status', 'completed')
                ->where(function ($query) use ($search) {
                    $query
                        ->orWhere('tj_conducteur.prenom', 'LIKE', '%' . $search . '%')
                        ->orwhere('tj_conducteur.nom', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(tj_conducteur.prenom, " ",tj_conducteur.nom)'), 'LIKE', '%' . $search . '%');

                })
                ->paginate(20);
        } else if ($request->selected_search == 'status' && $request->has('ride_status') && $request->ride_status != '') {
            $search = $request->input('ride_status');
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.discount', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'parcel_orders.id_user_app', 'parcel_orders.id_conducteur')
                ->orderBy('parcel_orders.created_at', 'DESC')
                ->where('parcel_orders.status', 'completed')
                ->where(function ($query) use ($search) {
                    $query
                        ->orwhere('parcel_orders.status', 'LIKE', '%' . $search . '%');

                })
                ->paginate(20);
        } else {
            $rides = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
                ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
                ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.admin_commission', 'parcel_orders.tax', 'parcel_orders.discount', 'parcel_orders.id_user_app', 'parcel_orders.id_conducteur', 'parcel_orders.payment_status', 'parcel_orders.distance', 'parcel_orders.amount', 'parcel_orders.created_at', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom')
                ->orderBy('parcel_orders.created_at', 'DESC')
                ->where('parcel_orders.status', 'completed')
                ->paginate(20);

        }
        return view("parcel_order.completed")->with("rides", $rides)->with('currency', $currency);
    }


    public function deleteRide($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $complaint = Complaints::where('id_parcel', $id[$i]);
                    if ($complaint) {
                        $complaint->delete();
                    }
                    $Note = Note::where('parcel_id', $id[$i]);
                    if ($Note) {
                        $Note->delete();
                    }

                    $user = ParcelOrder::find($id[$i]);
                    $user->delete();
                }

            } else {
                $complaint = Complaints::where('id_parcel', $id);
                if ($complaint) {
                    $complaint->delete();
                }
                $Note = Note::where('parcel_id', $id);
                if ($Note) {
                    $Note->delete();
                }

                $user = ParcelOrder::find($id);
                $user->delete();
            }

        }

        return redirect()->back();
    }

    public function show($id)
    {

        $currency = Currency::where('statut', 'yes')->first();

        $ride = ParcelOrder::join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
            ->leftjoin('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
            ->join('tj_payment_method', 'parcel_orders.id_payment_method', '=', 'tj_payment_method.id')
            ->leftjoin('parcel_category', 'parcel_orders.parcel_type', '=', 'parcel_category.id')
            ->select('parcel_orders.*')
            ->addSelect('tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_conducteur.phone as driver_phone', 'tj_conducteur.email as driver_email', 'tj_conducteur.photo_path as driver_photo')
            ->addSelect('tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_user_app.phone as user_phone', 'tj_user_app.email as user_email', 'tj_user_app.photo_path')
            ->addSelect('tj_payment_method.libelle', 'tj_payment_method.image')
            ->addSelect('parcel_category.title')
            ->where('parcel_orders.id', $id)->first();
        $parcel_image = [];
        if ($ride->parcel_image != '') {
            $parcelImage = json_decode($ride->parcel_image, true);

            foreach ($parcelImage as $value) {
                if (file_exists(public_path('images/parcel_order/' . '/' . $value))) {
                    $image = asset('images/parcel_order/') . '/' . $value;
                }
                array_push($parcel_image, $image);
            }
        }
            
        $amount = $ride->amount;
        $tax = json_decode($ride->tax, true);
        $discount = $ride->discount;
        $tip = $ride->tip;
        $totalAmount = floatval($amount) - floatval($discount);
        $totalTaxAmount = 0;
        $taxHtml = '';
        if (!empty($tax)) {
            for ($i = 0; $i < sizeof($tax); $i++) {
                $data = $tax[$i];
                if ($data['type'] == "Percentage") {
                    $taxValue = (floatval($data['value']) * $totalAmount) / 100;
                    $taxlabel = $data['libelle'];
                    $value = $data['value'] . "%";
                } else {
                    $taxValue = floatval($data['value']);
                    $taxlabel = $data['libelle'];
                    if ($currency->symbol_at_right == "true") {
                        $value = number_format($data['value'], $currency->decimal_digit) . "" . $currency->symbole;
                    } else {
                        $value = $currency->symbole . "" . number_format($data['value'], $currency->decimal_digit);
                    }
                }
                $totalTaxAmount += floatval(number_format($taxValue, $currency->decimal_digit));
                if ($currency->symbol_at_right == "true") {
                    $taxValueAmount = number_format($taxValue, $currency->decimal_digit) . "" . $currency->symbole;
                } else {
                    $taxValueAmount = $currency->symbole . "" . number_format($taxValue, $currency->decimal_digit);
                }
                $taxHtml = $taxHtml . "<tr><td class='label'>" . $taxlabel . "(" . $value . ")</td><td><span style='color:green'>+" . $taxValueAmount . "<span></td></tr>";
            }
            $totalAmount = floatval($totalAmount) + floatval($totalTaxAmount);

        }
        $totalAmount = floatval($totalAmount) + floatval($tip);
        $customer_review = DB::table('tj_note')->where('tj_note.parcel_id', $id)->select('comment','niveau')->get();
       
        $complaints = Complaints::select('title', 'description','user_type')->where('id_parcel', $id)->get();

        $driverRating = "0.0";
        if(!empty($ride->id_conducteur)){
            $id_conducteur = $ride->id_conducteur;
            $driver_rating = DB::table('tj_note')
                ->select(DB::raw("COUNT(id) as ratingCount"), DB::raw("SUM(niveau) as ratingSum"))
                ->where('id_conducteur', '=', $id_conducteur)
                ->first();
            if (!empty($driver_rating)) {
                if ($driver_rating->ratingCount > 0) {
                    $driverRating = number_format(($driver_rating->ratingSum / $driver_rating->ratingCount), 1);
                }
            }

        }

        return view("parcel_order.show")->with("ride", $ride)->with("currency", $currency)
            ->with("customer_review", $customer_review)
            ->with("complaints", $complaints)
            ->with('taxHtml', $taxHtml)
            ->with('totalAmount', $totalAmount)
            ->with('driverRating', $driverRating)
            ->with('parcel_image',$parcel_image);

    }

    public function updateRide(Request $request, $id)
    {

        $rides = ParcelOrder::find($id);
        if ($rides) {
            $rides->status = $request->input('order_status');
            $rides->save();
        }

        return redirect()->back();

    }

}
