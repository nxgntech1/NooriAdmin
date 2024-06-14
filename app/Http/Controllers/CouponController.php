<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CouponController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $today = Carbon::now();
        if ($request->has('search') && $request->search != '' && $request->selected_search == 'code') {
            $search = $request->input('search');
            $discounts = DB::table('tj_discount')
                ->where('tj_discount.code', 'LIKE', '%' . $search . '%')
                ->orderBy('tj_discount.expire_at','desc')
                ->paginate(20);
        } else if ($request->has('search') && $request->search != '' && $request->selected_search == 'discount') {
            $search = $request->input('search');
            $discounts = DB::table('tj_discount')
                ->where('tj_discount.discount', 'LIKE', '%' . $search . '%')
                ->orderBy('tj_discount.expire_at', 'desc')
                ->paginate(20);
        } else {
            $discounts =  DB::table('tj_discount')
            ->orderBy('tj_discount.expire_at', 'desc')
            ->paginate(20);
        }
        return view("coupons.index")->with('discounts', $discounts);
    }

    public function edit($id)
    {
        $discount = Discount::where('id', "=", $id)->first();
        return view('coupons.edit')->with('discount', $discount);
    }

    public function updateDiscount(Request $request, $id)
    {

        $validator = Validator::make($request->all(), $rules = [
            'code' => 'required',
            'discount' => 'required',
            'type' => 'required',
            'expire_at' => 'required|date',
            'discription'=>'required',
            'coupon_type' => 'required',

        ], $messages = [
            'code.required' => 'The Code field is required!',
            'discount.required' => 'The Discount field is required!',
            'type.required' => 'The Discount Type is required!',
            'expire_at.required' => 'The Expire date field is required!',
            'discription.required' => 'The Description field is required',
            'coupon_type.required' => 'The Coupon Type is required!',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $code = $request->input('code');
        $discount = $request->input('discount');
        $type = $request->input('type');
        $expire_at = $request->input('expire_at');
        $description = $request->input('discription');
        $coupon_type = $request->input('coupon_type');

        $statut = $request->input('statut');
        $date = date('Y-m-d H:i:s');
        if ($statut == "on") {
            $statut = "yes";
        } else {
            $statut = "no";
        }

        $discounts = Discount::find($id);

        if ($discounts) {
            $discounts->code = $code;
            $discounts->discount = $discount;
            $discounts->type = $type;
            $discounts->expire_at = $expire_at;
            $discounts->discription = $description;
            $discounts->coupon_type = $coupon_type;

            $discounts->statut = $statut;
            $discounts->modifier = $date;
            $discounts->save();
        }


        return redirect('coupons');
    }

    public function create()
    {
        return view('coupons.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $rules = [
            'code' => 'required',
            'discount' => 'required',
            'type' => 'required',
            'expire_at' => 'required|date',
            'discription'=>'required',
            'coupon_type' => 'required',

        ], $messages = [
            'code.required' => 'The Code field is required!',
            'discount.required' => 'The Discount field is required!',
            'type.required' => 'The Discount Type is required!',
            'expire_at.required' => 'The Expire date field is required!',
            'discription.required' => 'The Description field is required',
            'coupon_type.required' => 'The Coupon Type is required!',



        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }

        $code = $request->input('code');
        $discount = $request->input('discount');
        $type = $request->input('type');
        $expire_at = $request->input('expire_at');
        $description = $request->input('discription');
        $coupon_type = $request->input('coupon_type');


        $statut = $request->input('statut');
        $date = date('Y-m-d H:i:s');
        if ($statut == "on") {
            $statut = "yes";
        } else {
            $statut = "no";
        }

        $discounts = new Discount;

        if ($discounts) {
            $discounts->code = $code;
            $discounts->discount = $discount;
            $discounts->type = $type;
            $discounts->expire_at = $expire_at;
            $discounts->discription = $description;
            $discounts->coupon_type = $coupon_type;

            $discounts->statut = $statut;
            $discounts->creer = $date;
            $discounts->modifier = $date;
            $discounts->save();
        }


        return redirect('coupons');
    }

    public function show($id)
    {
        $discount = Discount::where('id', "=", $id)->first();
        return view('coupons.show')->with('discount', $discount);
    }

    public function changeStatus($id)
    {
        $discount = Discount::find($id);
        if ($discount->statut == 'no') {
            $discount->statut = 'yes';
        } else {
            $discount->statut = 'no';
        }

        $discount->save();
        return redirect()->back();

    }

    public function delete($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $user = Discount::find($id[$i]);
                    $user->delete();
                }

            } else {
                $user = Discount::find($id);
                $user->delete();
            }

        }

        return redirect()->back();
    }
    public function toggalSwitch(Request $request){
        $ischeck=$request->input('ischeck');
        $id=$request->input('id');
        $discount = Discount::find($id);

        if($ischeck=="true"){
          $discount->statut = 'yes';
        }else{
          $discount->statut = 'no';
        }
          $discount->save();

}
}
