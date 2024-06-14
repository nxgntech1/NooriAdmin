<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\Driver;
use App\Models\UserApp;
use Illuminate\Http\Request;
use DB;
class ExistingUserController extends Controller {

	public function __construct() {
		$this -> limit = 20;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function getData(Request $request) {

		$date_heure = date('Y-m-d H:i:s');
		$phone = $request -> get('phone');
		$user_cat = $request -> get('user_cat');

		if (empty($phone)) {

			$response['success'] = 'Failed';
			$response['error'] = 'Phone number required';

		}else{
			if($user_cat=="customer"){
				$checkuser = UserApp::where('phone', $phone) -> first();
				if($checkuser){
					$response['success'] = 'success';
					$response['error'] = null;
					$response['message'] = 'User already exist';
					$response['data'] = True;
				}
				else{
					 $checkdriver = Driver::where('phone', $phone) -> first();
					 if($checkdriver){
						 $response['success'] = 'Failed';
						 $response['error'] = 'User already exist, please try with different number';
						 $response['data'] = False;
					 }else{
						 $response['success'] = 'success';
						 $response['error'] = null;
						 $response['message'] = 'User Not exist';
						 $response['data'] = False;
					 }


				}
			}
				else if($user_cat=="driver"){
						$checkdriver = Driver::where('phone', $phone) -> first();
						if($checkdriver){
							$response['success'] = 'success';
							$response['message'] = 'User already exist';
							$response['error'] = null;
							$response['data'] = true;
						}

						else{
							$checkuser = UserApp::where('phone', $phone) -> first();
								if($checkuser){
									$response['success'] = 'Failed';
									$response['error'] = 'User already exist, please try with different number';
									$response['data'] = False;
								}else{
									$response['success'] = 'success';
									$response['error'] = null;
									$response['message'] = 'User Not exist';
									$response['data'] = False;
								}

						}
				}


			}

		return response() -> json($response);
	}

}
