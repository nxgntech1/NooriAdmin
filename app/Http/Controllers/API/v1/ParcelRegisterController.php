<?php

namespace App\Http\Controllers\api\v1;
use App\Models\ParcelOrder;
use App\Http\Controllers\API\v1\GcmController;
use App\Http\Controllers\Controller;
use App\Models\Requests;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ParcelRegisterController extends Controller
{

    public function __construct()
    {
        $this->limit = 20;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
        $months = array("January" => 'Jan', "February" => 'Fev', "March" => 'Mar', "April" => 'Avr', "May" => 'Mai', "June" => 'Jun', "July" => 'Jul', "August" => 'Aou', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $user_id = $request->get('user_id');
        $lat1 = $request->get('lat1');
        $lng1 = $request->get('lng1');
        $lat2 = $request->get('lat2');
        $lng2 = $request->get('lng2');
        $sourceCity = $request->get('source_city');
        $destinationCity = $request->get('destination_city');
        $distance = $request->get('distance');
        $distance_unit = $request->get('distance_unit');
        $duration = $request->get('duration');
        $id_payment = $request->get('id_payment');
        $source_adrs = $request->get('source_adrs');
        $destination_adrs = $request->get('destination_adrs');
        $sender_name=$request->get('sender_name');
        $receiver_name=$request->get('receiver_name');
        $sender_phone=$request->get('sender_phone');
        $receiver_phone=$request->get('receiver_phone');
        $note=$request->get('note');
        $parcel_weight=$request->get('parcel_weight');
        $parcel_dimension = $request->get('parcel_dimension');
        $image= $request->file('parcel_image');
        $parcel_type = $request->get('parcel_type');
        $filenames = [];
        $filename = '';
        if ($request->hasfile('parcel_image')) {
            for ($i = 0; $i < sizeof($image);$i++) {  
                $extenstion = $image[$i]->getClientOriginalExtension();
                $time = time().'_'.$i. '.' . $extenstion;
                $filename = 'parcel_' . $time;
                $image[$i]->move(public_path('images/parcel_order/'), $filename);
                array_push($filenames, $filename);
            }
            $filename = json_encode($filenames);
        }
        $parcel_date=$request->get('parcel_date');
        $parcel_time=$request->get('parcel_time');
        $receive_date = $request->get('receive_date');
        $receive_time = $request->get('receive_time');

        $amount = $request->get('amount');
        if (!empty($id_payment)) {

            $created_at = date('Y-m-d H:i:s');
            ParcelOrder::create([
                'id_user_app'=>$user_id,
                'source'=>$source_adrs,
                'destination'=>$destination_adrs,
                'lat_source'=>$lat1,
                'lng_source'=>$lng1,
                'lat_destination'=>$lat2,
                'lng_destination'=>$lng2,
                'source_city'=>$sourceCity,
                'destination_city'=>$destinationCity,
                'sender_name'=>$sender_name,
                'sender_phone'=>$sender_phone,
                'receiver_name'=>$receiver_name,
                'receiver_phone'=>$receiver_phone,
                'parcel_weight'=>$parcel_weight,
                'parcel_dimension'=>$parcel_dimension,
                'parcel_type'=>$parcel_type,
                'parcel_image'=>$filename,
                'note'=>$note,
                'parcel_date'=>$parcel_date,
                'parcel_time'=>$parcel_time,
                'receive_date'=>$receive_date,
                'receive_time'=>$receive_time,
                'status'=>'new',
                'payment_status'=>'no',
                'id_payment_method'=>$id_payment,
                'distance'=>$distance,
                'distance_unit'=>$distance_unit,
                'amount'=>$amount,
                'duration'=>$duration
            ]);

            $id = DB::getPdo()->lastInsertId();
            if ($id > 0) {
                $get_user = ParcelOrder::join('tj_payment_method', 'tj_payment_method.id', '=', 'parcel_orders.id_payment_method')
                    ->join('parcel_category', 'parcel_category.id', '=', 'parcel_orders.parcel_type')
                    ->select('parcel_orders.*', 'tj_payment_method.libelle as payment_method', 'parcel_category.title as parcel_type')
                    ->where('parcel_orders.id', $id)->first();
                $row = $get_user->toArray();
                $row['id'] = (string) $row['id'];
                $row['created_at'] = date("d", strtotime($row['created_at'])) . " " . $months[date("F", strtotime($row['created_at']))] . ". " . date("Y", strtotime($row['created_at']));
                $row['updated_at'] = date("d", strtotime($row['updated_at'])) . " " . $months[date("F", strtotime($row['updated_at']))] . ". " . date("Y", strtotime($row['updated_at']));

                if ($row['parcel_image'] != '') {
                    $parcelImage=json_decode($row['parcel_image'],true);
                    $image_user=[];
                    foreach($parcelImage as $value){
                        if (file_exists(public_path('images/parcel_order/' . '/' . $value))) {
                            $image = asset('images/parcel_order/') . '/' . $value;
                        }
                        array_push($image_user,$image);
                    }
                    if (!empty($image_user)) {
                        $row['parcel_image'] = $image_user;
                    } else {
                        $image_user = asset('assets/images/placeholder_image.jpg');
                    }

                }
                $output[] = $row;
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'Successfully created';
                $response['data'] = $output;
            } else {
                $response['success'] = 'Failed';
                $response['error'] = 'Failed';
            }
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'some field required';
        }


        return response()->json($response);
    }


}
