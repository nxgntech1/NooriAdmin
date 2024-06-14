<?php

namespace App\Http\Controllers;

use App\Models\Cms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->has('search') && $request->search != '' && $request->selected_search == 'cms_name') {

            $search = $request->input('search');
            $cmss = DB::table('tj_cms')
                ->where('tj_cms.cms_name', 'LIKE', '%' . $search . '%')
                ->paginate(10);

        } else {

            $cmss = DB::table('tj_cms')
                ->paginate(10);

        }
        return view("cms.index")->with("cmss", $cmss);
    }

    public function edit($id)
    {
        $cmss = Cms::where('cms_id', $id)->first();
        return view('cms.edit', compact('cmss'));
    }

    public function create()
    {
        
        return view('cms.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), $rules = [
            'cms_name' => 'required|unique:tj_cms',
            'cms_slug' => 'unique:tj_cms',
            'cms_desc' => 'required',

        ], $messages = [
            'cms_name.required' => 'Page Name already exist, please choose another one!',
            'cms_slug.required' => 'Page Slug already exist, please choose another one!',
            'cms_desc.required' => 'Please insert page description',

        ]);

        if ($validator->fails()) {
            return redirect('cms/create')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $cmss = new Cms;
        $cmss->cms_name = $request->input('cms_name');
        $cmss->cms_slug = $request->input('cms_slug');
        $cmss->cms_desc = $request->input('cms_desc');
        $cmss->cms_status = $request->has('cms_status') ? 'Publish' : 'Unpublish';
        $cmss->save();
        return redirect('cms');

    }

    public function destroycms($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $user = Cms::find($id[$i]);
                    $user->delete();
                }

            } else {
                $user = Cms::find($id);
                $user->delete();
            }

        }

        return redirect()->back();
    }

    public function updateCms(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $rules = [
            'cms_name' => ['required', Rule::unique('tj_cms')->ignore($id, 'cms_id')],
            'cms_slug' => ['required', Rule::unique('tj_cms')->ignore($id, 'cms_id')],
            'cms_desc' => 'required',


        ], $messages = [
            'cms_name.required' => 'The Page Name field is required!',
            'cms_desc.required' => 'Please insert page description',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }


        $cmss = Cms::where('cms_id', $id)->first();
        $cmss->cms_name = $request->get('cms_name');
        $cmss->cms_slug = $request->get('cms_slug');
        $cmss->cms_desc = $request->get('cms_desc');

        $cmss->cms_status = $request->has('cms_status') ? 'Publish' : 'Unpublish';
        $cmss->save();
        return redirect('cms');
    }

    public function changeStatus($id)
    {
        $cmss = Cms::find($id);
        if ($cmss->cms_status == 'no') {
            $cmss->cms_status = 'yes';
        } else {
            $cmss->cms_status = 'no';
        }
        $cmss->save();
        return redirect()->back();

    }
    public function toggalSwitch(Request $request){
        $ischeck=$request->input('ischeck');
        $id=$request->input('id');
        $cms = Cms::find($id);

        if($ischeck=="true"){
          $cms->cms_status = 'Publish';
        }else{
          $cms->cms_status = 'Unpublish';
        }
          $cms->save();

}

}
