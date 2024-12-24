<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Currency;
use App\Models\VehicleType;
use App\Models\Settings;
use App\Models\RentalVehicleType;
use Illuminate\Http\Request;
use DB;
use PDO;
use App\Models\Requests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\CustomClasses\addonpricing;



class VehicleController extends Controller
{

  public function __construct()
  {
   $this->limit=20;   
 }
	  /**
	    * Display a listing of the resource.
	    *
	    * @return \Illuminate\Http\Response
	    */
	  public function index()
	  {

     $driver = Vehicle::all();
     $driver = Vehicle::paginate($this->limit);
     return response()->json($driver);
   }
   /*Register Vehicle */
   public function register(Request $request)
   {

     $brand = $request->get('brand');
     $prenom = str_replace("'","\'",$brand);
     $model = $request->get('model');
     $color = $request->get('color');
     $numberplate = $request->get('carregistration');
     $passenger = $request->get('passenger');
     $id_driver = $request->get('id_driver');
     $id_categorie_vehicle = $request->get('id_categorie_vehicle');
     $date_heure = date('Y-m-d H:i:s');
     $car_make = $request->get('car_make');
     $milage = $request->get('milage');
     $km = $request->get('km_driven');
     $zone_id = $request->get('zone_id');
    
     $chkdriver = Driver::where('id',$id_driver)->first();
     if(!empty($chkdriver)){

       $chkid = Vehicle::where('id_conducteur',$id_driver)->first();

       if (!empty($chkid)){ 

          $row = $chkid->toArray();
          $id_vehicule = $row['id'];
          $updatedata = DB::update('update tj_vehicule set brand = ?,model = ?,passenger = ?,color = ?,numberplate = ?,modifier = ?,id_type_vehicule = ?,car_make = ?,km = ?,milage = ? where id = ?',[$brand,$model,$passenger,$color,$numberplate,$date_heure,$id_categorie_vehicle,$car_make,$km,$milage,$id_vehicule]);

          if (!empty($updatedata)) {
            $response['success']= 'Success';
            $response['error']= null;
            $response['message']= 'Vehicle updated successfully';

            $get_vehicule = Vehicle::where('id',$id_vehicule)->first();
            $row = $get_vehicule->toArray();
            $response['data'] = $row;
          } else {
            $response['success']= 'Failed';
            $response['error']= 'Error while updating';
          }

          $updatedata = DB::update('update tj_conducteur set zone_id = ? where id = ?',[$zone_id,$id_driver]);

       } else {    

          $insertdata = DB::insert("insert into tj_vehicule(passenger,brand,model,color,numberplate,id_conducteur,statut,creer,updated_at,id_type_vehicule,car_make,milage,km)
            values('".$passenger."','".$brand."','".$model."','".$color."','".$numberplate."','".$id_driver."','yes','".$date_heure."','".$date_heure."','".$id_categorie_vehicle."','".$car_make."','".$milage."','".$km."')");
          $id=DB::getPdo()->lastInsertId();
          
          if ($id > 0) {
            $response['success']= 'success';
            $response['error']= null;
            $response['message']= 'Vehicle Added successfully';
            $get_vehicule = Vehicle::where('id',$id)->first();
            $row = $get_vehicule->toArray();
            $response['data'] = $row;
          } else {
            $response['success']= 'Failed';
            $response['error']= 'Error while Add data';
          }

          $updatedata = DB::update('update tj_conducteur set statut_vehicule = ?, zone_id = ? where id = ?',['yes',$zone_id,$id_driver]);
      }

     }else{
        $response['success']= 'Failed';
        $response['error']= 'Driver Not Found';
     }

     return response()->json($response);
   }

   /* Get Booking Types --------------------------- */
   public function getBookingTypes()
   {
    
      $output = array();
      $settings = DB::table('tj_settings')
                      ->first();
      $sql = DB::table('bookingtypes')
                ->select('id','bookingtype','imageid','fixlatlongs','latitude','longitude','tagline')
                ->get();

              foreach ($sql as $row) 
              {
                $row->id = $row->id;
                $row->bookingtype = $row->bookingtype;
                $row->fixlatlongs = $row->fixlatlongs;
                $row->latitude = $row->latitude;
                $row->longitude= $row->longitude;
                $row->tagline= $row->tagline;
                $row->imageid = asset('assets/images') . '/' . $row->imageid;

                $output[] = $row; 
              }
              $response['usercanbookridebeforexminutes'] = $settings->user_ride_schedule_time_minute;
              $sqlcurrency = DB::table('tj_currency')
              ->select('symbole')
              ->where('statut', '=', 'yes')
              ->get();

            if (!empty($output)) {
              $response['success'] = 'success';
              $response['sourceordest_label'] = 'Rajiv Gandhi International Airport - Hyderabad'; 
              $response['currency'] = $sqlcurrency[0]->symbole;
              $response['error'] = null;
              $response['message'] = 'Successfully';
              $response['data'] = $output;


          } else {
              $response['success'] = 'Failed';
              $response['error'] = 'Failed to fetch data';
          }
  
          return response()->json($response);
   }



   /*Update Vehicle NumberPlate */
   public function updateVehicle(Request $request)
   {
     $id_user = $request->get('id_conducteur');
     $numberplate = $request->get('numberplate');
     $numberplate = str_replace("'","\'",$numberplate);
     $date_heure = date('Y-m-d H:i:s');
     if(!empty($id_user) && !empty($numberplate)){
       $updatedata = DB::update('update tj_vehicule set numberplate = ?, modifier = ? where id_conducteur = ?',[$numberplate,$date_heure,$id_user]);

       if (!empty($updatedata)) {
         $sql = Vehicle::where('id_conducteur',$id_user)->first();
         $row = $sql->toArray();
         $response['success'] = 'success';
         $response['error'] = null;
         $response['message'] = 'status successfully updated';
         $response['data'] = $row;
       } else {
        $response['success'] = 'Failed';
        $response['error'] = 'failed to update';
      }
    } else{
      $response['success'] = 'Failed';
      $response['error'] = 'some field are missing';
    }
    return response()->json($response);

  }

  /*Update Vehicle color */
  public function updateVehicleColor(Request $request)
  {
    $id_user = $request->get('id_conducteur');
    $color = $request->get('color');
    $color = str_replace("'","\'",$color);
    $date_heure = date('Y-m-d H:i:s');
    if(!empty($id_user) && !empty($color)){
      $updatedata = DB::table('tj_vehicule')
      ->where('id_conducteur', $id_user)
      ->update(['color' => $color,'modifier' => $date_heure]);
      if (!empty($updatedata)) {
        $sql = Vehicle::where('id_conducteur',$id_user)->first();
        $row = $sql->toArray();
        $response['success'] = 'success';
        $response['error'] = null;
        $response['message'] = 'status successfully updated';
        $response['data'] = $row;
      } else {
        $response['success'] = 'Failed';
        $response['error'] = 'failed to update';
      }
    } else{
      $response['success'] = 'Failed';
      $response['error'] = 'some field are missing';
    }

    return response()->json($response);

  }

  /*Update Vehicle Brand */
  public function updateVehicleBrand(Request $request)
  {

   $id_user = $request->get('id_conducteur');
   $brand = $request->get('brand');
   $date_heure = date('Y-m-d H:i:s');

   if(!empty($id_user) && !empty($brand)){

     $updatedata = DB::table('tj_vehicule')
     ->where('id_conducteur', $id_user)
     ->update(['brand' => $brand,'modifier' => $date_heure]);

     if (!empty($updatedata)) {
      $sql = Vehicle::where('id_conducteur',$id_user)->first();
      $row = $sql->toArray();
      $response['success'] = 'success';
      $response['error'] = null;
      $response['message'] = 'status successfully updated';
      $response['data'] = $row;
    } else {
      $response['success'] = 'Failed';
      $response['error'] = 'failed to update';
    }
  } else{
    $response['success'] = 'Failed';
    $response['error'] = 'some field are missing';
  }
  return response()->json($response);

}

/*Update Vehicle Model */
public function updateVehicleModel(Request $request)
{
  $id_user = $request->get('id_conducteur');
  $model = $request->get('model');
  $date_heure = date('Y-m-d H:i:s');

  if(!empty($id_user) && !empty($model)){

    $updatedata = DB::update('update tj_vehicule set model = ?, modifier = ? where id_conducteur = ?',[$model,$date_heure,$id_user]);
    
    if (!empty($updatedata)) {
      $sql = Vehicle::where('id_conducteur',$id_user)->first();
      $row = $sql->toArray();
      $response['success'] = 'success';
      $response['error'] = null;
      $response['message'] = 'status successfully updated';
      $response['data'] = $row;
    } else {
      $response['success'] = 'Failed';
      $response['error'] = 'failed to update';
    }
  } else{
    $response['success'] = 'Failed';
    $response['error'] = 'some field are missing';
  }
  return response()->json($response);

}

/*Update Vehicle type */
public function updateVehicleType(Request $request)
{            
  $id_user = $request->get('id_conducteur');

  $id_vehicle_type = $request->get('id_vehicle_type');
  $date_heure = date('Y-m-d H:i:s');
  if(!empty($id_user) && !empty($id_vehicle_type)){
    $updatedata = DB::update('update tj_vehicule set id_type_vehicule = ?, modifier = ? where id_conducteur = ?',[$id_vehicle_type,$date_heure,$id_user]);
    
    if (!empty($updatedata)) {
      $sql = Vehicle::where('id_conducteur',$id_user)->first();
      $row = $sql->toArray();
      $response['success'] = 'success';
      $response['error'] = null;
      $response['message'] = 'status successfully updated';
      $response['data'] = $row;
    } else {
      $response['success'] = 'Failed';
      $response['error'] = 'failed to update';
    }
  } else{
    $response['success'] = 'Failed';
    $response['error'] = 'some field are missing';
  }
  return response()->json($response);

}

/*get Vehicle data */
public function getVehicleData(Request $request)
{

  $sql = RentalVehicleType::select('tj_type_vehicule_rental.*')
  ->where('status','=','yes')
  ->get();
  $output = array();	
  foreach($sql as $row){
    $id_vehicule = $row->id;

    $sql_nb = DB::table('tj_location_vehicule')
    ->select(DB::raw("COUNT(id) as nb"))
    ->where('id_vehicule_rental','=',$id_vehicule)
    ->where('statut','=','accept')
    ->get();

    $nb = 0;
    foreach($sql_nb as $row_nb){
      $nb = $row_nb->nb;
    }
    $row->nb_reserve = $nb;
    if($row->image != ''){
      if(file_exists(public_path('assets/images/type_vehicle_rental'.'/'.$row->image  )))
      {
        $image_user = asset('assets/images/type_vehicle_rental').'/'. $row->image ;
      }
      else
      {
        $image_user = asset('assets/images/placeholder_image.jpg');

      }
      $row->image  = $image_user;
    }
    $output[] = $row;

  }

  if(!empty($sql)){
    $response['success']= 'success';
    $response['error']= null;
    $response['message']= 'successfully';
    $response['data']= $output;
  }else{
    $response['success']= 'Failed';
    $response['error']= 'Failed to fetch data';
    $response['message']= 'successfully';
  }
  
  return response()->json($response);
}

/*get Vehicle category vehicle_models */
public function getVehicleCategoryData(Request $request)
{
  $sql = VehicleType::select('*')
  ->where('status','=','Yes')
  ->where('deleted_at','=',null)
  ->get();

  $output = array();	
  foreach($sql as $row){
    $selected_image = $row->selected_image;

    if (file_exists(public_path('assets/images/type_vehicle'.'/'.$row->image)) && !empty($row->image))
    {
      $image_path = asset('assets/images/type_vehicle').'/'.$row->image;
    }else{
      $image_path	=	asset('assets/images/placeholder_image.jpg');

    }
    if (file_exists(public_path('assets/images/type_vehicle'.'/'.$row->selected_image)) && !empty($row->selected_image))
    {
      $selected_image_path = asset('assets/images/type_vehicle').'/'.$row->selected_image;
    }else{
      $selected_image_path	=	asset('assets/images/placeholder_image.jpg');

    }
    $row->image = $image_path;
    $row->selected_image_path = $selected_image_path;
    $get_commission = DB::table('tj_commission')
    ->select('*')
    ->where('type','=','fixed')
    ->get();

    foreach($get_commission as $row_commission){
      $row->statut_commission = $row_commission->statut;
      $row->commission = $row_commission->value;
      $row->type = $row_commission->type;
    }
    
    $get_commission_perc = DB::table('tj_commission')
    ->select('*')
    ->where('type','=','percentage')
    ->get();

    foreach($get_commission_perc as $row_commission_perc){
      $row->statut_commission_perc = $row_commission_perc->statut;
      $row->commission_perc = $row_commission_perc->value;
      $row->type_perc = $row_commission_perc->type;
    }
    
    //Delivery Charges
    $get_delivery_chagres = DB::table('delivery_charges')
    ->select('*')
    ->where('id_vehicle_type','=',$row->id)
    ->get();

    foreach($get_delivery_chagres as $row_delivery_chagres){
      $row->delivery_charges = $row_delivery_chagres->delivery_charges_per_km;
      $row->minimum_delivery_charges = $row_delivery_chagres->minimum_delivery_charges;
      $row->minimum_delivery_charges_within = $row_delivery_chagres->minimum_delivery_charges_within_km;
    }

    $output[] = $row;
  }
  if(!empty($sql)){
    $response['success']= 'Success';
    $response['error']= null;
    $response['message']= 'Successfully fetch data';
    $response['data'] = $output;
  }else{
    $response['success']= 'Failed';
    $response['error']= 'Failed To Fetch Data';
  }
  return response()->json($response);
  
}


public function getCarModels(Request $request)
{

    $ride_required_date = $request->get('ride_requird_date');

    $rideRequiredTime = $request->get('ride_requird_time');

    // Check if the input is in 12-hour or 24-hour format
    if (preg_match('/\d{1,2}:\d{2}\s?(AM|PM)/i', $rideRequiredTime)) {
        // 12-hour format
        $ride_required_time = Carbon::createFromFormat('h:i A', $rideRequiredTime)->format('H:i');
    } else {
        // 24-hour format
        $ride_required_time = Carbon::createFromFormat('H:i', $rideRequiredTime)->format('H:i');
    }

    //$ride_required_time = Carbon::createFromFormat('h:i A', $request->get('ride_requird_time'))->format('H:i');
    $booking_type_id =(int) $request->get('booking_type_id');
        
    $currency = Currency::where('statut', 'yes')->first();

    // Get Taxes ----------------------------------------------
    $AllTaxes = DB::table('tj_tax')
                ->select('*')
                ->where('statut','=',"yes")
                ->get();
    // End Get Taxes ------------------------------------------

    // Get Car Types  ----------------------------------------------
    $Vechicle_Types = DB::table('tj_type_vehicule')
                ->select('id','libelle as vehicleType')
                ->where('status','=',"yes")
                ->get();
    // End Get Taxes ------------------------------------------

          $pdo = DB::getPdo();
          $stmt = $pdo->prepare('CALL GetCarModelsForBooking(:BookingDate,:BookingTime,:BookingTypeID)');
          $stmt->bindParam(':BookingDate', $ride_required_date, PDO::PARAM_STR);
          $stmt->bindParam(':BookingTime', $ride_required_time, PDO::PARAM_STR);
          $stmt->bindParam(':BookingTypeID', $booking_type_id, PDO::PARAM_INT);
          $stmt->execute();

          // Fetch the first result set
          $listmodelcars = $stmt->fetchAll(PDO::FETCH_OBJ);
          $count = count($listmodelcars);
        foreach($listmodelcars as $row)
        {
       
         if (file_exists(public_path('assets/images/vehicle_models'.'/'.$row->imageid)) && !empty($row->imageid))
          {
            $image_path = asset('assets/images/vehicle_models').'/'.$row->imageid;
          }else{
            $image_path	=	asset('assets/images/placeholder_image.jpg');
          }
          
          $row->imageid = $image_path;
          $row->modelid = $row->modelid;
          $row->brandid = $row->brandid;
          $row->brandname = $row->brandname;
          $row->modelname = $row->modelname;
          $row->allow_cod = $row->allow_cod;
          $row->displayprice = $currency->symbole . "" . number_format($row->price,$currency->decimal_digit);
          $row->price = $row->price;
          $row->car_type = $row->car_type;

          $totalAmount = $row->price;
          $totalTaxAmount = 0;

          if (!empty($AllTaxes)) {

            foreach($AllTaxes as $taxrow)
              {

                if ($taxrow->type == "Percentage") {
                    $taxValue = (floatval($taxrow->value) * $totalAmount) / 100;
                    $taxlabel = $taxrow->libelle;
                    $value = $taxrow->value."%";
                } else {
                    $taxValue = floatval($taxrow->value);
                    $taxlabel = $taxrow->libelle;

                    if ($currency->symbol_at_right == "true") {
                        $value = number_format($taxrow->value,$currency->decimal_digit) . "" . $currency->symbole;
                    } else {
                        $value = $currency->symbole."".number_format($taxrow->value,$currency->decimal_digit);
                    }
                    
                }

                //$totalTaxAmount += floatval(number_format($taxValue,$currency->decimal_digit));
                $totalTaxAmount += floatval($taxValue);
                if ($currency->symbol_at_right == "true") {
                    $taxValueAmount = number_format($taxValue,$currency->decimal_digit) . "" . $currency->symbole;
                } else {
                    $taxValueAmount = $currency->symbole . "" . number_format($taxValue,$currency->decimal_digit);
                }

              $taxrow1["taxlabel"] = $taxlabel . "(" . $value . ")";
              $taxrow1["taxValueAmount"] = (string) $taxValue;//$taxValueAmount;
              $taxrow1["taxType"] = $taxrow->type;
              $taxrow1["tax"] = $taxrow->value;

              $taxarray[] = $taxrow1;
            }

           $totalAmount = floatval($totalAmount) + floatval($totalTaxAmount);
           
           $row->finalAmount = $currency->symbole . "" . number_format($totalAmount,$currency->decimal_digit); 
           $row->tax = $taxarray;
           $taxarray = null;
       }

           $CarModelsoutput[] = $row;
        }

        foreach($Vechicle_Types as $vechiclerow)
        {
          $vechiclerow->id = $vechiclerow->id;
          $vechiclerow->vehicleType =  $vechiclerow->vehicleType;

          $VehicleTypesoutput[] = $vechiclerow;
        }

        if(!empty($CarModelsoutput)){
          $response['success']= 'Success';
          $response['error']= null;
          $response['message']= 'Successfully fetch data';
          $response['carmodels'] = $CarModelsoutput;// $output[0];
          $response['vehicleTypes'] = $VehicleTypesoutput;// $output[1];
          //$response["modelscount"] = $count .'::'.$ride_required_date.'::'.$ride_required_time.'::'.$booking_type_id;
        }else{
          $response['success']= 'Failed';
          $response['error']= 'Failed To Fetch Data';
        }
        return response()->json($response);
  
  }

  public function getCoupons(Request $request)
  {

      $sql = DB::table('tj_discount')
            ->select('id','code','discount','type','discription','expire_at')
            ->where('statut','=',"yes")
            ->get();
  
    $output = array();	
    foreach($sql as $row){
     
      // $row->id = $row->id;
      // $row->id = $row->id;
      // $row->id = $row->id;
      $output[] = $row;
    }

    if(!empty($output)){
      $response['success']= 'Success';
      $response['error']= null;
      $response['message']= 'Successfully fetch data';
      $response['data'] = $output;
    }else{
      $response['success']= 'Failed';
      $response['error']= 'Failed To Fetch Data';
    }
    return response()->json($response);
    
  }

  public function getaddOnsPricing(Request $request)
  {

    $ride_id = $request->get('ride_id');
    
    if (!empty($ride_id))
    {
    //   $hoursgap =DB::table('tj_requete as current_ride')
    //   ->join('tj_requete as next_ride', 'next_ride.id_conducteur', '=', 'current_ride.id_conducteur')
    //   ->select(DB::raw(
    //       'TIMESTAMPDIFF(HOUR, 
    //           TIMESTAMP(current_ride.ride_required_on_date, current_ride.ride_required_on_time), 
    //           TIMESTAMP(next_ride.ride_required_on_date, next_ride.ride_required_on_time)
    //       ) as hours_gap','current_ride.booking_type_id'
    //   ))
    //   ->where('current_ride.id', $ride_id)
    //   ->whereRaw('TIMESTAMP(next_ride.ride_required_on_date, next_ride.ride_required_on_time) > TIMESTAMP(current_ride.ride_required_on_date, current_ride.ride_required_on_time)')
    //   ->orderBy('next_ride.creer', 'ASC')
    //   ->limit(1)
    //   ->first();



      // if(!empty($hoursgap))
      // {
      //   if($hoursgap->hours_gap > 0)
      //   {
      //   if( $hoursgap->booking_type_id=="1" || $hoursgap->booking_type_id=="2")
      //     {
      //       $hoursgap->hours_gap = (int)$hoursgap->hours_gap - 6;
      //     }
      //   else if($hoursgap->booking_type_id=="3"){
      //     $hoursgap->hours_gap = (int)$hoursgap->hours_gap - 10;
      //   }

      //   if($hoursgap->hours_gap > 0)
      //   {
      //     $sql = DB::table('tj_requete')
      //           ->Join('pricing_by_car_models', 'pricing_by_car_models.carmodelid', '=', 'tj_requete.model_id')
      //           ->select('tj_requete.id_user_app','tj_requete.model_id','tj_requete.id_conducteur',
      //           'pricing_by_car_models.pricingid','pricing_by_car_models.price as AddOnPricing','pricing_by_car_models.hours','pricing_by_car_models.kms',
      //           DB::raw('CONCAT(CAST(pricing_by_car_models.hours AS CHAR), " hours | ", CAST(pricing_by_car_models.kms AS CHAR), " KMs") as add_on_label'))
      //           ->where('pricing_by_car_models.is_add_on','=','yes')
      //           ->where('pricing_by_car_models.status','=','yes')
      //           ->where('pricing_by_car_models.hours','<=',$hoursgap->hours_gap)
      //           ->where('tj_requete.id','=',$ride_id)
      //           ->get();
      //   }
      //   else{
      //     $sql = DB::table('tj_requete')
      //         ->Join('pricing_by_car_models', 'pricing_by_car_models.carmodelid', '=', 'tj_requete.model_id')
      //         ->select('tj_requete.id_user_app','tj_requete.model_id','tj_requete.id_conducteur',
      //         'pricing_by_car_models.pricingid','pricing_by_car_models.price as AddOnPricing','pricing_by_car_models.hours','pricing_by_car_models.kms',
      //         DB::raw('CONCAT(CAST(pricing_by_car_models.hours AS CHAR), " hours | ", CAST(pricing_by_car_models.kms AS CHAR), " KMs") as add_on_label'))
      //         ->where('pricing_by_car_models.is_add_on','=','yes')
      //         ->where('pricing_by_car_models.status','=','yes')
      //         ->where('tj_requete.id','=',$ride_id)
      //         ->where('1','!=','1')
      //         ->get();
      //   }
      // }
      // else{
      //   $sql = DB::table('tj_requete')
      //       ->Join('pricing_by_car_models', 'pricing_by_car_models.carmodelid', '=', 'tj_requete.model_id')
      //       ->select('tj_requete.id_user_app','tj_requete.model_id','tj_requete.id_conducteur',
      //       'pricing_by_car_models.pricingid','pricing_by_car_models.price as AddOnPricing','pricing_by_car_models.hours','pricing_by_car_models.kms',
      //       DB::raw('CONCAT(CAST(pricing_by_car_models.hours AS CHAR), " hours | ", CAST(pricing_by_car_models.kms AS CHAR), " KMs") as add_on_label'))
      //       ->where('pricing_by_car_models.is_add_on','=','yes')
      //       ->where('pricing_by_car_models.status','=','yes')
      //       ->where('tj_requete.id','=',$ride_id)
      //       ->where('1','!=','1')
      //       ->get();
      // }
      // }
      // else{
      //   $sql = DB::table('tj_requete')
      //       ->Join('pricing_by_car_models', 'pricing_by_car_models.carmodelid', '=', 'tj_requete.model_id')
      //       ->select('tj_requete.id_user_app','tj_requete.model_id','tj_requete.id_conducteur',
      //       'pricing_by_car_models.pricingid','pricing_by_car_models.price as AddOnPricing','pricing_by_car_models.hours','pricing_by_car_models.kms',
      //       DB::raw('CONCAT(CAST(pricing_by_car_models.hours AS CHAR), " hours | ", CAST(pricing_by_car_models.kms AS CHAR), " KMs") as add_on_label'))
      //       ->where('pricing_by_car_models.is_add_on','=','yes')
      //       ->where('pricing_by_car_models.status','=','yes')
      //       ->where('tj_requete.id','=',$ride_id)
      //       ->get();
      // }
    //}

    $pdo = DB::getPdo();
    $stmt = $pdo->prepare('CALL GetAddOnsPricingAndAvailability(:ride_id)');
    $stmt->bindParam(':ride_id', $ride_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the first result set
    $listmodelcars = $stmt->fetchAll(PDO::FETCH_OBJ);
    //$count = count($listmodelcars);
    $stmt->closeCursor();
    $rowOutput='';
    $CarModelID = '';
    $output = array();	
    
    $customObjects = array_map(function ($row) {
      // return (object) [
      //   'id_user_app' => (string) $row->id_user_app, 
      //   'model_id' => $row->model_id, 
      //   'id_conducteur' => (string) $row->id_conducteur,
      //   'pricingid' => $row->pricingid,
      //   'AddOnPricing' => $row->AddOnPricing,
      //   'hours' => $row->hours,
      //   'kms' => $row->kms,
      //   'add_on_label' => $row->add_on_label
      // ];
       return new addonpricing((string)$row->id_user_app, $row->model_id, (string)$row->id_conducteur,$row->pricingid,$row->AddOnPricing,$row->hours,$row->kms,$row->add_on_label);
  }, $listmodelcars);

    foreach($customObjects as $row){
      $CarModelID = $row->model_id;
      $output[] =$row;
    }
    
    $allowcod = 'no';

    if (!empty($CarModelID)){
      $sqlCod = DB::table('car_model')
      ->select('car_model.allow_cod')
      ->where('car_model.status','=','yes')
      ->where('car_model.id','=',$CarModelID)
      ->get();

      
      foreach($sqlCod as $rowCod){
        $allowcod = $rowCod->allow_cod;
      }
    }
    if(!empty($output)){
      $response['success']= 'Success';
      $response['error']= null;
      $response['message']= 'Successfully fetch data';
      $response['data'] = $customObjects;
      $response['allow_cod'] = $allowcod;
    }else{
      $response['success']= 'Failed';
      $response['error']= 'Failed To Fetch Data';
    }
    }
    else{
      $response['success']= 'Failed';
      $response['error']= 'Failed To Fetch Data';
    }
    return response()->json($response);
  }

  public function getaddOnsTaxPricing(Request $request)
  {

    $addon_id = $request->get('addon_id');
    
    if (!empty($addon_id))
    {
      // $pdo = DB::getPdo();
      // $stmt = $pdo->prepare('CALL GetRideAddonData(:addon_id,@intout)');
      // $stmt->bindParam(':addon_id', $addon_id, PDO::PARAM_INT);
      // $stmt->execute();

      // $stmt = $pdo->query('SELECT @intout as INTRETURN');
      // $result = $stmt->fetch(PDO::FETCH_ASSOC);
      // $intout = $result['INTRETURN'];

      

      // // Close the cursor to free resources
      // $stmt->closeCursor();
      $procedureResult = DB::select('CALL `GetRideAddonData`(?, @intreturn)', [$addon_id]);
      if($procedureResult)
      {
        foreach($procedureResult as $row)
        {
          $rowOutput = $row;
        }
      }

    }
    

    //$rowOutput=$procedureResult;
   
    if(!empty($rowOutput)){
      $response['success']= 'Success';
      $response['error']= null;
      $response['message']= 'Successfully fetch data';
      $response['data'] = $rowOutput;
      
    }else{
      $response['success']= 'Failed';
      $response['error']= 'Failed To Fetch Data';
    }
    return response()->json($response);
    
  }

  public function updateAddonPaymentStatus(Request $request)
  {

    $addon_id = $request->get('addon_id');
    $bookingid = $request->get('booking_id');
    $paymentstatus = $request->get('payment_status');
    $transactionid = $request->get('transaction_id');
    $paymentmethodid = $request->get('payment_method_id');
    
    if (!empty($addon_id) && !empty($bookingid) && !empty($paymentstatus) && !empty($paymentmethodid))
    {
      $pdo = DB::getPdo();
      $stmt = $pdo->prepare('CALL UpdateAddonPaymentStatus(:bookingid, :addonid,:payment_method_id,:paymentstatus,:transactionid,@intout)');
      $stmt->bindParam(':bookingid', $bookingid, PDO::PARAM_INT);
      $stmt->bindParam(':addonid', $addon_id, PDO::PARAM_INT);
      $stmt->bindParam(':payment_method_id', $paymentmethodid, PDO::PARAM_INT);
      $stmt->bindParam(':paymentstatus', $paymentstatus, PDO::PARAM_STR);
      $stmt->bindParam(':transactionid', $transactionid, PDO::PARAM_STR);
      $stmt->execute();

      $stmt = $pdo->query('SELECT @intout as INTRETURN');
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      $intout = $result['INTRETURN'];
      $addonhours =0;
      if($paymentstatus=="success")
      {

      $addon = DB::table('pricing_by_car_models')
      ->select('pricing_by_car_models.*')
      ->where('pricing_by_car_models.is_Add_on','=','yes')
      ->where('pricing_by_car_models.status','=','yes')
      ->where('pricing_by_car_models.PricingID','=',$addon_id)
      ->first();
        if(!empty($addon))
        {
          $addonhours = $addon->hours;
        }

      }
      

    }
   
    if(!empty($intout)){

      $this->SendAddonAppNotification($bookingid,$addon_id);
      $this->SendUpgradeRideEmailNotifiaction($bookingid,$addon_id);

      $response['success']= 'Success';
      $response['error']= null;
      $response['message']= 'Successfully fetch data';
      $response['data'] = $addonhours;
      
    }else{
      $response['success']= 'Failed';
      $response['error']= 'Failed To Fetch Data';
    }
    return response()->json($response);
    
  }

  public function SendAddonAppNotification($ride_id,$addon_id)
    {

        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $sql = DB::table('tj_requete')
        ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
        ->Join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
        ->select('tj_requete.id','tj_requete.id_user_app', 'tj_requete.depart_name',
            'tj_requete.destination_name', 
            'tj_requete.ride_required_on_date','tj_requete.ride_required_on_time',
            'tj_requete.bookfor_others_mobileno','tj_requete.bookfor_others_name',
            'tj_requete.vehicle_Id','tj_requete.id_conducteur',
            'tj_conducteur.prenom as driverfirstname','tj_conducteur.nom as driverlastnae','tj_user_app.fcm_id','tj_conducteur.fcm_id as driverfcmid')
            ->where('tj_requete.id', '=', $ride_id)
            ->get();

            foreach ($sql as $row) {
                $iduserapp = $row->id_user_app;
                $drivername = $row->driverfirstname .' '. $row->driverlastnae;
                $pickup_Location = $row->depart_name;
                $drop_Location = $row->destination_name;
                $pickupdate = date("d", strtotime($row->ride_required_on_date)) . " " . $months[date("F", strtotime($row->ride_required_on_date))] . ", " . date("Y", strtotime($row->ride_required_on_date)); 
                $pickuptime = date("h:m A", strtotime($row->ride_required_on_time));
                $tokens = $row->fcm_id;
                $drivertokens = $row->driverfcmid;
            }

            $addontrans = DB::table('tj_transaction')
            ->Join('pricing_by_car_models','tj_transaction.addon_id','=','pricing_by_car_models.PricingID')
            ->select('tj_transaction.amount', 'tj_transaction.payment_status','tj_transaction.payment_method',DB::raw('CONCAT(CAST(pricing_by_car_models.hours AS CHAR), " hours | ", CAST(pricing_by_car_models.kms AS CHAR), " KMs") as add_on_label'))
            ->where('tj_transaction.is_addon', '=', 'yes')
            ->where('tj_transaction.ride_id', '=', $ride_id)
            ->where('tj_transaction.id_user_app', '=', $iduserapp)
            ->where('tj_transaction.addon_id','=',$addon_id)
            ->orderby('tj_transaction.id','DESC')
            ->limit(1)
            ->first();
         
            $addonamount= $addontrans->amount;
            $addonname = $addontrans->add_on_label;
            $paymentmethod='';
            $paymentstatus ='';
            if($addontrans->payment_method=="5")
            {
              $paymentmethod = "Cash on Dropping";
            }
            else{
              $paymentmethod = "Online";
            }

            if($addontrans->payment_status=="yes")
            {
              $paymentstatus= "Successful";
            }
            else{
              $paymentstatus= "failed";
            }
            

            $tmsg = '';
            $terrormsg = '';

            $title = "Addon ".$paymentstatus;
            
            $msg = str_replace("{AddonAmount}", $addonamount, "Payment of {AddonAmount} for an Addon {AddoName} in mode of {paymentmethod}  got {paymentstatus}.");
            $msg = str_replace("{AddoName}", $addonname, $msg);
            $msg = str_replace("{paymentmethod}", $paymentmethod, $msg);
            $msg = str_replace("{paymentstatus}", $paymentstatus, $msg);
            $msg = str_replace("'", "\'", $msg);
        
            $tab[] = array();
            $tab = explode("\\", $msg);
            $msg_ = "";
            for ($i = 0; $i < count($tab); $i++) {
                $msg_ = $msg_ . "" . $tab[$i];
            }

            $data = [
                'ride_id' => $ride_id
            ];

            $message1 = [
                'title' => $title,
                'body' => $msg_,
                'sound'=> 'mySound',
                'tag' => 'addontried'
            ];

            $notifications= new NotificationsController();
            $response['Response'] = $notifications->sendNotification($tokens, $message1,$data);
            if($addontrans->payment_status=="yes")
            {
              $response['driverResponse'] = $notifications->sendNotification($drivertokens, $message1,$data);
            }

            return response()->json($response);
    }

    public function SendUpgradeRideEmailNotifiaction($ride_id,$addon_id)
    {
        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $sql = DB::table('tj_requete')
            ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
            ->Join('car_model', 'car_model.id', '=', 'tj_requete.model_id')
            ->Join('brands', 'brands.id', '=', 'tj_requete.brand_id')
            ->Join('tj_payment_method', 'tj_payment_method.id', '=', 'tj_requete.id_payment_method')
            ->Join('bookingtypes', 'tj_requete.booking_type_id', '=', 'bookingtypes.id')
            ->select(
                'tj_requete.id',
                'tj_requete.id_user_app',
                'tj_requete.depart_name',
                'tj_requete.distance_unit',
                'tj_requete.destination_name',
                'tj_requete.latitude_depart',
                'tj_requete.longitude_depart',
                'tj_requete.latitude_arrivee',
                'tj_requete.longitude_arrivee',
                'tj_requete.statut',
                'tj_requete.id_conducteur',
                'tj_requete.creer',
                'tj_requete.tax_amount',
                'tj_requete.discount',
                'tj_user_app.nom',
                'tj_user_app.prenom',
                'tj_requete.otp',
                'tj_user_app.email as customeremail',
                'tj_user_app.phone as customerphone',
                'tj_requete.distance',
                'tj_user_app.phone',
                'tj_requete.date_retour',
                'tj_requete.heure_retour',
                'tj_requete.montant',
                'tj_requete.duree',
                'tj_requete.statut_paiement',
                'tj_requete.car_Price',
                'tj_requete.sub_total',
                'tj_requete.ride_required_on_date',
                'tj_requete.ride_required_on_time',
                'tj_requete.bookfor_others_mobileno',
                'tj_requete.bookfor_others_name',
                'tj_requete.vehicle_Id',
                'tj_requete.id_conducteur',
                'car_model.name as carmodel',
                'brands.name as brandname',
                'tj_payment_method.libelle as payment',
                'tj_payment_method.image as payment_image',
                'tj_requete.id_payment_method as paymentmethodid',
                'bookingtypes.bookingtype as bookingtype'
            )
            ->where('tj_requete.id', '=', $ride_id)
            ->get();

            

        foreach ($sql as $row) {

            $emailsubject = '';
            $emailmessage = '';
            
            $currency = DB::table('tj_currency')->select('*')->where('statut', 'yes')->first();
            $customer_name = $row->prenom.' '.$row->nom;
            $customerphone = $row->customerphone;
            $customeremail = $row->customeremail;
            $carmodelandbrand = $row->brandname . ' / ' . $row->carmodel;
            $pickup_Location = $row->depart_name;
            $drop_Location = $row->destination_name;
            $booking_date = date("d", strtotime($row->creer)) . " " . $months[date("F", strtotime($row->creer))] . ", " . date("Y", strtotime($row->creer));
            $booking_time = date("h:i A", strtotime(Carbon::parse($row->creer)->timezone('Asia/Kolkata')));
            $payment_method = $row->payment;
            $bookingtype = $row->bookingtype;
            $iduserapp = $row->id_user_app;
            
            $pickupdate = date("d", strtotime($row->ride_required_on_date)) . " " . $months[date("F", strtotime($row->ride_required_on_date))] . ", " . date("Y", strtotime($row->ride_required_on_date));
            $pickuptime = date("h:i A", strtotime($row->ride_required_on_time));
            
            $addontrans = DB::table('tj_transaction')
            ->Join('pricing_by_car_models','tj_transaction.addon_id','=','pricing_by_car_models.PricingID')
            ->select('tj_transaction.amount', 'tj_transaction.payment_status','tj_transaction.payment_method',DB::raw('CONCAT(CAST(pricing_by_car_models.hours AS CHAR), " hours | ", CAST(pricing_by_car_models.kms AS CHAR), " KMs") as add_on_label'))
            ->where('tj_transaction.is_addon', '=', 'yes')
            ->where('tj_transaction.ride_id', '=', $ride_id)
            ->where('tj_transaction.id_user_app', '=', $iduserapp)
            ->where('tj_transaction.addon_id','=',$addon_id)
            ->orderby('tj_transaction.id','DESC')
            ->limit(1)
            ->first();
         
            $addonamount= $addontrans->amount;
            $addonname = $addontrans->add_on_label;
            $paymentmethod='';
            $paymentstatus ='';
            if($addontrans->payment_method=="5")
            {
              $paymentmethod = "Cash on Dropping";
            }
            else{
              $paymentmethod = "Online";
            }

            if($addontrans->payment_status=="yes")
            {
              $paymentstatus= "Successful";
            }
            else{
              $paymentstatus= "failed";
            }
            
            //$response['EmailResponseSql'] = $emailmessage;
            $notifications = new NotificationsController();
            
            // admin email
            $urlstring = env('ADMIN_BASEURL', 'https://nadmin.nxgnapp.com/') . "/ride/show/" . $ride_id;
            $emailsubject = '';
            $emailmessage = '';

            $emailsubject = "Booking upgrade Notification";
            $emailmessage = file_get_contents(resource_path('views/emailtemplates/upgrade.html'));

            $emailmessage = str_replace("{CustomerName}", $customer_name, $emailmessage);
            //$emailmessage = str_replace("{CustomerNumber}", $customerphone, $emailmessage);
            $emailmessage = str_replace("{NewTripDate}", $pickupdate, $emailmessage);
            $emailmessage = str_replace("{NewTriptime}", $pickuptime, $emailmessage);
            $emailmessage = str_replace("{BookingType}", $bookingtype, $emailmessage);
            $emailmessage = str_replace("{BookingID}", $ride_id, $emailmessage);
            $emailmessage = str_replace("{AddoName}", $addonname, $emailmessage);
            $emailmessage = str_replace("{paymentmethod}", $paymentmethod, $emailmessage);
            $emailmessage = str_replace("{paymentstatus}", $paymentstatus, $emailmessage);

            // $emailmessage = str_replace("{PickupLocation}", $pickup_Location, $emailmessage);
            // $emailmessage = str_replace("{DropoffLocation}", $drop_Location, $emailmessage);
            

            $admintoemail = env('ADMIN_EMAILID', 'info@nooritravels.com');

            $response['AdminEmailResponse'] = $notifications->sendEmail($admintoemail, $emailsubject, $emailmessage);
        }

        return response()->json($response);
    }

}
