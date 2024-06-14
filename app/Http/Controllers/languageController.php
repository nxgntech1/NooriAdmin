<?php

namespace App\Http\Controllers;

use App\Models\language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Validator;
use App;

class languageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function change(Request $request)
    {

        App::setLocale($request->lang);

        session()->put('locale', $request->lang);

        return redirect()->back();
    }

    public function getCode($slugid){
        $data = DB::table('language')
        ->where('code','=',$slugid)
        ->get();

        return response()->json($data);
    }
    
    public function index(Request $request)
    {

        if ($request->has('search') && $request->search != '' && $request->selected_search == 'prenom') {
            $search = $request->input('search');
            $language = DB::table('language')
                ->where('language.language', 'LIKE', '%' . $search . '%')
                ->paginate(10);

        } else {
            $language = language::paginate(10);

        }

        return view("language.index")->with("language", $language);
    }

    public function getLangauage()
    {
         $data = DB::table('language')
         ->where('status','=','true')
         ->get();
         return response()->json($data);
    }

    public function create()
    {
    	return view("language.create");
    }

    public function storeuser(Request $request)
    {


        if ($request->id > 0) {
            $image_validation = "mimes:jpeg,jpg,png";
            $doc_validation = "mimes:doc,pdf,docx,zip,txt";

        } else {
            $image_validation = "required|mimes:jpeg,jpg,png";
            $doc_validation = "required|mimes:doc,pdf,docx,zip,txt";

        }
        $validator = Validator::make($request->all(), $rules = [
            'language' => 'required',
            'code' => 'required',
            'flag' => $image_validation,
        ], $messages = [
            'language.required' => 'The First Name field is required!',
            'code.required' => 'The Code field is required!',
            'flag.required' => 'The flag field is required!',

        ]);

        if ($validator->fails()) {
            return redirect('language/create')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }
        $user = new language;
        $user->language = $request->input('language');
        $user->code = $request->input('code');


        $user->status = $request->has('status') ? 'true' : 'false';
        $user->is_rtl = $request->has('is_rtl') ? 'true' : 'false';


        $user->flag = '';

        $user->creer = date('Y-m-d H:i:s');
        $user->modifier = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');


        if ($request->hasfile('flag')) {
            $file = $request->file('flag');
            $extenstion = $file->getClientOriginalExtension();
            $time = time() . '.' . $extenstion;
            $filename = $request->input('language') . '.' . $extenstion;
            $file->move(public_path('assets/images/flags/'), $filename);
            $image = str_replace('data:image/png;base64,', '', $file);
            $image = str_replace(' ', '+', $image);
            $user->flag = $filename;
        }


        $user->save();

        return redirect('language');

    }

    public function edit($id)
    {
		$language = language::where('id', "=", $id)->first();

        return view("language.edit")->with("language", $language);
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
            'language' => 'required',
            'code' => 'required',
            'flag' => $image_validation,
        ], $messages = [
            'language.required' => 'The language field is required!',
            'code.required' => 'The Code field is required!',
            'flag.required' => 'The flag field is required!',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $language = $request->input('language');
        $code = $request->input('code');

        if ($request->input('status')) {
            $status = "true";
        } else {
            $status = "false";
        }
        if ($request->input('is_rtl')) {
            $is_rtl = "true";
        } else {
            $is_rtl = "false";
        }


        $user = language::find($id);
        if ($user) {
            $user->language = $language;
            $user->code = $code;
            $user->status = $status;
            $user->is_rtl = $is_rtl;

            if ($request->hasfile('flag')) {
                $destination = public_path('assets/images/flags/' . $user->flag);
                if (File::exists($destination)) {
                    File::delete($destination);
                }
                $file = $request->file('flag');
                $extenstion = $file->getClientOriginalExtension();
                $time = time() . '.' . $extenstion;
                $filename = $request->input('language') . '.' . $extenstion;
                $file->move(public_path('assets/images/flags/'), $filename);
                $user->flag = $filename;


            }
            $user->save();
        }

        return redirect('language');
    }

    public function deleteUser($id)
    {

        if ($id != "") {

            $user = language::find($id);
            $user->delete();

        }

        return redirect()->back();
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

    public function toggalSwitch(Request $request){
             $ischeck=$request->input('ischeck');
            
             $id=$request->input('id');
             $language = language::find($id);
             $response = array();
            $active= language::where('status','true')->count();
            if($active == 1 && $ischeck=="false"){
                $messages = 'You can not disable all languages';
                $response['error'] = $messages;
            }
            else{
             if($ischeck=="true"){
              $language->status = 'true';
             }else{
               $language->status = 'false';
             }
               $language->save();
            }
        return response()->json($response);
    }

}
