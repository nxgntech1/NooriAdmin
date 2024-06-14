<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pricing_by_car_models;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\bookingtypes;
use App\Models\VehicleType;
use App\Models\Currency;

class CashCollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currency = Currency::where('statut', 'yes')->first();
        return view("cash_collection.index")->with('currency',$currency);
    }

    public function detail(Request $request, $id)
    {
        $currency = Currency::where('statut', 'yes')->first();
        return view('cash_collection.detail')->with('currency',$currency);
    }

    public function collected(Request $request)
    {
        $currency = Currency::where('statut', 'yes')->first();
        return view('cash_collection.collected')->with('currency',$currency);
    }
}
