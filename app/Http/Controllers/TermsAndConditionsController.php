<?php

namespace App\Http\Controllers;

use App\Models\TearmsCondition;
use App\Models\privacyPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TermsAndConditionsController extends Controller {

	public function index() {

		$termconditions = DB::table('tj_terms_and_conditions') -> first();

		return view("administration_tools.terms_condition.index") -> with("termconditions", $termconditions);
	}

	public function update(Request $request, $id) {
		
		$terms = $request -> terms_condition;
		$termcondition = TearmsCondition::find($id);

		if($termcondition) {
			$termcondition -> terms = $terms;
		}
		$termcondition -> save();
	}
	
	public function indexPrivacy() {

		$privacyPolicy = DB::table('tj_privacy_policy') -> first();
		
		return view("administration_tools.privacy_policy.index") -> with("privacyPolicy", $privacyPolicy);
	}
	
	public function updatePrivacy(Request $request, $id) {
		$privacy = $request -> privacy_policy;
		
		$privacyPolicy = privacyPolicy::find($id);
		if($privacyPolicy) {
			$privacyPolicy -> privacy_policy = $privacy;
		}
		$privacyPolicy -> save();

	}

}
