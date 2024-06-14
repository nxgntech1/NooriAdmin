<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\FavoriteRide;
use App\Models\ParcelOrder;
use App\Models\Referral;
use App\Models\Requests;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserApp;
use App\Models\VehicleLocation;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Image;
use Validator;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {

        if ($request->has('search') && $request->search != '' && $request->selected_search == 'prenom') {
            $search = $request->input('search');
            $users = DB::table('tj_user_app')
                ->where('tj_user_app.prenom', 'LIKE', '%' . $search . '%')
                ->orWhere(DB::raw('CONCAT(tj_user_app.prenom, " ",tj_user_app.nom)'), 'LIKE', '%' . $search . '%')
                ->where('tj_user_app.deleted_at', '=', NULL)
                ->orderBy('tj_user_app.id', 'desc')
                ->paginate(20);
        } else if ($request->has('search') && $request->search != '' && $request->selected_search == 'phone') {
            $search = $request->input('search');
            $users = DB::table('tj_user_app')
                ->where('tj_user_app.phone', 'LIKE', '%' . $search . '%')
                ->where('tj_user_app.deleted_at', '=', NULL)
                ->orderBy('tj_user_app.id', 'desc')
                ->paginate(20);
        } else if ($request->has('search') && $request->search != '' && $request->selected_search == 'email') {
            $search = $request->input('search');
            $users = DB::table('tj_user_app')
                ->where('tj_user_app.email', 'LIKE', '%' . $search . '%')
                ->where('tj_user_app.deleted_at', '=', NULL)
                ->orderBy('tj_user_app.id', 'desc')
                ->paginate(20);
        } else {

            $users = UserApp::orderBy('tj_user_app.id', 'desc')->paginate(20);
        }

        return view("settings.users.index")->with("users", $users);
    }

    public function create()
    {
        return view("settings.users.create");
    }

    public function storeuser(Request $request)
    {

        $validator = Validator::make($request->all(), $rules = [
            'nom' => 'required',
            'prenom' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'phone' => 'required|unique:tj_user_app',
            'email' => 'required|unique:tj_user_app',
            'photo' => 'required|mimes:jpg,jpeg,png|max:2048'
        ], $messages = [
            'nom.required' => 'The First Name field is required!',
            'prenom.required' => 'The Last Name field is required!',
            'email.required' => 'The Email field is required!',
            'email.unique' => 'The Email is already taken!',
            'password.required' => 'The Password field is required!',
            'confirm_password.same' => 'Confirm Password should match the Password',
            'phone.required' => 'The Phone is required!',
            'phone.unique' => 'The Phone field is should be unique!',
        ]);

        if ($validator->fails()) {
            return redirect('users/create')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }
        $user = new UserApp;
        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');
        $user->email = $request->input('email');

        $password = $request->input('password');
        $confirm_password = $request->input('confirm_password');
        $user->mdp = hash('md5', $password);

        $user->login_type = 'phone';
        $user->phone = $request->input('phone');

        $user->statut = $request->has('statut') ? 'yes' : 'no';

        $user->photo = '';
        $user->photo_nic = '';

        $user->creer = date('Y-m-d H:i:s');
        $user->modifier = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');

        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extenstion = $file->getClientOriginalExtension();
            $time = time() . '.' . $extenstion;
            $filename = 'user_image' . $time;
            $path = public_path('assets/images/users/') . $filename;
            Image::make($file->getRealPath())->resize(100, 100)->save($path);
            // $file->move(public_path('assets/images/users/'), $filename);
            $image = str_replace('data:image/png;base64,', '', $file);
            $image = str_replace(' ', '+', $image);
            $user->photo_path = $filename;
        }
        $user->save();

        $referral = new Referral;

        $referral->user_id = $user->id;
        $referral->referral_code = Str::random(5);
        $referral->code_used = "false";
        $referral->creer = date('Y-m-d H:i:s');

        $referral->save();

        return redirect('users');

    }


    public function appUsers()
    {
        return view("settings.users.index");
    }

    public function edit($id)
    {

        $user = UserApp::where('id', "=", $id)->first();
        $rides = DB::select("SELECT count(id) as rides

        FROM tj_requete WHERE statut='completed' AND id_user_app=$id");
        return view("settings.users.edit")->with("user", $user)->with("rides", $rides);
    }

    public function show($id)
    {

        $user = UserApp::where('id', "=", $id)->first();

        $currency = Currency::where('statut', 'yes')->first();

        $transactions = Transaction::join('tj_payment_method', 'tj_transaction.payment_method', '=', 'tj_payment_method.libelle')
            ->select('tj_transaction.*', 'tj_payment_method.image')
            ->where('id_user_app', "=", $id)->orderBy('tj_transaction.id', 'desc')->paginate(10);

        $rides = Requests::
        join('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
            ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
            ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
            ->select('tj_requete.id', 'tj_requete.statut', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.id as driver_id', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
            ->where('tj_requete.id_user_app', $id)
            ->orderBy('tj_requete.id', 'DESC')
            ->paginate(10);

        $parcelOrders = ParcelOrder::
        join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
            ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
            ->join('tj_payment_method', 'parcel_orders.id_payment_method', '=', 'tj_payment_method.id')
            ->select('parcel_orders.id', 'parcel_orders.status', 'parcel_orders.created_at', 'tj_conducteur.id as driver_id', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom')
            ->where('parcel_orders.id_user_app', $id)
            ->orderBy('parcel_orders.id', 'DESC')
            ->paginate(10);

        $user_rating = DB::table('tj_user_note')
            ->select(DB::raw("COUNT(id) as ratingCount"), DB::raw("SUM(niveau_driver) as ratingSum"))
            ->where('id_user_app', '=', $id)
            ->first();

        $userRating = "0.0";
        if (!empty($user_rating)) {
            if ($user_rating->ratingCount > 0) {
                $userRating = number_format(($user_rating->ratingSum / $user_rating->ratingCount));
            }
        }


        return view("settings.users.show")->with("user", $user)->with("rides", $rides)->with("transactions", $transactions)->with("currency", $currency)->with('userRating', $userRating)->with('parcelOrders', $parcelOrders);
    }

    public function userUpdate(Request $request, $id)
    {


        if ($request->id > 0) {
            $image_validation = "mimes:jpeg,jpg,png";
            $doc_validation = "mimes:doc,pdf,docx,zip,txt";

        } else {
            $image_validation = "required|mimes:jpeg,jpg,png";
            $doc_validation = "required|mimes:doc,pdf,docx,zip,txt";

        }
        $validator = Validator::make($request->all(), $rules = [
            'nom' => 'required',
            'prenom' => 'required',
            'phone' => 'required|unique:tj_user_app,phone,' . $id,
            'email' => 'required|unique:tj_user_app,email,' . $id,
            'photo' => 'required|mimes:jpg,jpeg,png|max:2048',

        ], $messages = [
            'nom.required' => 'The First Name field is required!',
            'prenom.required' => 'The Last Name field is required!',
            'email.required' => 'The Email field is required!',
            'email.unique' => 'The Email is already taken!',
            'phone.required' => 'The Phone is required!',
            'phone.unique' => 'The Phone field is should be unique!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $nom = $request->input('nom');
        $prenom = $request->input('prenom');
        $phone = $request->input('phone');
        $device_id = $request->input('device_id');

        // $gender = $request->input('gender');
        if ($request->input('statut')) {
            $status = "yes";
        } else {
            $status = "no";
        }
        $email = $request->input('email');


        $user = UserApp::find($id);
        if ($user) {
            $user->nom = $nom;
            $user->prenom = $prenom;
            $user->phone = $phone;
            $user->device_id = $device_id;
            $user->statut = $request->has('statut') ? 'yes' : 'no';
            $user->email = $email;
            if ($request->hasfile('photo')) {

                $destination = public_path('assets/images/users/' . $user->photo_path);
                if (File::exists($destination)) {
                    File::delete($destination);
                }
                $file = $request->file('photo');
                $extenstion = $file->getClientOriginalExtension();
                $time = time() . '.' . $extenstion;
                $filename = 'user_' . $id . '.' . $extenstion;
                $path = public_path('assets/images/users/') . $filename;
                Image::make($file->getRealPath())->resize(100, 100)->save($path);
                //$file->move(public_path('assets/images/users/'), $filename);
                $user->photo_path = $filename;
            }
            $user->save();
        }

        return redirect('users');
    }

    public function deleteUser($id)
    {

        if ($id != "") {

            $id = json_decode($id);


            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $rides = Requests::where('id_user_app', $id[$i]);
                    if ($rides) {
                        $rides->delete();
                    }
                    $parcels = ParcelOrder::where('id_conducteur', $id[$i]);
                    if ($parcels) {
                        $parcels->delete();
                    }

                    $favRides = FavoriteRide::where('id_user_app', $id[$i]);
                    if ($favRides) {
                        $favRides->delete();
                    }
                    $vehicle_location = VehicleLocation::where('id_user_app', $id[$i]);
                    if ($vehicle_location) {
                        $vehicle_location->delete();
                    }

                    $user = UserApp::find($id[$i]);
                    $destination = public_path('assets/images/users/' . $user->photo_path);
                    if (File::exists($destination)) {
                        File::delete($destination);
                    }
                    $user->delete();
                }

            } else {
                $rides = Requests::where('id_user_app', $id);
                if ($rides) {
                    $rides->delete();
                }
                $parcels = ParcelOrder::where('id_conducteur', $id);
                if ($parcels) {
                    $parcels->delete();
                }

                $favRides = FavoriteRide::where('id_user_app', $id);
                if ($favRides) {
                    $favRides->delete();
                }
                $vehicle_location = VehicleLocation::where('id_user_app', $id);
                if ($vehicle_location) {
                    $vehicle_location->delete();
                }

                $user = UserApp::find($id);
                $destination = public_path('assets/images/users/' . $user->photo_path);
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $user->delete();
            }

        }

        return redirect()->back();
    }

    public function addWallet(Request $request, $id)
    {
        $user = UserApp::find($id);
        $amount = $request->amount;
        if ($amount == '' || $amount == null) {
            $amount = 0;
        }
        if ($user) {
            $userWallet = floatval($user->amount) + floatval($amount);
            $user->amount = (string)$userWallet;
            $user->save();
        }
        $date = date('Y-m-d H:i:s');

        DB::table('tj_transaction')->insert([
            'amount' => $amount,
            'payment_method' => 'Wallet',
            'id_user_app' => $id,
            'deduction_type' => '1',
            'payment_status' => 'success',
            'creer' => $date
        ]);
        $user = UserApp::find($id);
        $txnId = uniqid(0, 999);
        $email = $user->email;
        $date = date('d F Y');

        if (!empty($email)) {

            $emailsubject = '';
            $emailmessage = '';
            $emailtemplate = DB::table('email_template')->select('*')->where('type', 'wallet_topup')->first();
            if (!empty($emailtemplate)) {
                $emailsubject = $emailtemplate->subject;
                $emailmessage = $emailtemplate->message;
                $send_to_admin = $emailtemplate->send_to_admin;
            }
            $currencyData = DB::table('tj_currency')->select('*')->where('statut', 'yes')->first();
            if ($currencyData->symbol_at_right == "true") {
                $amount = number_format($amount, $currencyData->decimal_digit) . $currencyData->symbole;
                $newBalance = number_format($user['amount'], $currencyData->decimal_digit) . $currencyData->symbole;
            } else {
                $amount = $currencyData->symbole . number_format($amount, $currencyData->decimal_digit);
                $newBalance = $currencyData->symbole . number_format($user['amount'], $currencyData->decimal_digit);

            }
            $contact_us_email = DB::table('tj_settings')->select('contact_us_email')->value('contact_us_email');
            $contact_us_email = $contact_us_email ? $contact_us_email : 'none@none.com';


            $app_name = env('APP_NAME', 'Cabme');
            if ($send_to_admin == "true") {
                $to = $email . "," . $contact_us_email;

            } else {
                $to = $email;
            }

            $emailmessage = str_replace("{AppName}", $app_name, $emailmessage);
            $emailmessage = str_replace("{UserName}", $user['nom'] . " " . $user['prenom'], $emailmessage);
            $emailmessage = str_replace("{Amount}", $amount, $emailmessage);
            $emailmessage = str_replace("{PaymentMethod}", 'Wallet', $emailmessage);
            $emailmessage = str_replace('{TransactionId}', $txnId, $emailmessage);
            $emailmessage = str_replace('{Balance}', $newBalance, $emailmessage);
            $emailmessage = str_replace('{Date}', $date, $emailmessage);

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: ' . $app_name . '<' . $contact_us_email . '>' . "\r\n";
            mail($to, $emailsubject, $emailmessage, $headers);
        }

        return redirect('users/show/' . $id);
    }

    


    public function changeStatus($id)
    {
        $user = UserApp::find($id);
        if ($user->statut == 'no') {
            $user->statut = 'yes';
        } else {
            $user->statut = 'no';
        }
        $user->save();
        return redirect()->back();

    }


    public function update(Request $request, $id)
    {
        $name = $request->input('name');
        $password = $request->input('password');
        $old_password = $request->input('old_password');
        $email = $request->input('email');
        if ($password == '') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email'
            ]);
        } else {
            $user = Auth::user();
            if (password_verify($old_password, $user->password)) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255',
                    'password' => 'required|min:8',
                    'confirm_password' => 'required|same:password',
                    'email' => 'required|email'
                ]);

            } else {
                return Redirect()->back()->with(['message' => "Please enter correct old password"]);
            }

        }

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return Redirect()->back()->with(['message' => $error]);
        }

        $user = User::find($id);
        if ($user) {
            $user->name = $name;
            $user->email = $email;
            if ($password != '') {
                $user->password = Hash::make($password);
            }
            $user->save();
        }

        return redirect()->back();
    }

    public function toggalSwitch(Request $request)
    {
        $ischeck = $request->input('ischeck');
        $id = $request->input('id');
        $user = UserApp::find($id);

        if ($ischeck == "true") {
            $user->statut = 'yes';
        } else {
            $user->statut = 'no';
        }
        $user->save();

    }

    

}
