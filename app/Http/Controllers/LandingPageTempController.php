<?php

namespace App\Http\Controllers;

use App\Models\LandingPageTemplate;
use Illuminate\Http\Request;

class LandingPageTempController extends Controller
{

    public function __construct()
    {
	    $this->middleware('auth');
    }
    
    public function index(){
    		
    	$template = LandingPageTemplate::first();
        
        return view('administration_tools.homepage_Template.index',compact('template'))->render();
	}
      
    public function save(Request $request)
    {
    	$html_template = $request->get('html_template');
     	
		$template = LandingPageTemplate::first();
		if($template){
			$template->html_template = $html_template;
			$template->save();	
		}else{
			LandingPageTemplate::create(array('html_template'=>$html_template));
		}
		
    	return redirect()->back();
    }
}