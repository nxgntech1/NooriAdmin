<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\DispatcherUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;
use Image;
class DispatcherController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if ($request->has('search') && $request->search != '' && $request->selected_search == 'first_name') {
            $search = $request->input('search');
            $users = DB::table('dispatcher_user')
                ->where('dispatcher_user.first_name', 'LIKE', '%' . $search . '%')
                ->orWhere(DB::raw('CONCAT(dispatcher_user.first_name, " ",dispatcher_user.last_name)'), 'LIKE', '%' . $search . '%')
                ->orderBy('id','desc')
                ->paginate(20);
        } else if ($request->has('search') && $request->search != '' && $request->selected_search == 'phone') {
            $search = $request->input('search');
            $users = DB::table('dispatcher_user')
                ->where('dispatcher_user.phone', 'LIKE', '%' . $search . '%')
                ->orderBy('id', 'desc')
                ->paginate(20);
        } else if ($request->has('search') && $request->search != '' && $request->selected_search == 'email') {
            $search = $request->input('search');
            $users = DB::table('dispatcher_user')
                ->where('dispatcher_user.email', 'LIKE', '%' . $search . '%')
                ->orderBy('id', 'desc')
                ->paginate(20);
        } else {
            $users = DispatcherUser::orderBy('id','desc')->paginate(20);
        }

        return view("dispatcher_user.index")->with("users", $users);
    }

    public function createUser()
    {

        return view("dispatcher_user.create");
    }

    public function storeUser(Request $request)
    {


        if ($request->id > 0) {
            $image_validation = "mimes:jpeg,jpg,png";
            $doc_validation = "mimes:doc,pdf,docx,zip,txt";

        } else {
            $image_validation = "required|mimes:jpeg,jpg,png";
            $doc_validation = "required|mimes:doc,pdf,docx,zip,txt";

        }
        $validator = Validator::make($request->all(), $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'phone' => 'required|unique:dispatcher_user',
            'email' => 'required|unique:dispatcher_user',
            'profile_picture' => $image_validation,
        ], $messages = [
            'first_name.required' => 'The First Name field is required!',
            'last_name.required' => 'The Last Name field is required!',
            'email.required' => 'The Email field is required!',
            'email.unique' => 'The Email is already taken!',
            'password.required' => 'The Password field is required!',
            'confirm_password.same' => 'Confirm Password should match the Password',
            'phone.required' => 'The Phone is required!',
            'phone.unique' => 'The Phone field is should be unique!',
            'profile_picture.required' => 'The Profile Image is required!',
        ]);

        if ($validator->fails()) {
            return redirect('dispatcher-users/create')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }
        $user = new DispatcherUser;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');

        $password = $request->input('password');
        $confirm_password = $request->input('confirm_password');
        $user->password = Hash::make($password);

        $user->phone = $request->input('phone');

        $user->status = $request->has('status') ? 'yes' : 'no';

        $user->profile_picture = '';

        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');

        if ($request->hasfile('profile_picture')) {
            $file = $request->file('profile_picture');
            $extenstion = $file->getClientOriginalExtension();
            $time = time() . '.' . $extenstion;
            $filename = 'dispatcher_user_profile' . $time;
            $path = public_path('assets/images/dispatcher_users/') . $filename;
            Image::make($file->getRealPath())->resize(150, 150)->save($path);

            //$file->move(public_path('assets/images/dispatcher_users'), $filename);
            $image = str_replace('data:image/png;base64,', '', $file);
            $image = str_replace(' ', '+', $image);
            $user->profile_picture = $image;
            $user->profile_picture_path = $filename;
        }

        $user->save();
        return redirect('dispatcher-users');

    }


    public function appUsers()
    {
        return view("settings.users.index");
    }

    public function editUser($id)
    {

        $user = DispatcherUser::where('id', "=", $id)->first();
        $rides = DB::select("SELECT count(id) as rides

        FROM tj_requete WHERE statut='completed' AND id_user_app=$id");
        return view("dispatcher_user.edit")->with("user", $user)->with("rides", $rides);
    }

    public function userShow($id)
    {

        $user = DispatcherUser::where('id', "=", $id)->first();

        $currency = Currency::where('statut', 'yes')->first();

        $transactions = [];
        $rides = [];
        
        $rides = DB::table('tj_requete')
        ->join('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
        ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
        ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
        ->select('tj_requete.id', 'tj_requete.statut','tj_requete.depart_name','tj_requete.destination_name','tj_requete.ride_type','tj_requete.dispatcher_id','tj_requete.tip_amount','tj_requete.admin_commission','tj_requete.tax','tj_requete.discount', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.id as driver_id', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image')
        //->orderBy('tj_requete.creer', 'DESC')
        ->where('tj_requete.dispatcher_id', '=', $id)
        ->paginate(10);
       
        return view("dispatcher_user.show")->with("user", $user)->with("rides", $rides)->with("transactions", $transactions)->with("currency", $currency);
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
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:dispatcher_user,phone,' . $id,
            'email' => 'required|unique:dispatcher_user,email,' . $id,
            'profile_picture' => $image_validation,
        ], $messages = [
            'first_name.required' => 'The First Name field is required!',
            'last_name.required' => 'The Last Name field is required!',
            'email.required' => 'The Email field is required!',
            'email.unique' => 'The Email is already taken!',
            'phone.required' => 'The Phone is required!',
            'phone.unique' => 'The Phone field is should be unique!',
            'profile_picture.required' => 'The Profile Image is required!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $phone = $request->input('phone');

        $user = DispatcherUser::find($id);
        if ($user) {
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->phone = $phone;
            $user->status = $request->has('status') ? 'yes' : 'no';
            $user->email = $email;
            if ($request->hasfile('profile_picture')) {
                $file = $request->file('profile_picture');
                $extenstion = $file->getClientOriginalExtension();
                $time = time() . '.' . $extenstion;
                $filename = 'dispatcher_user_profile' . $time;
                $path = public_path('assets/images/dispatcher_users/') . $filename;
                Image::make($file->getRealPath())->resize(150, 150)->save($path);

                //$file->move(public_path('assets/images/dispatcher_users'), $filename);
                $image = str_replace('data:image/png;base64,', '', $file);
                $image = str_replace(' ', '+', $image);
                $user->profile_picture = $image;
                $user->profile_picture_path = $filename;
            }

            $user->save();
        }

        return redirect('dispatcher-users');
    }

    public function deleteUser($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $user = DispatcherUser::find($id[$i]);
                    $user->delete();
                }

            } else {
                $user = DispatcherUser::find($id);
                $user->delete();
            }

        }

        return redirect()->back();
    }

    public function userChangeStatus($id)
    {
        $user = DispatcherUser::find($id);
        if ($user->status == 'no') {
            $user->status = 'yes';
        } else {
            $user->status = 'no';
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
        $user = DispatcherUser::find($id);

        if ($ischeck == "true") {
            $user->status = 'yes';
        } else {
            $user->status = 'no';
        }
        $user->save();

    }

}
