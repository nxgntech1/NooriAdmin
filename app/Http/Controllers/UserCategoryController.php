<?php

namespace App\Http\Controllers;

use App\Models\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class UserCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if ($request->has('search') && $request->search != '' && $request->selected_search == 'libelle') {

            $search = $request->input('search');
            $userscategories = DB::table('tj_categorie_user')
                ->where('tj_categorie_user.libelle', 'LIKE', '%' . $search . '%')
                ->where('tj_categorie_user.deleted_at', '=', NULL)
                ->paginate(10);

        } else {

            $userscategories = DB::table('tj_categorie_user')
                ->where('tj_categorie_user.deleted_at', '=', NULL)
                ->paginate(10);

        }
        return view("settings.users_category.index")->with("userscategories", $userscategories);
    }

    public function delete($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $user = UserCategory::find($id[$i]);
                    $user->delete();
                }

            } else {
                $user = UserCategory::find($id);
                $user->delete();
            }

        }


        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $rules = [
            'libelle' => 'required',

        ], $messages = [
            'libelle.required' => 'The Name field is required!',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }
        $name = $request->input('name');
        $modifier = $request->modifier = date('Y-m-d H:i:s');

        $userscategory = UserCategory::find($id);
        if ($userscategory) {
            $userscategory->libelle = $name;
            $userscategory->modifier = $modifier;
            $userscategory->save();
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), $rules = [
            'libelle' => 'required',

        ], $messages = [
            'libelle.required' => 'The Name field is required!',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }
        $userscategory = new UserCategory;
        $userscategory->libelle = $request->input('name');
        $userscategory->creer = date('Y-m-d H:i:s');
        $userscategory->modifier = date('Y-m-d H:i:s');


        $userscategory->save();
        return redirect()->back();
    }

}