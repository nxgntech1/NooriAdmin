<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\ParcelOrder;
use App\Models\UserApp;
use DB;
use Illuminate\Http\Request;

class GetParcelOrdersController extends Controller
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

    public function getDriverParcel(Request $request)
    {
        $months = array("January" => 'Jan', "February" => 'Fev', "March" => 'Mar', "April" => 'Avr', "May" => 'Mai', "June" => 'Jun', "July" => 'Jul', "August" => 'Aou', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $id_driver = $request->get('id_driver');

        if (!empty($id_driver)) {
            $sql = ParcelOrder::Join('tj_payment_method', 'tj_payment_method.id', '=', 'parcel_orders.id_payment_method')
                ->leftjoin('parcel_category', 'parcel_category.id', '=', 'parcel_orders.parcel_type')
                ->select('parcel_orders.*', 'tj_payment_method.libelle', 'tj_payment_method.image as payment_image', 'parcel_category.title')
                ->where('parcel_orders.id_conducteur', '=', $id_driver)
                ->orderBy('parcel_orders.id', 'desc')
                ->get();

            $output = array();
            foreach ($sql as $row) {
                $id_user_app = $row->id_user_app;
                $row->id = (string)$row->id;
                $row->tax = json_decode($row->tax, true);

                $sql_cond = Driver::select('nom as nomConducteur', 'prenom as prenomConducteur', 'phone', 'photo_path')
                    ->where('id', '=', $id_driver)
                    ->get();

                $sql_user = UserApp::select('nom', 'prenom', 'phone', 'photo_path')
                    ->where('id', '=', $id_user_app)
                    ->first();

                $row->user_phone = ($sql_user->phone) ? $sql_user->phone : "";
                $row->user_name = ($sql_user->prenom) ? $sql_user->prenom . " " . $sql_user->nom : "";
                $user_photo = "";
                if ($sql_user->photo_path != '') {
                    if (file_exists(public_path('assets/images/users/' . $sql_user->photo_path))) {
                        $user_photo = asset('assets/images/users/' . $sql_user->photo_path);
                    } else {
                        $user_photo = asset('assets/images/placeholder_image.jpg');

                    }
                }
                $row->user_photo = $user_photo;

                foreach ($sql_cond as $row_cond)

                    // Nb avis conducteur
                    $sql_nb_avis = DB::table('tj_note')
                        ->select(DB::raw("COUNT(id) as nb_avis"), DB::raw("SUM(niveau) as somme"))
                        ->where('id_conducteur', '=', $id_driver)
                        ->get();

                if (!empty($sql_nb_avis)) {
                    foreach ($sql_nb_avis as $row_nb_avis)
                        $somme = $row_nb_avis->somme;
                    $nb_avis = $row_nb_avis->nb_avis;
                    if ($nb_avis != "0")
                        $moyenne = $somme / $nb_avis;
                    else
                        $moyenne = 0;
                } else {
                    $somme = "0";
                    $nb_avis = "0";
                    $moyenne = 0;
                }
                $sql_nb_avis_driver = DB::table('tj_user_note')
                    ->select(DB::raw("COUNT(id) as nb_avis_driver"), DB::raw("SUM(niveau_driver) as somme_driver"))
                    ->where('id_user_app', '=', $id_user_app)
                    ->get();
                if (!empty($sql_nb_avis_driver)) {
                    foreach ($sql_nb_avis_driver as $row_nb_avis_driver)
                        $somme_driver = $row_nb_avis_driver->somme_driver;
                    $nb_avis_driver = $row_nb_avis_driver->nb_avis_driver;
                    if ($nb_avis_driver != "0")
                        $moyenne_driver = $somme_driver / $nb_avis_driver;
                    else
                        $moyenne_driver = 0;
                } else {
                    $somme_driver = "0";
                    $nb_avis_driver = "0";
                    $moyenne_driver = 0;
                }

                // Note conducteur
                $sql_note = DB::table('tj_note')
                    ->select('niveau', 'comment')
                    ->where('id_conducteur', '=', $id_driver)
                    ->where('id_user_app', '=', $id_user_app)
                    ->get();
                foreach ($sql_note as $row_note) {
                    if ($row_note) {
                        $row->comment = $row_note->comment;
                    } else {
                        $row->comment = "";
                    }
                }
                // Note user
                $sql_note_driver = DB::table('tj_user_note')
                    ->select('niveau_driver', 'comment')
                    ->where('id_user_app', '=', $id_user_app)
                    ->where('id_conducteur', '=', $id_driver)
                    ->get();
                foreach ($sql_note_driver as $row_note_driver) {
                    if ($row_note_driver) {
                        $row->comment_driver = $row_note_driver->comment;
                    } else {
                        $row->comment_driver = "";
                    }
                }

                $row->driver_phone = ($row_cond->phone) ? $row_cond->phone : "";
                $row->driver_name = ($row_cond->prenomConducteur) ? $row_cond->prenomConducteur . " " . $row_cond->nomConducteur : "";

                $driver_photo = "";
                if ($row_cond->photo_path != '') {
                    if (file_exists(public_path('assets/images/driver/' . $row_cond->photo_path))) {
                        $driver_photo = asset('assets/images/driver/' . $row_cond->photo_path);
                    } else {
                        $driver_photo = asset('assets/images/placeholder_image.jpg');
                    }
                }
                $row->driver_photo = $driver_photo;

                $row->moyenne = number_format((float)$moyenne, 1);
                $row->moyenne_driver = number_format((float)$moyenne_driver, 1);

                $image_parcel = [];
                if ($row->parcel_image != '') {
                    $parcelImage = json_decode($row->parcel_image, true);

                    foreach ($parcelImage as $value) {
                        if (file_exists(public_path('images/parcel_order/' . '/' . $value))) {
                            $image = asset('images/parcel_order/') . '/' . $value;
                        }
                        array_push($image_parcel, $image);
                    }
                    if (!empty($image_parcel)) {
                        $row->parcel_image = $image_parcel;
                    } else {
                        $row->parcel_image = asset('assets/images/placeholder_image.jpg');
                    }
                }

                if (empty($row->parcel_image)) {
                    $row->parcel_image = $image_parcel;
                }

                if ($row->payment_image != '') {
                    if (file_exists(public_path('assets/images/payment_method' . '/' . $row->payment_image))) {
                        $image_payment = asset('assets/images/payment_method') . '/' . $row->payment_image;
                    } else {
                        $image_payment = asset('assets/images/placeholder_image.jpg');
                    }
                    $row->payment_image = $image_payment;
                }
                $output[] = $row;
            }
            if (!empty($row)) {
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'successfully fetched data';
                $response['data'] = $output;
            } else {
                $response['success'] = 'Failed';
                $response['error'] = 'failed to fetch data';
            }
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Id required';
        }

        return response()->json($response);
    }

    public function getUserParcel(Request $request)
    {
        $months = array("January" => 'Jan', "February" => 'Fev', "March" => 'Mar', "April" => 'Avr', "May" => 'Mai', "June" => 'Jun', "July" => 'Jul', "August" => 'Aou', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $id_user_app = $request->get('id_user_app');
        if (!empty($id_user_app)) {
            $sql = ParcelOrder::Join('tj_payment_method', 'tj_payment_method.id', '=', 'parcel_orders.id_payment_method')
                ->leftjoin('parcel_category', 'parcel_category.id', '=', 'parcel_orders.parcel_type')
                ->Join('tj_user_app', 'tj_user_app.id', '=', 'parcel_orders.id_user_app')
                ->leftJoin('tj_conducteur', 'tj_conducteur.id', '=', 'parcel_orders.id_conducteur')
                ->select('parcel_orders.*',
                    'tj_payment_method.libelle',
                    'tj_payment_method.image as payment_image',
                    'parcel_category.title',
                    'tj_user_app.phone',
                    'tj_user_app.nom',
                    'tj_user_app.prenom',
                    'tj_user_app.photo_path as user_photo',
                    'tj_conducteur.nom as nomConducteur',
                    'tj_conducteur.prenom as prenomConducteur',
                    'tj_conducteur.phone as driverPhone',
                    'tj_conducteur.photo_path'
                )
                ->where('parcel_orders.id_user_app', '=', $id_user_app)
                ->orderBy('parcel_orders.id', 'desc')
                ->get();

            $output = array();
            
            foreach ($sql as $row) {
                $id_user_app = $row->id_user_app;
                $row->id = (string)$row->id;
                $row->tax = json_decode($row->tax, true);
                $id_driver = $row->id_conducteur;
                // Nb avis conducteur
                $sql_nb_avis = DB::table('tj_note')
                    ->select(DB::raw("COUNT(id) as nb_avis"), DB::raw("SUM(niveau) as somme"))
                    ->where('id_conducteur', '=', $id_driver)
                    ->get();

                if (!empty($sql_nb_avis)) {
                    foreach ($sql_nb_avis as $row_nb_avis)
                        $somme = $row_nb_avis->somme;
                    $nb_avis = $row_nb_avis->nb_avis;
                    if ($nb_avis != "0")
                        $moyenne = $somme / $nb_avis;
                    else
                        $moyenne = 0;
                } else {
                    $somme = "0";
                    $nb_avis = "0";
                    $moyenne = 0;
                }
                $sql_nb_avis_driver = DB::table('tj_user_note')
                    ->select(DB::raw("COUNT(id) as nb_avis_driver"), DB::raw("SUM(niveau_driver) as somme_driver"))
                    ->where('id_user_app', '=', $id_user_app)
                    ->get();
                if (!empty($sql_nb_avis_driver)) {
                    foreach ($sql_nb_avis_driver as $row_nb_avis_driver)
                        $somme_driver = $row_nb_avis_driver->somme_driver;
                    $nb_avis_driver = $row_nb_avis_driver->nb_avis_driver;
                    if ($nb_avis_driver != "0")
                        $moyenne_driver = $somme_driver / $nb_avis_driver;
                    else
                        $moyenne_driver = 0;
                } else {
                    $somme_driver = "0";
                    $nb_avis_driver = "0";
                    $moyenne_driver = 0;
                }

                // Note conducteur
                $sql_note = DB::table('tj_note')
                    ->select('niveau', 'comment')
                    ->where('id_conducteur', '=', $id_driver)
                    ->where('id_user_app', '=', $id_user_app)
                    ->get();
                foreach ($sql_note as $row_note) {
                    if ($row_note) {
                        $row->comment = $row_note->comment;
                    } else {
                        $row->comment = "";
                    }
                }
                // Note user
                $sql_note_driver = DB::table('tj_user_note')
                    ->select('niveau_driver', 'comment')
                    ->where('id_user_app', '=', $id_user_app)
                    ->where('id_conducteur', '=', $id_driver)
                    ->get();
                foreach ($sql_note_driver as $row_note_driver) {
                    if ($row_note_driver) {
                        $row->comment_driver = $row_note_driver->comment;
                    } else {
                        $row->comment_driver = "";
                    }
                }

                $row->moyenne = number_format((float)$moyenne, 1);
                $row->moyenne_driver = number_format((float)$moyenne_driver, 1);
                $row->user_phone = ($row->phone) ? $row->phone : "";
                $row->user_name = ($row->prenom) ? $row->prenom . " " . $row->nom : "";
                $user_photo = "";

                if ($row->user_photo != '') {
                    if (file_exists(public_path('assets/images/users/' . $row->user_photo))) {
                        $user_photo = asset('assets/images/users/' . $row->user_photo);
                    } else {
                        $user_photo = asset('assets/images/placeholder_image.jpg');
                    }
                }

                $row->user_photo = $user_photo;

                $row->driver_phone = ($row->driverPhone) ? $row->driverPhone : "";
                $row->driver_name = ($row->prenomConducteur) ? $row->prenomConducteur . " " . $row->nomConducteur : "";

                $driver_photo = "";
                if ($row->photo_path != '') {
                    if (file_exists(public_path('assets/images/driver/' . $row->photo_path))) {
                        $driver_photo = asset('assets/images/driver/' . $row->photo_path);
                    } else {
                        $driver_photo = asset('assets/images/placeholder_image.jpg');
                    }
                }

                $row->driver_photo = $driver_photo;

                if ($row->payment_image != '') {
                    if (file_exists(public_path('assets/images/payment_method' . '/' . $row->payment_image))) {
                        $image_payment = asset('assets/images/payment_method') . '/' . $row->payment_image;
                    } else {
                        $image_payment = asset('assets/images/placeholder_image.jpg');
                    }
                    $row->payment_image = $image_payment;
                }

                $image_parcel = [];
                if ($row->parcel_image != '') {
                    $parcelImage = json_decode($row->parcel_image, true);

                    foreach ($parcelImage as $value) {
                        if (file_exists(public_path('images/parcel_order/' . '/' . $value))) {
                            $image = asset('images/parcel_order/') . '/' . $value;
                        }
                        array_push($image_parcel, $image);
                    }
                    if (!empty($image_parcel)) {
                        $row->parcel_image = $image_parcel;
                    } else {
                        $row->parcel_image = asset('assets/images/placeholder_image.jpg');
                    }
                }

                if (empty($row->parcel_image)) {
                    $row->parcel_image = $image_parcel;
                }

                $output[] = $row;
            }
            if (!empty($output)) {
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'successfully fetched data';
                $response['data'] = $output;
            } else {
                $response['success'] = 'Failed';
                $response['error'] = 'failed to fetch data';
            }
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Id required';
        }

        return response()->json($response);
    }

    public function getParcelDetail(Request $request)
    {
        $parcel_id = $request->get('parcel_id');

        if (!empty($parcel_id)) {

            $row = ParcelOrder::Join('tj_user_app', 'tj_user_app.id', '=', 'parcel_orders.id_user_app')
                ->leftjoin('tj_conducteur', 'tj_conducteur.id', '=', 'parcel_orders.id_conducteur')
                ->leftJoin('parcel_category', 'parcel_category.id', '=', 'parcel_orders.parcel_type')
                ->select('parcel_orders.*',
                    'tj_user_app.nom',
                    'tj_user_app.prenom',
                    'tj_user_app.phone as user_phone',
                    'tj_user_app.photo_path as user_photo',
                    'tj_conducteur.nom as driverNom',
                    'tj_conducteur.prenom as driverPreNom',
                    'tj_conducteur.phone as driverPhone',
                    'tj_conducteur.photo_path as driver_photo',
                    'parcel_category.title')
                ->where('parcel_orders.id', $parcel_id)->first();

            if ($row) {
                $image_parcel = [];

                $row->driver_name = ($row->prenom) ? $row->prenom . " " . $row->nom : "";
                $row->driver_name = ($row->driverPreNom) ? $row->driverPreNom . " " . $row->driverNom : "";

                if ($row->parcel_image != '') {
                    $parcelImage = json_decode($row->parcel_image, true);

                    foreach ($parcelImage as $value) {
                        if (file_exists(public_path('images/parcel_order/' . '/' . $value))) {
                            $image = asset('images/parcel_order/') . '/' . $value;
                        }
                        array_push($image_parcel, $image);
                    }
                    if (!empty($image_parcel)) {
                        $row->parcel_image = $image_parcel;
                    } else {
                        $row->parcel_image = asset('assets/images/placeholder_image.jpg');
                    }
                }

                if (empty($row->parcel_image)) {
                    $row->parcel_image = $image_parcel;
                }
                if ($row->user_photo != '') {
                    if (file_exists(public_path('assets/images/users' . '/' . $row->user_photo))) {
                        $user_photo = asset('assets/images/users') . '/' . $row->user_photo;
                    } else {
                        $user_photo = asset('assets/images/placeholder_image.jpg');
                    }
                    $row->user_photo = $user_photo;
                }
                if ($row->driver_photo != '') {
                    if (file_exists(public_path('assets/images/drivers' . '/' . $row->driver_photo))) {
                        $driver_photo = asset('assets/images/drivers') . '/' . $row->driver_photo;
                    } else {
                        $driver_photo = asset('assets/images/placeholder_image.jpg');
                    }
                    $row->driver_photo = $driver_photo;
                }


                $row->id = (string)$row->id;
                $row->tax = json_decode($row->tax, true);
                $row->discount = number_format((float)$row->discount, 2, '.', '');

                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'successfully';
                $response['data'] = $row;
            } else {
                $response['success'] = 'Failed';
                $response['error'] = 'Ride Not Found';
            }

        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Some fields not found';
        }

        return response()->json($response);
    }
}
