<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\VehicleType;

class BrandController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

	public function index( Request $request)
    {

     if ($request->has('search') && $request->search != '' && $request->selected_search=='name') {

        $search = $request->input('search');

        $brands = DB::table('brands')
        ->where('brands.name','LIKE','%'.$search.'%')
        ->paginate(20);
      }
      else{




      $brands = DB::table('brands')
      ->paginate(20);

      }
      $vehicleType = VehicleType::all();
       return view("brand.index")->with("brands",$brands);
    }
    public function createCurrency(){
      $vehicleType = VehicleType::all();
      return view("brand.create");
    }

  public function edit(Request $request, $id)
  {
    $brand= Brand::find($id);
    return view("brand.edit")->with("brand",$brand);
  }

  public function deleteBrand($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $brand = Brand::find($id[$i]);
                    $brand->delete();
                }

            } else {
                $brand = Brand::find($id);
                $brand->delete();
            }

        }

        return redirect()->back();
    }

  public function show($id)
    {
      $brand = DB::table('brands')
      ->where('id',$id)->first();

    	return view('brand.show')->with('brand',$brand);
    }
    public function update($id,Request $request)
    {
        $validator = Validator::make($request->all() ,$rules = [
          'name' => 'required',
        ], $messages = [
          'name.required' => 'The Name field is required!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with(['message' => $messages])->withInput();
        }

        $name = $request->input('name');

        $status = $request->input('status') ? 'yes' : 'no';


       $brand =Brand::find($id);

      if($brand){
        $brand->name = $name;
        $brand->status = $status;
        $brand->modifier = date('Y-m-d H:i:s');
        $brand->save();
      }

      return redirect('brands');
    }

    public function store(Request $request)
    {

      $validator = Validator::make($request->all() ,$rules = [
          'name' => 'required',
        ],  $messages = [
          'name.required' => 'The Name field is required!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)->with(['message' => $messages])
            ->withInput();
        }

        $name = $request->input('name');
        $status = $request->input('status') ? 'yes' : 'no';

        $brans = new Brand;

       if($brans){
         $brans->name = $name;

         $brans->status = $status;
         $brans->modifier = date('Y-m-d H:i:s');

         $brans->save();
       }

      return redirect('brands');
    }
    
    public function changeStatus($id)
    {
          $currencies=Currency::find($id);
          if($currencies->statut == 'no') {
              $currencies->statut = 'yes';
          }
          else{
            $currencies->statut = 'no';
          }

          $currencies->save();
          return redirect()->back();

    }

    public function toggalSwitch(Request $request){
        $ischeck=$request->input('ischeck');
        $id=$request->input('id');
        $brand = Brand::find($id);

        if($ischeck=="true"){
          $brand->status = 'yes';
        }else{
          $brand->status = 'no';
        }
          $brand->save();
    }

}
