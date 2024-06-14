<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\FavoriteRide;
use App\Models\ParcelOrder;
use App\Models\Referral;
use App\Models\Requests;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VehicleLocation;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Image;
use Validator;

class WebUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if ($request->has('search') && $request->search != '' && $request->selected_search == 'name') {
            $search = $request->input('search');
            $users = DB::table('users')
                ->where('users.name', 'LIKE', '%' . $search . '%')
                ->whereNot('users.id',1)
                ->orderBy('users.id', 'desc')
                ->paginate(20);
        }  else if ($request->has('search') && $request->search != '' && $request->selected_search == 'email') {
            $search = $request->input('search');
            $users = DB::table('users')
                ->where('users.email', 'LIKE', '%' . $search . '%')
                ->whereNot('users.id',1)
                ->orderBy('users.id', 'desc')
                ->paginate(20);
        }
        else if ($request->has('search') && $request->search != '' && $request->selected_search == 'phone') {
            $search = $request->input('search');
            $users = DB::table('users')
                ->where('users.phone', 'LIKE', '%' . $search . '%')
                ->whereNot('users.id',1)
                ->orderBy('users.id', 'desc')
                ->paginate(20);
        } else {

            $users = User::whereNot('users.id',1)->orderBy('users.id', 'desc')->paginate(20);
        }

        return view("webuser.index")->with("users", $users);
    }

    public function create()
    {
        return view("webuser.create");
    }

    public function storeuser(Request $request)
    {

        $validator = Validator::make($request->all(), $rules = [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'phone' => 'required|unique:users',
            'email' => 'required|unique:users',
        ], $messages = [
            'name.required' => 'The First Name field is required!',
            'email.required' => 'The Email field is required!',
            'email.unique' => 'The Email is already taken!',
            'password.required' => 'The Password field is required!',
            'confirm_password.same' => 'Confirm Password should match the Password',
            'phone.required' => 'The Phone is required!',
            'phone.unique' => 'The Phone field is should be unique!',
        ]);

        if ($validator->fails()) {
            return redirect('webuser/create')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

       $res = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'phone' => $request->input('phone')
        ]);

        $role = Role::find(2);
        
        if($role)
        {
            $userRole = new UserRole;
            $userRole->user_id = $res->id;
            $userRole->role_id = $role->id;
            $userRole->created_at =  date('Y-m-d H:i:s');
            $userRole->save();

        }


        return redirect('webuser');

    }
    public function edit($id)
    {

        $user = User::where('id', "=", $id)->first();
        
        return view("webuser.edit")->with("user", $user);
    }
    
    public function userUpdate(Request $request, $id)
    {

        if ($request->id > 0) {
            $image_validation = "mimes:jpeg,jpg,png";

        } else {
            $image_validation = "required|mimes:jpeg,jpg,png";
        }
        $validator = Validator::make($request->all(), $rules = [
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $id,
            'email' => 'required|unique:users,email,' . $id,
            

        ], $messages = [
            'name.required' => 'The First Name field is required!',
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

        $name = $request->input('name');
        $phone = $request->input('phone');
        $email = $request->input('email');


        $user = User::find($id);
        if ($user) {
            $user->name = $name;
            $user->phone = $phone;
            $user->email = $email;
            $user->save();
            }
        return redirect('webuser');
    }

    public function deleteUser($id)
    {

        if ($id != "") {

            $id = json_decode($id);


            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    // $rides = Requests::where('id_user_app', $id[$i]);
                    // if ($rides) {
                    //     $rides->delete();
                    // }
                    // $parcels = ParcelOrder::where('id_conducteur', $id[$i]);
                    // if ($parcels) {
                    //     $parcels->delete();
                    // }

                    // $favRides = FavoriteRide::where('id_user_app', $id[$i]);
                    // if ($favRides) {
                    //     $favRides->delete();
                    // }
                    // $vehicle_location = VehicleLocation::where('id_user_app', $id[$i]);
                    // if ($vehicle_location) {
                    //     $vehicle_location->delete();
                    // }

                    $user = User::find($id[$i]);
                    $destination = public_path('assets/images/users/' . $user->photo_path);
                    if (File::exists($destination)) {
                        File::delete($destination);
                    }
                    $user->delete();
                }

            } else {
                // $rides = Requests::where('id_user_app', $id);
                // if ($rides) {
                //     $rides->delete();
                // }
                // $parcels = ParcelOrder::where('id_conducteur', $id);
                // if ($parcels) {
                //     $parcels->delete();
                // }

                // $favRides = FavoriteRide::where('id_user_app', $id);
                // if ($favRides) {
                //     $favRides->delete();
                // }
                // $vehicle_location = VehicleLocation::where('id_user_app', $id);
                // if ($vehicle_location) {
                //     $vehicle_location->delete();
                // }

                $user = User::find($id);
                $destination = public_path('assets/images/users/' . $user->photo_path);
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $user->delete();
            }

        }

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
}
