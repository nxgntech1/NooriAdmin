<?php

namespace App\Http\Controllers;
use App\Models\Zone;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;

class ZoneController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index( Request $request)
    {
      if ($request->has('search') && $request->search != '' && $request->selected_search=='name') {
          $search = $request->input('search');
          $zones = Zone::where('zones.name','LIKE','%'.$search.'%')->paginate(20);
        } else{
          $zones = Zone::paginate(20);
        }
        
        return view("zone.index")->with("zones",$zones);
      }

      public function create(){
        
          $settings = Settings::first();
          $lat_long = $this->getDefaultLatLong();
          return view("zone.create")->with("settings", $settings)->with("lat_long", $lat_long);
      }

      public function edit(Request $request, $id)
      {
          $zone= Zone::find($id);
          $settings = Settings::first();
          $lat_long = $this->getDefaultLatLong();

          $area = $zone->area->toArray();
          $coordinates = [];
          foreach($area['coordinates'] as $key => $data){
                foreach($data as $k=>$v){
                    $coordinates[$key][] = array('lat' => $v[1], 'lng' => $v[0]);
                }
          }
          $default_lat = $coordinates[0][0]['lat'];
          $default_lng = $coordinates[0][0]['lng'];

          return view("zone.edit")
            ->with("zone",$zone)
            ->with("settings", $settings)
            ->with("lat_long", $lat_long)
            ->with("coordinates", json_encode($coordinates))
            ->with("default_lat", $default_lat)
            ->with("default_lng", $default_lng);
      }

      public function delete($id)
      {
          if ($id != "") {
              $id = json_decode($id);
              if (is_array($id)) {
                  for ($i = 0; $i < count($id); $i++) {
                      $zone = Zone::find($id[$i]);
                      $zone->delete();
                  }
              } else {
                  $zone = Zone::find($id);
                  $zone->delete();
              }
          }
          return redirect()->back();
    }

    public function update($id,Request $request)
    {
        $validator = Validator::make($request->all() ,$rules = [
          'name' => 'required',
          'coordinates' => 'required',
        ], $messages = [
            'name.required' => 'The Name field is required',
            'coordinates.required' => 'Please select your zone from map',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with(['message' => $messages])->withInput();
        }

        $name = $request->input('name');
        $status = $request->input('status') ? 'yes' : 'no';
        $coordinates = json_decode($request->input('coordinates'));
        
        $points = array();
        foreach ($coordinates[0] as $coordinate) {
            $points[]= new Point($coordinate->lat, $coordinate->lng);
        }
        array_push($points, $points[0]);

        $zone = Zone::find($id);
        $zone->name = $name;
        $zone->status = $status;
        $zone->latitude = $points[0]->latitude;
        $zone->longitude = $points[0]->longitude;
        $zone->area = new Polygon([
            new LineString($points),
        ]);
        $zone->update();
        
        return redirect('zone');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all() ,$rules = [
            'name' => 'required',
            'coordinates' => 'required',
        ], $messages = [
          'name.required' => 'The Name field is required',
          'coordinates.required' => 'Please select your zone from map',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with(['message' => $messages])->withInput();
        }

        $name = $request->input('name');
        $status = $request->input('status') ? 'yes' : 'no';
        $coordinates = json_decode($request->input('coordinates'));
        
        $points = array();
        foreach ($coordinates[0] as $coordinate) {
            $points[]= new Point($coordinate->lat, $coordinate->lng);
        }
        array_push($points, $points[0]);
        
        $zone = Zone::create([
            'name' => $name,
            'status' => $status,
            'latitude' => $points[0]->latitude,
            'longitude' => $points[0]->longitude,
            'area' =>  new Polygon([
                new LineString($points),
            ])
        ]);

        return redirect('zone');
    }
    
    public function toggalSwitch(Request $request){
        $ischeck = $request->input('ischeck');
        $id = $request->input('id');
        $zone = Zone::find($id);
        if($ischeck=="true"){
          $zone->status = 'yes';
        }else{
          $zone->status = 'no';
        }
        $zone->save();
    }

    public function getDefaultLatLong(){

      $sql=DB::table('tj_settings')->select('tj_settings.contact_us_address as address','tj_settings.google_map_api_key as apikey')->first();
      $address=$sql->address;
      $apiKey=$sql->apikey;

      if(!empty($address) && !empty($apiKey)){
      $geo=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&key='.$apiKey);
      $geo = json_decode($geo, true);
      $latlong = array();
          if (isset($geo['status']) && $geo['status'] == 'OK') {
              $latitude = $geo['results'][0]['geometry']['location']['lat'];
              $longitude = $geo['results'][0]['geometry']['location']['lng'];
              $latlong = array('lat'=> $latitude ,'lng'=>$longitude);
          }
      }else{
          $latlong = array();
      }
      return $latlong;
  }

}