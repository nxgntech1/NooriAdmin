<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Driver;
use App\Models\Requests;
use App\Models\ParcelOrder;
use App\Models\Vehicle;
use App\Models\DriversDocuments;
use App\Models\Message;
use App\Models\Note;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\VehicleType;
use App\Models\vehicleImages;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\GcmController;
use Image;
class DriverController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

	public function index(Request $request)
    {

        $query = DB::table('tj_conducteur')
            ->leftJoin('tj_vehicule', 'tj_vehicule.id_conducteur', '=', 'tj_conducteur.id')
            ->leftJoin('tj_type_vehicule', 'tj_type_vehicule.id', '=', 'tj_vehicule.id_type_vehicule')
            ->select('tj_conducteur.*', 'tj_type_vehicule.libelle');
        
    	if($request->search != '' && $request->selected_search != '') {
    		$keyword = $request->input('search');
			$field = $request->input('selected_search');
			if($field == "prenom"){
				$query->where('tj_conducteur.prenom', 'LIKE', '%' . $keyword . '%');
				$query->orWhere(DB::raw('CONCAT(tj_conducteur.nom, " ",tj_conducteur.prenom)'), 'LIKE', '%' . $keyword . '%');
			}else{
				$query->where('tj_conducteur.'.$field, 'LIKE', '%' . $keyword . '%');

			}
			$query->where('tj_conducteur.deleted_at', '=', NULL);
            $query->paginate(20);
		}

		$drivers = $query->orderBy('tj_conducteur.id','desc')->paginate(20);

        $totalRide = DB::table('tj_requete')
        ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
        ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
        ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
        ->where('tj_requete.deleted_at', '=', NULL)
        ->select('tj_requete.id_conducteur')
        ->orderBy('tj_conducteur.id','desc')
        ->get();

        return view("drivers.index")->with("drivers", $drivers)->with('totalRide', $totalRide);
    }

    public function approvedDrivers(Request $request)
    {

        $query = DB::table('tj_conducteur')
            ->leftJoin('tj_vehicule', 'tj_vehicule.id_conducteur', '=', 'tj_conducteur.id')
            ->leftJoin('tj_type_vehicule', 'tj_type_vehicule.id', '=', 'tj_vehicule.id_type_vehicule')
            ->select('tj_conducteur.*', 'tj_type_vehicule.libelle');
            $query->where('tj_conducteur.is_verified','=',1);

    	if($request->search != '' && $request->selected_search != '') {
    		$keyword = $request->input('search');
			$field = $request->input('selected_search');
			if($field == "prenom"){
				$query->where('tj_conducteur.prenom', 'LIKE', '%' . $keyword . '%');
				$query->orWhere(DB::raw('CONCAT(tj_conducteur.nom, " ",tj_conducteur.prenom)'), 'LIKE', '%' . $keyword . '%');
			}else{
				$query->where('tj_conducteur.'.$field, 'LIKE', '%' . $keyword . '%');
			}
			$query->where('tj_conducteur.deleted_at', '=', NULL)->where('tj_conducteur.is_verified','=',1);
		}


		$drivers = $query->orderBy('tj_conducteur.id','desc')->paginate(20);

    $totalRide = DB::table('tj_requete')
    ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
    ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
    ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
    ->where('tj_requete.deleted_at', '=', NULL)
    ->select('tj_requete.id_conducteur')
    ->orderBy('tj_conducteur.id', 'desc')
    ->get();

        return view("drivers.approved")->with("drivers", $drivers)->with('totalRide', $totalRide);
    }

	public function pendingDrivers(Request $request)
    {
        $query = DB::table('tj_conducteur')
            ->leftJoin('tj_vehicule', 'tj_vehicule.id_conducteur', '=', 'tj_conducteur.id')
            ->leftJoin('tj_type_vehicule', 'tj_type_vehicule.id', '=', 'tj_vehicule.id_type_vehicule')
            ->select('tj_conducteur.*', 'tj_type_vehicule.libelle');
            $query->where('tj_conducteur.is_verified','=',0);

    	if($request->search != '' && $request->selected_search != '') {
    		$keyword = $request->input('search');
			$field = $request->input('selected_search');
    		if($field == "prenom"){
				$query->where('tj_conducteur.prenom', 'LIKE', '%' . $keyword . '%');
				$query->orWhere(DB::raw('CONCAT(tj_conducteur.nom, " ",tj_conducteur.prenom)'), 'LIKE', '%' . $keyword . '%');
			}else{
				$query->where('tj_conducteur.'.$field, 'LIKE', '%' . $keyword . '%');
			}
			$query->where('tj_conducteur.deleted_at', '=', NULL)->where('tj_conducteur.is_verified','=',0);
		}


		$drivers = $query->orderBy('tj_conducteur.id','desc')->paginate(20);

    $totalRide = DB::table('tj_requete')
    ->leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
    ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
    ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
    ->where('tj_requete.deleted_at', '=', NULL)
    ->select('tj_requete.id_conducteur')->get();

        return view("drivers.pending")->with("drivers", $drivers)->with('totalRide', $totalRide);
    }

    public function statusAproval(Request $request, $id, $type)
    {

		$document = DriversDocuments::find($id);
		$comment = $request->get('comment');

        if($document){
        	if($type == 1){
                $document->document_status = 'Approved';
				$document->comment = '';
                $msg ="Document got Approved.";
            }else{
            	$document->document_status = 'Disapprove';
				$document->comment = $comment;
                $msg ="Document got Disapproved.";
				$this->notifyDriver($comment,$document->driver_id);
            }
			$document->save();
        }
  
		$admin_documents = DB::table('admin_documents')->where('admin_documents.is_enabled','Yes')->get();
        $driverDocumentCount = 0;
        foreach($admin_documents as $value){
            $approved_documents=DriversDocuments::where('driver_id', $document->driver_id)->where('document_status', 'Approved')->where('document_id',$value->id)->get();
            if(count($approved_documents)>0){
                $driverDocumentCount++;
            }
        }
        $admin_documents = DB::table('admin_documents')->where('admin_documents.is_enabled', 'Yes')->count();

       // $approved_documents = DriversDocuments::where('driver_id',$document->driver_id)->where('document_status','Approved')->count();

		$driver = Driver::find($document->driver_id);

		if($admin_documents == $driverDocumentCount){
         $driver->is_verified = 1;
		}else{
			$driver->is_verified = 0;
		}
		$driver->save();
        
		if(!blank($comment)){
			echo json_encode(array('success'=>'yes')); die;
		}

        return redirect()->back()->with('message',$msg);
    }

	public function notifyDriver($comment,$id){

		$tmsg = '';
        $terrormsg = '';

        $title = str_replace("'", "\'", "Disapproved of your Document");
        $msg = str_replace("'", "\'", "Admin is Disapproved your Document. Please submit again.");
        $reasons = str_replace("'", "\'", "$comment");

        $tab[] = array();
        $tab = explode("\\", $msg);

        $msg_ = "";
        for ($i = 0; $i < count($tab); $i++) {
            $msg_ = $msg_ . "" . $tab[$i];
        }

        $message = array("body" => $msg_, "reasons" => $reasons, "title" => $title, "sound" => "mySound", "tag" => "documentdisaaproved");

        $driver = DB::table('tj_conducteur')
            ->select('fcm_id')
            ->where('fcm_id', '<>', '')
            ->where('id',$id)
            ->first();

        $tokens = array();
        if(isset($driver->fcm_id)) {
            $tokens[] = $driver->fcm_id;
        }

        $temp = array();
        if(count($tokens) > 0) {
            $date_heure = date('Y-m-d H:i:s');
            $from_id = $id;
            $to_id = $id;
            GcmController::send_notification($tokens, $message, $temp);
        }
	}

    public function statusDisaproval(Request $request, $id, $type)
    {
        $validator = Validator::make($request->all(), $rules = [
            'comment' => 'required',
        ], $messages = [
            'comment.required' => 'Add Comment for disapproval!',
        ]);


        if ($validator->fails()) {
            return redirect('driver/document/view/' . $id)
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }
        $comment = $request->input('comment');
        $approvalStatus = 'disapproved';

        $user = DriversDocuments::find($id);
        if ($user) {
            if ($type == 1) {
                $user->comment = $comment;
                $user->document_status = $approvalStatus;
            } elseif ($type == 2) {
                $user->comment = $comment;
                $user->document_status = $approvalStatus;
            } elseif ($type == 3) {
                $user->comment = $comment;
                $user->document_status = $approvalStatus;
            } elseif ($type == 4) {
                $user->comment = $comment;
                $user->document_status = $approvalStatus;
            }
        }
        $user->save();

        $title = str_replace("'", "\'", "Disapproved of your Document");
        $msg = str_replace("'", "\'", "Admin is Disapproved your Document. Please submit again.");
        $reasons = str_replace("'", "\'", "$comment");

        $tab[] = array();
        $tab = explode("\\", $msg);
        $msg_ = "";
        for ($i = 0; $i < count($tab); $i++) {
            $msg_ = $msg_ . "" . $tab[$i];
        }
        $message = array("body" => $msg_, "reasons" => $reasons, "title" => $title, "sound" => "mySound", "tag" => "documentdisaaproved");

        $query = DB::table('tj_conducteur')
            ->select('fcm_id')
            ->where('fcm_id', '<>', '')
            ->where('id', '=', DB::raw($id))
            ->get();
        $tokens = array();
        if (!empty($query)) {
            foreach ($query as $user) {
                if (!empty($user->fcm_id)) {
                    $tokens[] = $user->fcm_id;
                }
            }
        }

        $temp = array();

        if (count($tokens) > 0) {
            $date_heure = date('Y-m-d H:i:s');
            $from_id = $id;
            $to_id = $id;
            GcmController::send_notification($tokens, $message, $temp);
        }
        return redirect()->back();
    }


    public function edit($id)
    {
        $zones = Zone::where('status','yes')->get();
        $driver = Driver::where('id', "=", $id)->first();
        $vehicle = Vehicle::where('id_conducteur', "=", $id)->first();

        $vehicleType = VehicleType::all();

        $brand =Brand::all();
        $model = [];
        if(!empty($vehicle)){
            $model = Carmodel::where('brand_id', "=", $vehicle->brand)->where('vehicle_type_id', "=", $vehicle->id_type_vehicule)->get();

        }
        $currency = Currency::where('statut', 'yes')->first();

        $vehicleImage = vehicleImages::where('id_driver', '=', $id)->first();
        $earnings = DB::select("SELECT sum(montant) as montant, count(id) as rides FROM tj_requete WHERE statut='completed' AND id_conducteur=$id");

		$avg_rating = Note::where('id_conducteur', "=", $id)->avg('niveau');
		$avg_rating = $avg_rating?$avg_rating:0;

        return view('drivers.edit')->with('driver', $driver)->with('model', $model)->with('brand', $brand)
            ->with("vehicle", $vehicle)->with("earnings", $earnings)->with('vehicleType', $vehicleType)->with('currency', $currency)
            ->with('vehicleImage', $vehicleImage)
			->with('avg_rating', $avg_rating)
            ->with('zones', $zones);
            
    }

    public function create()
    {
        $brand = Brand::all();
        $vehicleType = VehicleType::all();
        $model = Carmodel::all();
        $zones = Zone::where('status','yes')->get();
        return view('drivers.create')->with('brand', $brand)->with('model', $model)->with('vehicleType', $vehicleType)->with('zones', $zones);
    }

    public function getModel(Request $request, $brand_id)
    {
        $id_type_vehicule = $request->get('id_type_vehicule');
        $data['model'] = Carmodel::where("brand_id", $brand_id)->where('vehicle_type_id',$id_type_vehicule)->get(["name", "id"]);

        return response()->json($data);
    }

    public function getBrand(Request $request, $vehicleType_id)
    {
        $data['brand'] = Brand::where("vehicle_id", $vehicleType_id)
            ->get(["name", "id"]);

        return response()->json($data);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), $rules = [
            'nom' => 'required',
            'prenom' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            // 'id_type_vehicule'=>'required',
            // 'brand'=>'required',
            // 'model'=>'required',
            // 'km'=>'required',
            // 'milage'=>'required',
            // 'car_number'=>'required',
            // 'color'=>'required',
            // 'passenger'=>'required',
            'photo' => 'required|mimes:jpg,jpeg,png|max:2048',
            'zone'=>'required',
        ], $messages = [
            'nom.required' => 'The First Name field is required!',
            'prenom.required' => 'The Last Name field is required!',
            'email.required' => 'The Email field is required!',
            'email.unique' => 'The Email field is should be unique!',
            'password.required' => 'The Password field is required!',
            'phone.required' => 'The Phone field is required!',
            'phone.unique' => 'The Phone field is should be unique!',
            // 'id_type_vehicule.required' => 'The Vehicle type field is required!',
            // 'brand.required' => 'The brand field is required!',
            // 'model.required' => 'The model field is required!',
            // 'km.required' => 'The km field is required!',
            // 'milage.required' => 'The milage field is required!',
            // 'car_number.required' => 'The NumberPlate field is required!',
            // 'color.required' => 'The Color field is required!',
            //'passenger.required' => 'The Number of Passenger field is required!',
        ]);

        if ($validator->fails()) {
            return redirect('drivers/create')
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $user = new Driver;
        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');
        $user->email = $request->input('email');
        $user->statut = $request->has('statut') ? 'yes' : 'no';
        $user->statut_vehicule = $request->has('statut') ? 'yes' : 'no';
        // $user->tonotify = $request->has('notify') ? 'yes' : 'no';
        $user->online = 'yes';
        $user->status_car_image = 'yes';
        $user->login_type = 'phone';
        // $user->address = $request->input('address');
        $user->device_id = $request->input('device_id');
        $password = $request->input('password');
        $user->mdp = hash('md5', $password);
        $user->phone = $request->input('phone');
        $user->creer = date('Y-m-d H:i:s');
        $user->modifier = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        // $user->bank_name = $request->input('bank_name');
        // $user->holder_name = $request->input('holder_name');
        // $user->account_no = $request->input('account_number');
        // $user->branch_name = $request->input('branch_name');
        // $user->other_info = $request->input('other_information');
        // $user->ifsc_code = $request->input('ifsc_code');
        $user->amount = "0";
        //$user->parcel_delivery=$request->has('parcel_delivery') ? "yes" : "no";
        $user->parcel_delivery="no";
        $zone = $request->input('zone');
        
        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extenstion = $file->getClientOriginalExtension();
            $time = time() . '.' . $extenstion;
            $filename = 'driver_image_' . $time;
            $path = public_path('assets/images/driver/') . $filename;
            Image::make($file->getRealPath())->resize(150, 150)->save($path);

            //$file->move(public_path('assets/images/driver'), $filename);
            $image = str_replace('data:image/png;base64,', '', $file);
            $image = str_replace(' ', '+', $image);
            $user->photo_path = $filename;
        }
        $user->zone_id = $zone ? implode(',',$zone) : NULL;
        $user->save();
        $driver_id = $user->id;


        // $vehicle = new Vehicle;
        // $vehicle->brand = $request->input('brand');
        // $vehicle->model = $request->input('model');
        // $vehicle->color = $request->input('color');
        // $vehicle->numberplate = $request->input('car_number');
        // $vehicle->car_make = '';
        // $vehicle->km = $request->input('km');
        // $vehicle->milage = $request->input('milage');
        // $vehicle->id_conducteur = $driver_id;
        // $vehicle->statut = 'yes';
        // $vehicle->creer = date('Y-m-d H:i:s');
        // $vehicle->modifier = date('Y-m-d H:i:s');
        // $vehicle->updated_at = date('Y-m-d H:i:s');
        // $vehicle->id_type_vehicule = $request->input('id_type_vehicule');
        // $vehicle->passenger = $request->input('passenger');
        // $vehicle->save();
        // $vehicle_id = $vehicle->id;

        // $vehicle_image = new vehicleImages;
        // if ($request->hasfile('image_path')) {
        //     $file = $request->file('image_path');
        //     $extenstion = $file->getClientOriginalExtension();
        //     $time = time() . '.' . $extenstion;
        //     $filename = 'vehicle_' . $time;
        //     $Selectedfilename = 'selected_vehicleType_' . $time;
        //     $file->move(public_path('assets/images/vehicle'), $filename);
        //     $vehicle_image->image_path = $filename;
        //     // $vehicle_image->selected_image = $Selectedfilename;

        // }
        // $vehicle_image->id_vehicle = $vehicle_id;
        // $vehicle_image->id_driver = $driver_id;
        // $vehicle_image->creer = date('Y-m-d H:i:s');
        // $vehicle_image->modifier = date('Y-m-d H:i:s');
        // $vehicle_image->save();
        
        return redirect('drivers');
    }


    public function deleteDriver($id)
    {

        if ($id != "") {

            $id = json_decode($id);


            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $requests = Requests::where('id_conducteur', $id[$i]);
                    if ($requests) {

                        $requests->delete();
                    }
                    $parcels = ParcelOrder::where('id_conducteur', $id[$i]);
                    if ($parcels) {

                        $parcels->delete();
                    }

                    $vehicle = Vehicle::where('id_conducteur', $id[$i]);
                    if ($vehicle) {
                        $vehicle->delete();
                    }


                    $Message = Message::where('id_conducteur', $id[$i]);
                    if ($Message) {
                        $Message->delete();
                    }


                    $Note = Note::where('id_conducteur', $id[$i]);
                    if ($Note) {
                        $Note->delete();
                    }

                    $user = Driver::find($id[$i]);
                    if(!empty($user->photo_path)){
                        $destination = public_path('assets/images/driver/' . $user->photo_path);
                        if (File::exists($destination)) {
                            File::delete($destination);
                        }
                    }
                    
                    $driver_docs = DriversDocuments::where('driver_id', "=", $id[$i])->get();
                    if($driver_docs){
                        foreach($driver_docs as $driver_doc){
                            if(!empty($driver_doc->document_path)){
                                $destination = public_path('assets/images/driver/documents/' . $driver_doc->document_path);
                                if (File::exists($destination)) {
                                    File::delete($destination);
                                }
                            }
                        }
                    }

                    $user->delete();
                }

            } else {
                $requests = Requests::where('id_conducteur', $id);
                if ($requests) {

                    $requests->delete();
                }
                $parcels = ParcelOrder::where('id_conducteur', $id);
                if ($parcels) {

                    $parcels->delete();
                }

                $vehicle = Vehicle::where('id_conducteur', $id);
                if ($vehicle) {
                    $vehicle->delete();
                }


                $Message = Message::where('id_conducteur', $id);
                if ($Message) {
                    $Message->delete();
                }


                $Note = Note::where('id_conducteur', $id);
                if ($Note) {
                    $Note->delete();
                }

                $user = Driver::find($id);
                if(!empty($user->photo_path)){
                    $destination = public_path('assets/images/driver/' . $user->photo_path);
                    if (File::exists($destination)) {
                        File::delete($destination);
                    }
                }

                $driver_docs = DriversDocuments::where('driver_id', "=", $id)->get();
                if($driver_docs){
                    foreach($driver_docs as $driver_doc){
                        if(!empty($driver_doc->document_path)){
                            $destination = public_path('assets/images/driver/documents/' . $driver_doc->document_path);
                            if (File::exists($destination)) {
                                File::delete($destination);
                            }
                        }
                    }
                }

                $user->delete();
            }

        }

        return redirect()->back();
    }

    public function updateDriver(Request $request, $id)
    {

        if ($request->id > 0) {
            $image_validation = "mimes:jpeg,jpg,png";
            $doc_validation = "mimes:doc,pdf,docx,zip,txt";
        } else {
            $image_validation = "required|mimes:jpeg,jpg,png";
            $doc_validation = "required|mimes:doc,pdf,docx,zip,txt";

        }

        $validator = Validator::make($request->all(), $rules = [
            'nom' => 'required',
            'prenom' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'id_type_vehicule'=>'required',
            'brand'=>'required',
            'model'=>'required',
            'km'=>'required',
            'milage'=>'required',
            'numberplate'=>'required',
            'color'=>'required',
            'passenger'=>'required',
            'zone'=>'required',
        ], $messages = [
            'nom.required' => 'The First Name field is required!',
            'prenom.required' => 'The Last Name field is required!',
            'email.required' => 'The Email field is required!',
            'email.unique' => 'The Email field is should be unique!',
            'phone.required' => 'The Phone field is required!',
            'phone.unique' => 'The Phone field is should be unique!',
            'id_type_vehicule.required' => 'The Vehicle type field is required!',
            'brand.required' => 'The brand field is required!',
            'model.required' => 'The model field is required!',
            'km.required' => 'The km field is required!',
            'milage.required' => 'The milage field is required!',
            'numberplate.required' => 'The NumberPlate field is required!',
            'color.required' => 'The Color field is required!',
            'passenger.required' => 'The Number of Passenger field is required!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }


        $nom = $request->input('nom');
        $prenom = $request->input('prenom');
        $phone = $request->input('phone');
        $device_id = $request->input('device_id');
        $status = $request->input('statut');
        $id_type_vehicule = $request->input('id_type_vehicule');
        $brand = $request->input('brand');
        $model = $request->input('model');
        $color = $request->input('color');
        $km = $request->input('km');
        $milage = $request->input('milage');
        $numberplate = $request->input('numberplate');
        $passenger = $request->input('passenger');
        $bank = $request->input('bank_name');
        $holder = $request->input('holder_name');
        $branch = $request->input('branch_name');
        $acc_no = $request->input('account_number');
        $other_info = $request->input('other_information');
        $ifsc_code = $request->input('ifsc_code');
        $parcel_delivery = $request->has('parcel_delivery') ? "yes" : "no";
        $zone = $request->input('zone');
        
        if ($status == "on") {
            $status = "yes";
        } else {
            $status = "no";
        }

        $address = $request->input('address');
        $email = $request->input('email');
        $user = Driver::find($id);
        $vehicle = Vehicle::where('id_conducteur', "=", $id)->first();
        if ($user) {
            $user->nom = $nom;
            $user->prenom = $prenom;
            $user->phone = $phone;
            $user->device_id = $device_id;
            $user->statut = $status;
            $user->address = $address;
            $user->email = $email;
            $user->bank_name = $bank;
            $user->branch_name = $branch;
            $user->holder_name = $holder;
            $user->account_no = $acc_no;
            $user->other_info = $other_info;
            $user->ifsc_code = $ifsc_code;
            $user->parcel_delivery = $parcel_delivery;
            if ($request->hasfile('photo')) {
                $destination = public_path('assets/images/driver/' . $user->photo_path);
                if (File::exists($destination)) {
                    File::delete($destination);
                }
                $file = $request->file('photo');
                $extenstion = $file->getClientOriginalExtension();
                $time = time() . '.' . $extenstion;
                $filename = 'driver_' . $id . '.' . $extenstion;
                $path = public_path('assets/images/driver/') . $filename;
                Image::make($file->getRealPath())->resize(150, 150)->save($path);

                //$file->move(public_path('assets/images/driver'), $filename);
                $user->photo_path = $filename;
            }
            $user->zone_id = $zone ? implode(',',$zone) : NULL;
            $user->save();
        }
       $vehicle_image = vehicleImages::where('id_driver', "=", $id)->first();
        if ($vehicle_image) {
            if ($request->hasfile('image_path')) {
                $destination = public_path('assets/images/vehicle/' . $vehicle_image->image_path);
                if (File::exists($destination)) {
                    File::delete($destination);
                }
                $file = $request->file('image_path');
                $extenstion = $file->getClientOriginalExtension();
                $time = time() . '.' . $extenstion;
                $filename = 'vehicle_' . $id . '.' . $extenstion;
                $file->move(public_path('assets/images/vehicle'), $filename);
                $vehicle_image->image_path = $filename;
                $vehicle_image->save();
            }
        }else{
            $vehicle_image = new vehicleImages;
            if ($request->hasfile('image_path')) {
                $file = $request->file('image_path');

                $extenstion = $file->getClientOriginalExtension();

                $time = time() . '.' . $extenstion;
                $filename = 'vehicle_' . $id . '.' . $extenstion;
                $file->move(public_path('assets/images/vehicle'), $filename);
                $vehicle_image->image_path = $filename;
                // $vehicle_image->selected_image = $Selectedfilename;
                $vehicle_image->id_vehicle = $vehicle->id;
                $vehicle_image->id_driver = $id;
                $vehicle_image->creer = date('Y-m-d H:i:s');
                $vehicle_image->modifier = date('Y-m-d H:i:s');
                $vehicle_image->save();

            }

        }
        if ($vehicle) {
            $vehicle->id_type_vehicule = $id_type_vehicule;
            $vehicle->brand = $brand;
            $vehicle->model = $model;
            $vehicle->color = $color;
            $vehicle->km = $km;
            $vehicle->milage = $milage;
            $vehicle->numberplate = $numberplate;
            $vehicle->passenger = $passenger;
            $vehicle->id_type_vehicule=$request->input('id_type_vehicule');
            $vehicle->save();
        }else{
            $vehicle = new Vehicle;
            $vehicle->id_type_vehicule = $id_type_vehicule;
            $vehicle->brand = $brand;
            $vehicle->model = $model;
            $vehicle->color = $color;
            $vehicle->km = $km;
            $vehicle->milage = $milage;
            $vehicle->numberplate = $numberplate;
            $vehicle->passenger = $passenger;
            $vehicle->id_conducteur = $id;
            $vehicle->car_make = '';
            $vehicle->statut = 'yes';
            $vehicle->creer = date('Y-m-d H:i:s');
            $vehicle->modifier = date('Y-m-d H:i:s');
            $vehicle->updated_at = date('Y-m-d H:i:s');

            $vehicle->save();

        }


        return redirect('drivers');
    }

    public function show($id)
    {
        $driver = Driver::where('id', "=", $id)->first();

        $vehicle=DB::table('tj_vehicule')->leftjoin('brands','brands.id','=','tj_vehicule.brand')
                                        ->leftjoin('car_model','car_model.id','=','tj_vehicule.model')
                                        ->select('tj_vehicule.*','brands.name as brand','car_model.name as model')
                                        ->where('id_conducteur', "=", $id)->first();

        $currency = Currency::where('statut', 'yes')->first();
        $transactions = DB::table('tj_conducteur_transaction')
            ->join('tj_payment_method', 'tj_conducteur_transaction.payment_method', '=', 'tj_payment_method.libelle')
            ->select('tj_conducteur_transaction.*', 'tj_payment_method.image')
            ->where('id_conducteur', "=", $id)->orderBy('tj_conducteur_transaction.id','desc')->paginate(10);

        $rides = Requests::
        leftjoin('tj_user_app', 'tj_requete.id_user_app', '=', 'tj_user_app.id')
            ->join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
            ->join('tj_payment_method', 'tj_requete.id_payment_method', '=', 'tj_payment_method.id')
            ->select('tj_requete.id', 'tj_requete.statut', 'tj_requete.statut_paiement', 'tj_requete.depart_name', 'tj_requete.destination_name', 'tj_requete.distance', 'tj_requete.montant', 'tj_requete.creer', 'tj_conducteur.id as driver_id', 'tj_conducteur.prenom as driverPrenom', 'tj_conducteur.nom as driverNom', 'tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom', 'tj_payment_method.libelle', 'tj_payment_method.image','tj_requete.ride_type')
            ->where('tj_requete.id_conducteur', $id)
            ->orderBy('tj_requete.id', 'DESC')
            ->paginate(10);

        $parcelOrders = ParcelOrder::
              join('tj_user_app', 'parcel_orders.id_user_app', '=', 'tj_user_app.id')
            ->join('tj_conducteur', 'parcel_orders.id_conducteur', '=', 'tj_conducteur.id')
            ->join('tj_payment_method', 'parcel_orders.id_payment_method', '=', 'tj_payment_method.id')
            ->select('parcel_orders.id', 'parcel_orders.status','parcel_orders.created_at','tj_user_app.id as user_id', 'tj_user_app.prenom as userPrenom', 'tj_user_app.nom as userNom')
            ->where('parcel_orders.id_conducteur', $id)
            ->orderBy('parcel_orders.id', 'DESC')
            ->paginate(10);


        $driverRating = "0.0";

        $driver_rating = DB::table('tj_note')
            ->select(DB::raw("COUNT(id) as ratingCount"), DB::raw("SUM(niveau) as ratingSum"))
            ->where('id_conducteur', '=', $id)
            ->first();
        if (!empty($driver_rating)) {
            if ($driver_rating->ratingCount > 0) {
                $driverRating = number_format(($driver_rating->ratingSum / $driver_rating->ratingCount), 1);
            }
        }

        $zone_name = '';
        if($driver->zone_id){
            $zone_id = explode(',',$driver->zone_id);
            $zones = Zone::whereIn('id',$zone_id)->get();
            foreach($zones as $zone){
                $zone_name .= $zone->name.', ';
            }
            $zone_name = rtrim($zone_name,', ');
        }
        
        return view('drivers.show')->with('driver', $driver)->with("vehicle", $vehicle)->with("rides", $rides)->with("currency", $currency)->with('transactions',$transactions)->with('driverRating',$driverRating)->with('parcelOrders',$parcelOrders)->with('zone_name',$zone_name);
    }

    public function changeStatus($id)
    {
        $driver = Driver::find($id);
        if ($driver->statut == 'no') {
            $driver->statut = 'yes';
        } else {
            $driver->statut = 'no';
        }

        $driver->save();
        return redirect()->back();

    }

    public function documentView($id)
    {
		$driver = Driver::where('id', "=", $id)->first();

		$admin_documents = DB::table('admin_documents')->where('admin_documents.is_enabled','Yes')->get();


		$admin_documents->map(function ($admin_document, $key) use ($id){
			$driver_document = DB::table('driver_document')->where('driver_id',$id)->where('document_id',$admin_document->id)->first();
			$admin_document->driver_document = $driver_document;
			return $admin_document;
		});

        return view('drivers.viewDocument')->with('admin_documents',$admin_documents)->with('driver',$driver);
    }



      public function uploaddocument($id,$doc_id)
      {
          $document=DB::table('admin_documents')->where('is_enabled','=','Yes')->get();
          return view('drivers.uploaddocument')->with('id', $id)->with('document_id',$doc_id)->with('document',$document);
      }


    public function updatedocument(Request $request, $id)
    {

        $validator = Validator::make($request->all(), $rules = [
            'document_path' => "mimes:doc,pdf,docx,zip,txt,jpeg,png,jpg",

        ], $messages = [
            'document_path.required' => 'The docuemnt field is required!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $document_id = $request->input('document_id');

		$document_name = DB::table('admin_documents')->where('id', $document_id)->first();
        
        $driver = DriversDocuments::where('driver_id', "=", $id)->where('document_id','=',$document_id)->first();

        if ($driver) {

            if ($request->hasfile('document_path')) {

                $destination = public_path('assets/images/driver/documents/' . $driver->document_path);

                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('document_path');

                $extenstion = $file->getClientOriginalExtension();

                $filename = str_replace(' ','_',$document_name->title) . '_' . time() . '.' . $extenstion;

                $file->move(public_path('assets/images/driver/documents'), $filename);

                $driver->document_path = $filename;

                $driver->document_status = 'Pending';
            }

            $driver->save();

        }else{

          $driver = new DriversDocuments;

          if ($request->hasfile('document_path')) {

              $file = $request->file('document_path');

              $extenstion = $file->getClientOriginalExtension();

              $filename = str_replace(' ','_',$document_name->title) . '_' . time() . '.' . $extenstion;

              $file->move(public_path('assets/images/driver/documents'), $filename);

              $driver->document_path = $filename;

              $driver->document_status = 'Pending';
          }

          $driver->driver_id = $id;

          $driver->document_id = $request->input('document_id');

          $driver->save();
        }

		return redirect()->route('driver.documentView',$id);
        
    }

    public function toggalSwitch(Request $request)
    {
        $ischeck = $request->input('ischeck');
        $id = $request->input('id');
        $driver = Driver::find($id);

        if ($ischeck == "true") {
            $driver->statut = 'yes';
        } else {
            $driver->statut = 'no';
        }
        $driver->save();

    }
    public function addWallet(Request $request, $id)
    {
        $driver = Driver::find($id);
        $amount = $request->amount;
        if ($amount == '' || $amount == null) {
            $amount = 0;
        }
        if ($driver) {
            $driverWallet = floatval($driver->amount) + floatval($amount);
            $driver->amount = (string) $driverWallet;
            $driver->save();
        }
        $date = date('Y-m-d H:i:s');

        DB::table('tj_conducteur_transaction')->insert([
            'amount' => $amount,
            'payment_method' => 'Wallet',
            'id_conducteur' => $id,
            'creer' => $date
        ]);

        $driver = Driver::find($id);
        $txnId = uniqid(0,999);
        $email=$driver->email;
        $date = date('d F Y');

        if (!empty($email)) {

            $emailsubject = '';
            $emailmessage = '';
            $emailtemplate = DB::table('email_template')->select('*')->where('type', 'wallet_topup')->first();
            if (!empty($emailtemplate)) {
                $emailsubject = $emailtemplate->subject;
                $emailmessage = $emailtemplate->message;
                $send_to_admin = $emailtemplate->send_to_admin;
            }
            $currencyData = DB::table('tj_currency')->select('*')->where('statut', 'yes')->first();
            if ($currencyData->symbol_at_right == "true") {
                $amount = number_format($amount, $currencyData->decimal_digit) . $currencyData->symbole;
                $newBalance = number_format($driver['amount'], $currencyData->decimal_digit) . $currencyData->symbole;
            } else {
                $amount = $currencyData->symbole . number_format($amount, $currencyData->decimal_digit);
                $newBalance = $currencyData->symbole . number_format($driver['amount'], $currencyData->decimal_digit);

            }
            $contact_us_email = DB::table('tj_settings')->select('contact_us_email')->value('contact_us_email');
            $contact_us_email = $contact_us_email ? $contact_us_email : 'none@none.com';


            $app_name = env('APP_NAME', 'Cabme');
            
            if($send_to_admin=="true"){
                $to = $email . "," . $contact_us_email;
            }else{
                $to = $email;

            }

            $emailmessage = str_replace("{AppName}", $app_name, $emailmessage);
            $emailmessage = str_replace("{UserName}", $driver['nom'] . " " . $driver['prenom'], $emailmessage);
            $emailmessage = str_replace("{Amount}", $amount, $emailmessage);
            $emailmessage = str_replace("{PaymentMethod}", 'Wallet', $emailmessage);
            $emailmessage = str_replace('{TransactionId}', $txnId, $emailmessage);
            $emailmessage = str_replace('{Balance}', $newBalance, $emailmessage);
            $emailmessage = str_replace('{Date}', $date, $emailmessage);

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: ' . $app_name . '<' . $contact_us_email . '>' . "\r\n";
            mail($to, $emailsubject, $emailmessage, $headers);
        }

        return redirect('driver/show/' . $id);
    }


}
