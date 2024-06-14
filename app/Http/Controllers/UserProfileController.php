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

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    public function profile()
    {
        $user = Auth::user();
        return view('settings.users.profile', compact(['user']));
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

    public function userDashboard()
    {
        $user = User::find(Auth::id()); // Example user
        if ($user->hasRole('admin')) {
            $userrole="admin";
         }
         else if($user->hasRole('user'))
         {
             $userrole="user";
         }
         else
         {
             $userrole="unknown";
         }
         $currency = Currency::where('statut', 'yes')->first();
         $results = [];
         //echo json_encode($user,JSON_PRETTY_PRINT);
         $userid= $user->id;
         // Use the raw PDO connection to execute the stored procedure
         $pdo = DB::getPdo();
         $stmt = $pdo->prepare('CALL get_admin_dashboard_reports(?)');
         $stmt->execute([$userid]);
 
         // Fetch the first result set
         $totals = $stmt->fetchAll(\PDO::FETCH_OBJ);
         $results['totals'] = $totals;
 
         // Use nextRowset() to advance to the next result set
         if ($stmt->nextRowset()) {
             $bookingtypes = $stmt->fetchAll(\PDO::FETCH_OBJ);
             $results['bookingtypes'] = $bookingtypes;
         }
 
         // Close the cursor to free resources
         $stmt->closeCursor();
         //echo json_encode($results,JSON_PRETTY_PRINT);
         return view('userdashboard')->with('results',$results)->with('currency',$currency)->with('userrole',$userrole);
        
    }
}
