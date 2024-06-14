<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        $templates = EmailTemplate::get();

        return view("administration_tools.email_template.index")->with('templates', $templates);
    }

    public function edit($id)
    {
        $template = EmailTemplate::find($id);
        return view('administration_tools.email_template.edit')->with('template', $template);
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), $rules = [
            'subject' => 'required',
            'message' => 'required',

        ], $messages = [
                'subject.required' => 'The subject field is required!',
                'message.required' => 'The message field is required!',
            ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }
        $subject = $request->input('subject');
        $message = $request->input('message');
        $sen_to_admin = $request->has('send_admin') ? "true" : "false";
        $template = EmailTemplate::find($id);
        if($template){
            $template->subject = $subject;
            $template->message = $message;
            $template->send_to_admin = $sen_to_admin;
            $template->updated_at = date('Y-m-d H:i:s');
            $template->save();
            return redirect('administration_tools/email_template');
        }

    }

}