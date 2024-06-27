<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth::routes();

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/getlang', [App\Http\Controllers\languageController::class, 'getLangauage'])->name('language.header');

// Route::middleware(['auth', 'role:admin'])->group(function(){
        Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
        Route::get('/userdashboard', [App\Http\Controllers\UserProfileController::class, 'userdashboard'])->name('userdashboard');
        Route::get('/updateDriverStatus/{id}', [App\Http\Controllers\HomeController::class, 'updateDriverStatus'])->name('updatestatus');
        Route::get('home/sales_overview', [App\Http\Controllers\HomeController::class, 'getSalesOverview']);
        
        Route::get('lang/change', [App\Http\Controllers\languageController::class, 'change'])->name('changeLang');
        Route::post('/gecode/{slugid}', [App\Http\Controllers\languageController::class, 'getCode'])->name('lang.code');
        Route::get('language', [App\Http\Controllers\languageController::class, 'index'])->name('language');
        Route::get('/language/create', [App\Http\Controllers\languageController::class, 'create'])->name('language.create');
        Route::post('/language/storeuser', [App\Http\Controllers\languageController::class, 'storeuser'])->name('language.storeuser');
        Route::get('/language/edit/{id}', [App\Http\Controllers\languageController::class, 'edit'])->name('language.edit');
        Route::put('language/update/{id}', [App\Http\Controllers\languageController::class, 'userUpdate'])->name('language.update');
        Route::get('/language/delete/{id}', [App\Http\Controllers\languageController::class, 'deleteUser'])->name('language.delete');
        Route::post('language/switch', [App\Http\Controllers\languageController::class, 'toggalSwitch'])->name('language.switch');;
        
        
        Route::post('payments/getpaytmchecksum', [App\Http\Controllers\PaymentController::class, 'getPaytmChecksum']);
        Route::post('payments/validatechecksum', [App\Http\Controllers\PaymentController::class, 'validateChecksum']);
        Route::post('payments/initiatepaytmpayment', [App\Http\Controllers\PaymentController::class, 'initiatePaytmPayment']);
        Route::get('payments/paytmpaymentcallback', [App\Http\Controllers\PaymentController::class, 'paytmPaymentcallback']);
        Route::post('payments/paypalclientid', [App\Http\Controllers\PaymentController::class, 'getPaypalClienttoken']);
        Route::post('payments/paypaltransaction', [App\Http\Controllers\PaymentController::class, 'createBraintreePayment']);
        Route::post('payments/stripepaymentintent', [App\Http\Controllers\PaymentController::class, 'createStripePaymentIntent']);
        
        Route::post('payments/razorpay/createorder', [App\Http\Controllers\RazorPayController::class, 'createOrderid']);
        
        Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
        Route::get('/users/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::get('/user/delete/{id}', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('user.delete');
        Route::put('user/update/{id}', [App\Http\Controllers\UserController::class, 'userUpdate'])->name('user.update');
        Route::get('/users/profile', [App\Http\Controllers\UserProfileController::class, 'profile'])->name('users.profile');
        Route::post('/users/profile/update/{id}', [App\Http\Controllers\UserProfileController::class, 'update'])->name('users.profile.update');
        Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('/users/storeuser', [App\Http\Controllers\UserController::class, 'storeuser'])->name('users.storeuser');
        Route::get('/users/show/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
        Route::post('/users/add-wallet/{id}', [App\Http\Controllers\UserController::class, 'addWallet'])->name('user.wallet');
        Route::get('/users/changeStatus/{id}', [App\Http\Controllers\UserController::class, 'changeStatus'])->name('users.changeStatus');
        Route::post('/switch', [App\Http\Controllers\UserController::class, 'toggalSwitch']);
        
        Route::get('/users_category', [App\Http\Controllers\UserCategoryController::class, 'index'])->name('users_category');
        Route::get('/users_category/delete/{id}', [App\Http\Controllers\UserCategoryController::class, 'delete'])->name('userscategory.delete');
        Route::put('users_category/update/{id}', [App\Http\Controllers\UserCategoryController::class, 'update'])->name('userscategory.update');
        Route::post('/userscategory/store', [App\Http\Controllers\UserCategoryController::class, 'store'])->name('userscategory.store');
        
        Route::get('webuser', [App\Http\Controllers\WebUserController::class, 'index'])->name('webuser');
        Route::get('webuser/edit/{id}', [App\Http\Controllers\WebUserController::class, 'edit'])->name('webuser.edit');
        Route::get('webuser/delete/{id}', [App\Http\Controllers\WebUserController::class, 'deleteUser'])->name('webuser.delete');
        Route::put('webuser/update/{id}', [App\Http\Controllers\WebUserController::class, 'userUpdate'])->name('webuser.update');
        Route::get('webuser/create', [App\Http\Controllers\WebUserController::class, 'create'])->name('webuser.create');
        Route::post('webuser/storeuser', [App\Http\Controllers\WebUserController::class, 'storeuser'])->name('webuser.storeuser');


        Route::get('/drivers', [App\Http\Controllers\DriverController::class, 'index'])->name('drivers');
        Route::get('/drivers/approved', [App\Http\Controllers\DriverController::class, 'approvedDrivers'])->name('drivers.approved');
        Route::get('/drivers/pending', [App\Http\Controllers\DriverController::class, 'pendingDrivers'])->name('drivers.pending');
        Route::get('/drivers/edit/{id}', [App\Http\Controllers\DriverController::class, 'edit'])->name('drivers.edit');
        Route::get('/drivers/documentstatus/{id}/{type}', [App\Http\Controllers\DriverController::class, 'statusAproval'])->name('drivers.documentstatus');
        Route::get('/drivers/create', [App\Http\Controllers\DriverController::class, 'create'])->name('drivers.create');
        Route::post('/drivers/store', [App\Http\Controllers\DriverController::class, 'store'])->name('drivers.store');
        Route::get('/driver/delete/{id}', [App\Http\Controllers\DriverController::class, 'deleteDriver'])->name('driver.delete');
        Route::put('driver/update/{id}', [App\Http\Controllers\DriverController::class, 'updateDriver'])->name('driver.update');
        Route::get('/driver/show/{id}', [App\Http\Controllers\DriverController::class, 'show'])->name('driver.show');
        Route::post('/driver/add-wallet/{id}', [App\Http\Controllers\DriverController::class, 'addWallet'])->name('driver.wallet');
        Route::get('/driver/changeStatus/{id}', [App\Http\Controllers\DriverController::class, 'changeStatus'])->name('driver.changeStatus');
        Route::get('/driver/changeStatus/{id}', [App\Http\Controllers\DriverController::class, 'changeStatus'])->name('driver.changeStatus');
        Route::get('/driver/document/view/{id}', [App\Http\Controllers\DriverController::class, 'documentView'])->name('driver.documentView');
        Route::get('/driver/uploaddocument/{id}/{doc_id}', [App\Http\Controllers\DriverController::class, 'uploaddocument'])->name('driver.uploaddocument');
        Route::get('/driver/upload/document/{id}', [App\Http\Controllers\DriverController::class, 'uploaddocument'])->name('driver.upload_document');
        Route::put('/driver/updatedocument/{id}', [App\Http\Controllers\DriverController::class, 'updatedocument'])->name('driver.updatedocument');
        Route::post('/driver/model/{brandId}', [App\Http\Controllers\DriverController::class, 'getModel'])->name('driver.model');
        Route::post('/driver/brand/{vehicleType_id}', [App\Http\Controllers\DriverController::class, 'getBrand'])->name('driver.brand');
        Route::get('/driver/download', [App\Http\Controllers\DriverController::class, 'download'])->name('driver.download');
        Route::get('status-update/{id}', [App\Http\Controllers\DriverController::class, 'statusupdate'])->name('status-update');
        Route::post('driver/switch', [App\Http\Controllers\DriverController::class, 'toggalSwitch']);
        
        Route::get('cms', [App\Http\Controllers\CmsController::class, 'index'])->name('cms');
        Route::get('/cms/edit/{id}', [App\Http\Controllers\CmsController::class, 'edit'])->name('cms.edit');
        Route::put('cms/updateCms/{id}', [App\Http\Controllers\CmsController::class, 'updateCms'])->name('cms.updateCms');
        Route::get('/cms/create', [App\Http\Controllers\CmsController::class, 'create'])->name('cms.create');
        Route::post('/cms/store', [App\Http\Controllers\CmsController::class, 'store'])->name('cms.store');
        Route::get('/cms/destroycms/{id}', [App\Http\Controllers\CmsController::class, 'destroycms'])->name('cms.destroycms');
        Route::get('/cms/changeStatus/{id}', [App\Http\Controllers\CmsController::class, 'changeStatus'])->name('cms.changeStatus');
        Route::post('cms/switch', [App\Http\Controllers\CmsController::class, 'toggalSwitch']);
        
        /*Route::get('/notification', [App\Http\Controllers\NotificationController::class, 'index'])->name('notification');
        Route::get('/notification/delete/{id}', [App\Http\Controllers\NotificationController::class, 'delete'])->name('notification.delete');
        Route::get('/notification/show/{id}', [App\Http\Controllers\NotificationController::class, 'show'])->name('notification.show');*/
        
        Route::get('/notification', [App\Http\Controllers\AdminNotificationController::class, 'index'])->name('notifications');
        Route::get('/notification/create', [App\Http\Controllers\AdminNotificationController::class, 'create'])->name('notifications.create');
        Route::post('/notification/send', [App\Http\Controllers\AdminNotificationController::class, 'send'])->name('notifications.send');
        Route::get('/notification/delete/{id}', [App\Http\Controllers\AdminNotificationController::class, 'delete'])->name('notifications.delete');
        
        
        Route::get('/rides/all/{id?}', [App\Http\Controllers\RidesController::class, 'all'])->name('rides.all');
        Route::get('/rides/new', [App\Http\Controllers\RidesController::class, 'new'])->name('rides.new');
        Route::get('/rides/confirmed', [App\Http\Controllers\RidesController::class, 'confirmed'])->name('rides.confirmed');
        Route::get('/rides/onRide', [App\Http\Controllers\RidesController::class, 'onRide'])->name('rides.onRide');
        Route::get('/rides/rejected', [App\Http\Controllers\RidesController::class, 'rejected'])->name('rides.rejected');
        Route::get('/rides/completed', [App\Http\Controllers\RidesController::class, 'completed'])->name('rides.completed');
        Route::get('/ride/delete/{rideid}', [App\Http\Controllers\RidesController::class, 'deleteRide'])->name('ride.delete');
        Route::get('/ride/show/{id}', [App\Http\Controllers\RidesController::class, 'show'])->name('ride.show');
        Route::get('/rides/filter', [App\Http\Controllers\RidesController::class, 'filterRides'])->name('rides.filter');
        Route::put('/rides/update/{id}', [App\Http\Controllers\RidesController::class, 'updateRide'])->name('rides.update');
        Route::get('/reviews/{id}', [App\Http\Controllers\RidesController::class, 'index'])->name('restaurants.reviews');
        
        // parcle orders routes
        Route::get('/parcel/all/{id?}', [App\Http\Controllers\ParcelOrdersController::class, 'all'])->name('parcel.all');
        Route::get('/parcel/delete/{rideid}', [App\Http\Controllers\ParcelOrdersController::class, 'deleteRide'])->name('parcel.delete');
        Route::get('/parcel/show/{id}', [App\Http\Controllers\ParcelOrdersController::class, 'show'])->name('parcel.show');
        Route::put('/parcel/update/{id}', [App\Http\Controllers\ParcelOrdersController::class, 'updateRide'])->name('parcel.update');
        Route::get('/parcel/confirmed', [App\Http\Controllers\ParcelOrdersController::class, 'confirmed'])->name('parcel.confirmed');
        Route::get('/parcel/rejected', [App\Http\Controllers\ParcelOrdersController::class, 'rejected'])->name('parcel.rejected');
        Route::get('/parcel/completed', [App\Http\Controllers\ParcelOrdersController::class, 'completed'])->name('parcel.completed');
        
        Route::get('/vehicle/index', [App\Http\Controllers\VehicleController::class, 'vehicleType'])->name('vehicle-type');
        Route::get('/vehicle/creates', [App\Http\Controllers\VehicleController::class, 'creates'])->name('vehicle.creates');
        Route::post('/vehicle/store', [App\Http\Controllers\VehicleController::class, 'store'])->name('vehicle-type.store');
        Route::post('vehicle/switch', [App\Http\Controllers\VehicleController::class, 'toggalSwitch']);
        Route::get('/vehicle/edits/{id}', [App\Http\Controllers\VehicleController::class, 'vehicleTypeEdit'])->name('vehicle.edits');
        Route::put('/vehicle-type/update/{id}', [App\Http\Controllers\VehicleController::class, 'vehicleTypeUpdate'])->name('vehicle-type.update');
        Route::get('/vehicle-type/delete/{id}', [App\Http\Controllers\VehicleController::class, 'deleteVehicle'])->name('vehicle-type.delete');
        Route::post('vehicle-type/switch', [App\Http\Controllers\VehicleController::class, 'vehicleTypeSwitch']);
        
        Route::get('/vehicle/vehicle', [App\Http\Controllers\VehicleController::class, 'vehicleList'])->name('vehicle');
        Route::post('/vehicle/vehicle/create', [App\Http\Controllers\VehicleController::class, 'create'])->name('vehicle.create');
        Route::get('/vehicle/vehicle/edit/{id}', [App\Http\Controllers\VehicleController::class, 'edit'])->name('vehicle.edit');
        Route::put('/vehicle/vehicle/update/{id}', [App\Http\Controllers\VehicleController::class, 'update'])->name('vehicle.update');
        Route::get('/vehicle/vehicle/delete/{id}', [App\Http\Controllers\VehicleController::class, 'delete'])->name('vehicle.delete');
        Route::get('/vehicle/vehicle_create', [App\Http\Controllers\VehicleController::class, 'vehiclecreates'])->name('vehicle.vehicle_create');
        Route::get('/vehicle/vehicle_edit/{id}', [App\Http\Controllers\VehicleController::class, 'edit'])->name('vehicle.vehicle_edit');
        
        Route::get('/vehicle/vehicle-rent', [App\Http\Controllers\VehicleRentalController::class, 'vehicleRent'])->name('vehicle-rent');
        Route::get('/vehicle/vehicle-rent/delete/{id}', [App\Http\Controllers\VehicleRentalController::class, 'delete'])->name('vehicle-rent.delete');
        Route::get('/vehicle/vehicle-rent/show/{id}', [App\Http\Controllers\VehicleRentalController::class, 'show'])->name('vehicle-rent.show');
        Route::get('/vehicle/vehicle-rent/ChangeStatus/{id}', [App\Http\Controllers\VehicleRentalController::class, 'ChangeStatus'])->name('vehicleRental.ChangeStatus');
        
        Route::get('/vehicle-rental-type/index', [App\Http\Controllers\VehicleTypeRentalController::class, 'index'])->name('vehicle-rental-type');
        Route::get('/vehicle-rental-type/create', [App\Http\Controllers\VehicleTypeRentalController::class, 'create'])->name('vehicle-rental-type.create');
        Route::post('/vehicle-rental-type/store', [App\Http\Controllers\VehicleTypeRentalController::class, 'store'])->name('vehicle-rental-type.store');
        Route::get('/vehicle-rental-type/edits/{id}', [App\Http\Controllers\VehicleTypeRentalController::class, 'edit'])->name('vehicle-rental-type.edit');
        Route::put('/vehicle-rental-type/update/{id}', [App\Http\Controllers\VehicleTypeRentalController::class, 'update'])->name('vehicle-rental-type.update');
        Route::get('/vehicle-rental-type/delete/{id}', [App\Http\Controllers\VehicleTypeRentalController::class, 'delete'])->name('vehicle-rental-type.delete');
        Route::post('rental_vehicle_type/switch', [App\Http\Controllers\VehicleTypeRentalController::class, 'toggalSwitch']);
        
        Route::get('/reports/userreport', [App\Http\Controllers\ReportController::class, 'userreport'])->name('userreport');
        Route::get('/reports/downloadExcel', [App\Http\Controllers\ReportController::class, 'downloadExcel'])->name('userreport.downloadExcel');
        Route::get('/reports/driverreport', [App\Http\Controllers\ReportController::class, 'driverreport'])->name('driverreport');
        Route::get('/reports/downloadExcelDriver', [App\Http\Controllers\ReportController::class, 'downloadExcelDriver'])->name('driverreport.downloadExcelDriver');
        Route::get('/reports/travelreport', [App\Http\Controllers\ReportController::class, 'travelreport'])->name('travelreport');
        Route::get('/reports/downloadExcelTravel', [App\Http\Controllers\ReportController::class, 'downloadExcelTravel'])->name('travelreport.downloadExcelTravel');
        
        Route::get('/coupons', [App\Http\Controllers\CouponController::class, 'index'])->name('coupons');
        Route::get('/coupons/edit/{id}', [App\Http\Controllers\CouponController::class, 'edit'])->name('coupons.edit');
        Route::get('/coupons/create', [App\Http\Controllers\CouponController::class, 'create'])->name('coupons.create');
        Route::put('/coupons/update/{id}', [App\Http\Controllers\CouponController::class, 'updateDiscount'])->name('coupons.update');
        Route::post('/coupons/store', [App\Http\Controllers\CouponController::class, 'store'])->name('coupons.store');
        Route::get('/coupons/show/{id}', [App\Http\Controllers\CouponController::class, 'show'])->name('coupons.show');
        Route::get('/coupons/delete/{id}', [App\Http\Controllers\CouponController::class, 'delete'])->name('coupons.delete');
        Route::get('/coupons/changeStatus/{id}', [App\Http\Controllers\CouponController::class, 'changeStatus'])->name('coupons.changeStatus');
        Route::post('coupon/switch', [App\Http\Controllers\CouponController::class, 'toggalSwitch']);
        Route::get('/coupon/{id}', [App\Http\Controllers\CouponController::class, 'index'])->name('restaurants.coupons');
        Route::get('/coupon/create/{id}', [App\Http\Controllers\CouponController::class, 'create']);
        
        Route::get('driversPayouts/create', [App\Http\Controllers\DriversPayoutController::class, 'create'])->name('driversPayouts.create');
        Route::post('driversPayouts/store', [App\Http\Controllers\DriversPayoutController::class, 'store'])->name('driversPayouts.store');
        Route::get('driversPayouts', [App\Http\Controllers\DriversPayoutController::class, 'index'])->name('driversPayouts');
        
        Route::get('walletstransaction', [App\Http\Controllers\TransactionController::class, 'index'])->name('walletstransaction');
        Route::get('/walletstransaction/{id}', [App\Http\Controllers\TransactionController::class, 'index'])->name('users.walletstransaction');
        
        Route::get('walletstransactions/driver/{id?}', [App\Http\Controllers\TransactionController::class, 'driverWallet'])->name('walletstransactions.driver');
        Route::get('complaints', [App\Http\Controllers\ComplaintsController::class, 'index'])->name('complaints');
        Route::get('complaints/delete/{id}', [App\Http\Controllers\ComplaintsController::class, 'deleteComplaints'])->name('complaints.delete');
        Route::get('complaints/show/{id}', [App\Http\Controllers\ComplaintsController::class, 'show'])->name('complaints.show');
        Route::post('complaints/update', [App\Http\Controllers\ComplaintsController::class, 'update'])->name('complaints.update');

        Route::get('sos', [App\Http\Controllers\SosController::class, 'index'])->name('sos');
        Route::get('/sos/show/{id}', [App\Http\Controllers\SosController::class, 'show'])->name('sos.show');
        Route::get('/sos/delete/{id}', [App\Http\Controllers\SosController::class, 'deleteSos'])->name('sos.delete');
        Route::put('/sos/update/{id}', [App\Http\Controllers\SosController::class, 'sosUpdate'])->name('sos.update');

        Route::get('/car_model', [App\Http\Controllers\CarModelController::class, 'index'])->name('car_model');
        Route::get('/car_model/create', [App\Http\Controllers\CarModelController::class, 'create'])->name('car_model.create');
        Route::get('/car_model/edit/{id}', [App\Http\Controllers\CarModelController::class, 'edit'])->name('car_model.edit');
        Route::get('/car_model/delete/{id}', [App\Http\Controllers\CarModelController::class, 'deleteCarModel'])->name('car_model.delete');
        Route::put('car_model/update/{id}', [App\Http\Controllers\CarModelController::class, 'UpdateCarModel'])->name('car_model.update');
        Route::post('/car_model/storecarmodel', [App\Http\Controllers\CarModelController::class, 'storecarmodel'])->name('car_model.storecarmodel');
        Route::post('carModel/switch', [App\Http\Controllers\CarModelController::class, 'toggalSwitch']);

        Route::get('brands', [App\Http\Controllers\BrandController::class, 'index'])->name('brand');
        Route::get('brands/create', [App\Http\Controllers\BrandController::class, 'createCurrency'])->name('brand.create');
        Route::post('brands/create', [App\Http\Controllers\BrandController::class, 'store'])->name('brand.store');
        Route::get('brands/edit/{id}', [App\Http\Controllers\BrandController::class, 'edit'])->name('brand.edit');
        Route::put('brands/update/{id}', [App\Http\Controllers\BrandController::class, 'update'])->name('brand.update');
        Route::get('brands/delete/{id}', [App\Http\Controllers\BrandController::class, 'deleteBrand'])->name('brand.delete');
        Route::get('brands/show/{id}', [App\Http\Controllers\BrandController::class, 'show'])->name('brand.show');
        Route::post('brand/switch', [App\Http\Controllers\BrandController::class, 'toggalSwitch']);

        Route::post('currency/switch', [App\Http\Controllers\CurrencyController::class, 'toggalSwitch'])->name('currency.switch');
        Route::post('country/switch', [App\Http\Controllers\CountryController::class, 'toggalSwitch']);
        Route::post('commission/switch', [App\Http\Controllers\CommissionController::class, 'toggalSwitch']);
        Route::post('tax/switch', [App\Http\Controllers\TaxController::class, 'toggalSwitch']);
        Route::post('driver_document/switch', [App\Http\Controllers\DriverDocumentController::class, 'toggalSwitch'])->name('driver_document.switch');

        Route::get('/payoutRequest', [App\Http\Controllers\PayoutRequestController::class, 'payout'])->name('payoutRequests');
        Route::get('/payoutRequest/{id}', [App\Http\Controllers\PayoutRequestController::class, 'payout'])->name('payoutRequests.view');
        Route::post('driver/getbankdetails', [App\Http\Controllers\PayoutRequestController::class, 'getBankDetails']);
        Route::post('withdrawal/accept', [App\Http\Controllers\PayoutRequestController::class, 'acceptWithdrawal']);
        Route::post('withdrawal/reject', [App\Http\Controllers\PayoutRequestController::class, 'rejectWithdrawal']);
        Route::get('/get-settings', [App\Http\Controllers\SettingsController::class, 'getSettings'])->name('get-settings');

        Route::get('/dispatcher-users/create', [App\Http\Controllers\DispatcherController::class, 'createUser'])->name('dispatcher-users.create');
        Route::get('/dispatcher-users', [App\Http\Controllers\DispatcherController::class, 'index'])->name('dispatcher-users');
        Route::post('/dispatcher-users/storeuser', [App\Http\Controllers\DispatcherController::class, 'storeUser'])->name('dispatcher-users.store');
        Route::get('/dispatcher-users/edit/{id}', [App\Http\Controllers\DispatcherController::class, 'editUser'])->name('dispatcher-users.edit');
        Route::get('/dispatcher-users/delete/{id}', [App\Http\Controllers\DispatcherController::class, 'deleteUser'])->name('dispatcher-users.delete');
        Route::put('dispatcher-users/update/{id}', [App\Http\Controllers\DispatcherController::class, 'userUpdate'])->name('dispatcher-users.update');Route::post('/switch', [App\Http\Controllers\UserController::class, 'toggalSwitch']);
        Route::post('/dispatcher-users-switch', [App\Http\Controllers\DispatcherController::class, 'toggalSwitch']);
        Route::get('/dispatcher-users/show/{id}', [App\Http\Controllers\DispatcherController::class, 'userShow'])->name('dispatcher-users.show');
        Route::get('/dispatcher-users/changestatus/{id}', [App\Http\Controllers\DispatcherController::class, 'userChangeStatus'])->name('dispatcher-users.changestatus');

        Route::get('/map', [App\Http\Controllers\MapController::class, 'index'])->name('map');
        Route::post('/map/get_ride_info', [App\Http\Controllers\MapController::class, 'getRideInfo'])->name('map.getrideinfo');
        Route::get('parcel/map', [App\Http\Controllers\ParcelMapController::class, 'index'])->name('parcel.map');
        Route::post('parcel/map/get_ride_info', [App\Http\Controllers\ParcelMapController::class, 'getRideInfo'])->name('parcel.map.getrideinfo');

        Route::get('/parcel-category/create', [App\Http\Controllers\ParcelCategoryController::class, 'create'])->name('parcel-category.create');
        Route::get('/parcel-category', [App\Http\Controllers\ParcelCategoryController::class, 'index'])->name('parcel-category');
        Route::post('/parcel-category/store', [App\Http\Controllers\ParcelCategoryController::class, 'store'])->name('parcel-category.store');
        Route::get('/parcel-category/edit/{id}', [App\Http\Controllers\ParcelCategoryController::class, 'edit'])->name('parcel-category.edit');
        Route::get('/parcel-category/delete/{id}', [App\Http\Controllers\ParcelCategoryController::class, 'delete'])->name('parcel-category.delete');
        Route::put('parcel-category/update/{id}', [App\Http\Controllers\ParcelCategoryController::class, 'update'])->name('parcel-category.update');
        Route::post('/parcel-category-switch', [App\Http\Controllers\ParcelCategoryController::class, 'toggalSwitch']);
        Route::get('/parcel-category/changestatus/{id}', [App\Http\Controllers\ParcelCategoryController::class, 'changeStatus'])->name('parcel-category.changestatus');

        Route::get('zone', [App\Http\Controllers\ZoneController::class, 'index'])->name('zone');
        Route::get('zone/create', [App\Http\Controllers\ZoneController::class, 'create'])->name('zone.create');
        Route::post('zone/store', [App\Http\Controllers\ZoneController::class, 'store'])->name('zone.store');
        Route::get('zone/edit/{id}', [App\Http\Controllers\ZoneController::class, 'edit'])->name('zone.edit');
        Route::put('zone/update/{id}', [App\Http\Controllers\ZoneController::class, 'update'])->name('zone.update');
        Route::get('zone/delete/{id}', [App\Http\Controllers\ZoneController::class, 'delete'])->name('zone.delete');
        Route::post('zone/switch', [App\Http\Controllers\ZoneController::class, 'toggalSwitch'])->name('zone.switch');

        Route::get('bookingtypes', [App\Http\Controllers\BookingTypeController::class, 'index'])->name('bookingtypes');
        Route::get('bookingtypes/create', [App\Http\Controllers\BookingTypeController::class, 'create'])->name('bookingtypes.create');
        Route::post('bookingtypes/storebookingtypemodel', [App\Http\Controllers\BookingTypeController::class, 'storebookingtypemodel'])->name('bookingtypes.storebookingtypemodel');
        Route::get('bookingtypes/edit/{id}', [App\Http\Controllers\BookingTypeController::class, 'edit'])->name('bookingtypes.edit');
        Route::get('bookingtypes/delete/{id}', [App\Http\Controllers\BookingTypeController::class, 'deleteBookingType'])->name('bookingtypes.delete');
        Route::put('bookingtypes/update/{id}', [App\Http\Controllers\BookingTypeController::class, 'UpdateBookingType'])->name('bookingtypes.update');
        Route::post('bookingtypes/switch', [App\Http\Controllers\BookingTypeController::class, 'toggalSwitch']);

        Route::get('cmpricing', [App\Http\Controllers\CarModelPricingController::class, 'index'])->name('cmpricing');
        Route::get('cmpricing/create', [App\Http\Controllers\CarModelPricingController::class, 'create'])->name('cmpricing.create');
        Route::post('cmpricing/storecarmodelprice', [App\Http\Controllers\CarModelPricingController::class, 'storecarmodelprice'])->name('cmpricing.storecarmodelprice');
        Route::get('cmpricing/edit/{id}', [App\Http\Controllers\CarModelPricingController::class, 'edit'])->name('cmpricing.edit');
        Route::get('cmpricing/delete/{id}', [App\Http\Controllers\CarModelPricingController::class, 'deleteCarModelPrice'])->name('cmpricing.delete');
        Route::put('cmpricing/update/{id}', [App\Http\Controllers\CarModelPricingController::class, 'UpdateCarModelPrice'])->name('cmpricing.update');
        Route::post('cmpricing/switch', [App\Http\Controllers\CarModelPricingController::class, 'toggalSwitch']);
        Route::post('cmpricing/filter', [App\Http\Controllers\CarModelPricingController::class, 'filter'])->name('cmpricing.filter');

        Route::get('vehicles', [App\Http\Controllers\VehiclesController::class, 'index'])->name('vehicles');
        Route::get('vehicles/create', [App\Http\Controllers\VehiclesController::class, 'create'])->name('vehicles.create');
        Route::post('vehicles/store', [App\Http\Controllers\VehiclesController::class, 'store'])->name('vehicles.store');
        Route::post('vehicles/switch', [App\Http\Controllers\VehiclesController::class, 'toggalSwitch']);
        Route::get('vehicles/edit/{id}', [App\Http\Controllers\VehiclesController::class, 'edit'])->name('vehicles.edit');
        Route::get('vehicles/delete/{id}', [App\Http\Controllers\VehiclesController::class, 'deletevehicle'])->name('vehicles.delete');
        Route::put('vehicles/update/{id}', [App\Http\Controllers\VehiclesController::class, 'UpdateVehicle'])->name('vehicles.update');
        Route::post('vehicles/deleteimage/{id}', [App\Http\Controllers\VehiclesController::class, 'deletevehicleImage'])->name('vehicles.deleteimage');

        Route::get('addon', [App\Http\Controllers\AddOnController::class, 'index'])->name('addon');
        Route::get('addon/create', [App\Http\Controllers\AddOnController::class, 'create'])->name('addon.create');
        Route::post('addon/storecarmodelprice', [App\Http\Controllers\AddOnController::class, 'storecarmodelprice'])->name('addon.storecarmodelprice');
        Route::get('addon/edit/{id}', [App\Http\Controllers\AddOnController::class, 'edit'])->name('addon.edit');
        Route::get('addon/delete/{id}', [App\Http\Controllers\AddOnController::class, 'deleteCarModelPrice'])->name('addon.delete');
        Route::put('addon/update/{id}', [App\Http\Controllers\AddOnController::class, 'UpdateCarModelPrice'])->name('addon.update');
        Route::post('addon/switch', [App\Http\Controllers\AddOnController::class, 'toggalSwitch']);
        Route::post('addon/filter', [App\Http\Controllers\AddOnController::class, 'filter'])->name('addon.filter');

        

        

        Route::get('/payment_method', [App\Http\Controllers\PaymentMethodController::class, 'index'])->name('payment_method');
        Route::get('/payment_method/show/{id}', [App\Http\Controllers\PaymentMethodController::class, 'show'])->name('payment_method.show');
        Route::get('/payment_method/changeStatus/{id}', [App\Http\Controllers\PaymentMethodController::class, 'changeStatus'])->name('payment_method.changeStatus');

        Route::get('/commission', [App\Http\Controllers\CommissionController::class, 'index'])->name('commission');
        Route::get('/commission/edit/{id}', [App\Http\Controllers\CommissionController::class, 'edit'])->name('commission.edit');
        Route::put('/commission/update/{id}', [App\Http\Controllers\CommissionController::class, 'update'])->name('commission.update');
        Route::get('/commission/show/{id}', [App\Http\Controllers\CommissionController::class, 'show'])->name('commission.show');
        Route::get('/commission/changeStatus/{id}', [App\Http\Controllers\CommissionController::class, 'changeStatus'])->name('commission.changeStatus');
        Route::get('/commission/search', [App\Http\Controllers\CommissionController::class, 'searchCommision'])->name('commision.search');

        Route::get('/tax', [App\Http\Controllers\TaxController::class, 'index'])->name('tax');
        Route::get('/tax/create', [App\Http\Controllers\TaxController::class, 'create'])->name('tax.create');
        Route::post('/tax/store', [App\Http\Controllers\TaxController::class, 'store'])->name('tax.store');
        Route::get('/tax/edit/{id}', [App\Http\Controllers\TaxController::class, 'edit'])->name('tax.edit');
        Route::put('/tax/update/{id}', [App\Http\Controllers\TaxController::class, 'update'])->name('tax.update');
        Route::get('/tax/delete/{id}', [App\Http\Controllers\TaxController::class, 'delete'])->name('tax.delete');
        Route::get('/tax/show/{id}', [App\Http\Controllers\TaxController::class, 'show'])->name('tax.show');
        Route::get('/tax/changeStatus/{id}', [App\Http\Controllers\TaxController::class, 'changeStatus'])->name('tax.changeStatus');
        Route::get('/tax/search', [App\Http\Controllers\TaxController::class, 'searchTax'])->name('tax.search');

        Route::get('cash_collection',[App\Http\Controllers\CashCollectionController:: class, 'index'])->name('cash_collection');
        Route::get('cash_collection/detail/{id}',[App\Http\Controllers\CashCollectionController:: class, 'detail'])->name('cash_collection.detail');
        Route::post('cash_collection/collect', [App\Http\Controllers\CashCollectionController::class, 'collect'])->name('cash_collection.collect');
        Route::get('cash_collection/collected',[App\Http\Controllers\CashCollectionController:: class, 'collected'])->name('cash_collection.collected');
        Route::get('cash_collection/coldetail/{id}/transaction/{transactionid}',[App\Http\Controllers\CashCollectionController:: class, 'coldetail'])->name('cash_collection.coldetail');
        

        Route::get('/homepageTemplate', [App\Http\Controllers\LandingPageTempController::class, 'index'])->name('homepageTemplate');
        Route::post('/homepageTemplate/save', [App\Http\Controllers\LandingPageTempController::class, 'save'])->name('homepageTemplate.save');

        

        

        

        

    Route::prefix('administration_tools')->group(function (){
        Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
        Route::get('/settings/edit/{id}', [App\Http\Controllers\SettingsController::class, 'edit'])->name('settings.edit');
        Route::post('/settings/update/{id}', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
        Route::get('/driver_document', [App\Http\Controllers\DriverDocumentController::class, 'index'])->name('driver_document');
        Route::get('/driver_document/create', [App\Http\Controllers\DriverDocumentController::class, 'create'])->name('driver_document.create');
        Route::post('/driver_document/store', [App\Http\Controllers\DriverDocumentController::class, 'storeDocument'])->name('driver_document.store');
        Route::get('/driver_document/edit/{id}', [App\Http\Controllers\DriverDocumentController::class, 'edit'])->name('driver_document.edit');
        Route::put('/driver_document/update/{id}', [App\Http\Controllers\DriverDocumentController::class, 'documentUpdate'])->name('driver_document.update');
        Route::get('/driver_document/delete/{id}', [App\Http\Controllers\DriverDocumentController::class, 'deleteDocument'])->name('driver_document.delete');
	Route::get('/country', [App\Http\Controllers\CountryController::class, 'index'])->name('country');
        Route::post('/country/store', [App\Http\Controllers\CountryController::class, 'store'])->name('country.store');
        Route::get('/country/show/{id}', [App\Http\Controllers\CountryController::class, 'show'])->name('country.show');
        Route::put('/country/update/{id}', [App\Http\Controllers\CountryController::class, 'update'])->name('country.update');
        Route::get('/country/changeStatus/{id}', [App\Http\Controllers\CountryController::class, 'changeStatus'])->name('country.changeStatus');
        Route::get('/country/create', [App\Http\Controllers\CountryController::class, 'create'])->name('country.create');
        Route::get('/country/edit/{id}', [App\Http\Controllers\CountryController::class, 'editCountry'])->name('country.edit');
	Route::get('/currency', [App\Http\Controllers\CurrencyController::class, 'index'])->name('currency');
        Route::get('/currency/create', [App\Http\Controllers\CurrencyController::class, 'createCurrency'])->name('currency.create');
        Route::get('/currency/edit/{id}', [App\Http\Controllers\CurrencyController::class, 'edit'])->name('currency.edit');
        Route::get('/currency/show/{id}', [App\Http\Controllers\CurrencyController::class, 'show'])->name('currency.show');
        Route::put('/currency/update/{id}', [App\Http\Controllers\CurrencyController::class, 'update'])->name('currency.update');
        Route::post('/currency/store', [App\Http\Controllers\CurrencyController::class, 'store'])->name('currency.store');
        Route::get('/currency/changeStatus/{id}', [App\Http\Controllers\CurrencyController::class, 'changeStatus'])->name('currency.changeStatus');
        Route::get('/currency/delete/{id}', [App\Http\Controllers\CurrencyController::class, 'delete'])->name('currency.delete');
        Route::get('/currency/change/{id}', [App\Http\Controllers\CurrencyController::class, 'currencyEdit'])->name('edit_currency');
	Route::get('/terms_condition', [App\Http\Controllers\TermsAndConditionsController::class, 'index'])->name('terms_condition');
        Route::put('/terms_condition/update/{id}', [App\Http\Controllers\TermsAndConditionsController::class, 'update'])->name('terms_condition.update');
        Route::get('/privacy_policy', [App\Http\Controllers\TermsAndConditionsController::class, 'indexPrivacy'])->name('privacy_policy');
        Route::put('/privacy_policy/update/{id}', [App\Http\Controllers\TermsAndConditionsController::class, 'updatePrivacy'])->name('privacy_policy.update');

        Route::get('email_template', [App\Http\Controllers\EmailTemplateController::class, 'index'])->name('email_template.index');
        Route::get('email_template/edit/{id}', [App\Http\Controllers\EmailTemplateController::class, 'edit'])->name('email_template.edit');
        Route::put('email_template/update/{id}', [App\Http\Controllers\EmailTemplateController::class, 'update'])->name('email_template.update');

    });
    Route::prefix('settings')->group(function(){
        Route::get('app/globals', [App\Http\Controllers\SettingsController::class, 'globals'])->name('settings.app.globals');
        Route::get('app/social', [App\Http\Controllers\SettingsController::class, 'social'])->name('settings.app.social');
        Route::get('app/adminCommission', [App\Http\Controllers\SettingsController::class, 'adminCommission'])->name('settings.app.adminCommission');
        Route::get('app/radiosConfiguration', [App\Http\Controllers\SettingsController::class, 'radiosConfiguration'])->name('settings.app.radiosConfiguration');
        Route::get('app/notifications', [App\Http\Controllers\SettingsController::class, 'notifications'])->name('settings.app.notifications');

        Route::get('payment/stripe', [App\Http\Controllers\SettingsController::class, 'stripe'])->name('payment.stripe');
        Route::put('payment/stripeUpdate/{id}', [App\Http\Controllers\SettingsController::class, 'stripeUpdate'])->name('payment.stripeUpdate');
        Route::get('payment/applepay', [App\Http\Controllers\SettingsController::class, 'applepay'])->name('payment.applepay');
        Route::put('payment/applepayUpdate/{id}', [App\Http\Controllers\SettingsController::class, 'applepayUpdate'])->name('payment.applepayUpdate');
        Route::get('payment/razorpay', [App\Http\Controllers\SettingsController::class, 'razorpay'])->name('payment.razorpay');
        Route::put('payment/razorpayUpdate/{id}', [App\Http\Controllers\SettingsController::class, 'razorpayUpdate'])->name('payment.razorpayUpdate');
        Route::get('payment/cod', [App\Http\Controllers\SettingsController::class, 'cod'])->name('payment.cod');
        Route::put('payment/codUpdate/{id}', [App\Http\Controllers\SettingsController::class, 'codUpdate'])->name('payment.codUpdate');
        Route::get('payment/paypal', [App\Http\Controllers\SettingsController::class, 'paypal'])->name('payment.paypal');
        Route::put('payment/paypalUpdate/{id}', [App\Http\Controllers\SettingsController::class, 'paypalUpdate'])->name('payment.paypalUpdate');
        Route::get('payment/paytm', [App\Http\Controllers\SettingsController::class, 'paytm'])->name('payment.paytm');
        Route::put('payment/paytmUpdate/{id}', [App\Http\Controllers\SettingsController::class, 'paytmUpdate'])->name('payment.paytmUpdate');
        Route::get('payment/wallet', [App\Http\Controllers\SettingsController::class, 'wallet'])->name('payment.wallet');
        Route::put('payment/walletUpdate/{id}', [App\Http\Controllers\SettingsController::class, 'walletUpdate'])->name('payment.walletUpdate');
        Route::get('payment/payfast', [App\Http\Controllers\SettingsController::class, 'payfast'])->name('payment.payfast');
        Route::put('payment/payfastUpdate/{id}', [App\Http\Controllers\SettingsController::class, 'payfastUpdate'])->name('payment.payfastUpdate');
        Route::get('payment/paystack', [App\Http\Controllers\SettingsController::class, 'paystack'])->name('payment.paystack');
        Route::put('payment/paystackUpdate/{id}', [App\Http\Controllers\SettingsController::class, 'paystackUpdate'])->name('payment.paystackUpdate');
        Route::get('payment/flutterwave', [App\Http\Controllers\SettingsController::class, 'flutterwave'])->name('payment.flutterwave');
        Route::put('payment/flutterUpdate/{id}', [App\Http\Controllers\SettingsController::class, 'flutterUpdate'])->name('payment.flutterUpdate');
        Route::get('payment/mercadopago', [App\Http\Controllers\SettingsController::class, 'mercadopago'])->name('payment.mercadopago');
        Route::put('payment/mercadopago/{id}', [App\Http\Controllers\SettingsController::class, 'mercadopagoUpdate'])->name('payment.mercadopagoUpdate');
        Route::get('brand', [App\Http\Controllers\SettingsController::class, 'brand'])->name('settings.brand');
        Route::get('brand/create', [App\Http\Controllers\SettingsController::class, 'brandCreate'])->name('settings.brand.create');
        Route::get('brand/edit/{id}', [App\Http\Controllers\SettingsController::class, 'brandEdit'])->name('settings.brand.edit');
        Route::get('carModel', [App\Http\Controllers\SettingsController::class, 'carModel'])->name('settings.carModel');
        Route::get('carModel/create', [App\Http\Controllers\SettingsController::class, 'carModelCreate'])->name('settings.carModel.create');
        Route::get('carModel/edit/{id}', [App\Http\Controllers\SettingsController::class, 'carModelEdit'])->name('settings.carModel.edit');
    });
// });

// Route::middleware(['auth', 'role:user'])->group(function () {
//         Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'userdashboard'])->name('dashboard');

//         Route::get('/users/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('users.profile');
//         Route::post('/users/profile/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.profile.update');
        
//         Route::get('lang/change', [App\Http\Controllers\languageController::class, 'change'])->name('changeLang');
//         Route::post('/gecode/{slugid}', [App\Http\Controllers\languageController::class, 'getCode'])->name('lang.code');
//         Route::get('language', [App\Http\Controllers\languageController::class, 'index'])->name('language');

//         Route::get('/drivers', [App\Http\Controllers\DriverController::class, 'index'])->name('drivers');
//         Route::get('/drivers/approved', [App\Http\Controllers\DriverController::class, 'approvedDrivers'])->name('drivers.approved');
//         Route::get('/drivers/pending', [App\Http\Controllers\DriverController::class, 'pendingDrivers'])->name('drivers.pending');
//         Route::get('/drivers/edit/{id}', [App\Http\Controllers\DriverController::class, 'edit'])->name('drivers.edit');
//         Route::get('/drivers/documentstatus/{id}/{type}', [App\Http\Controllers\DriverController::class, 'statusAproval'])->name('drivers.documentstatus');
//         Route::get('/drivers/create', [App\Http\Controllers\DriverController::class, 'create'])->name('drivers.create');
//         Route::post('/drivers/store', [App\Http\Controllers\DriverController::class, 'store'])->name('drivers.store');
//         Route::get('/driver/delete/{id}', [App\Http\Controllers\DriverController::class, 'deleteDriver'])->name('driver.delete');
//         Route::put('driver/update/{id}', [App\Http\Controllers\DriverController::class, 'updateDriver'])->name('driver.update');
//         Route::get('/driver/show/{id}', [App\Http\Controllers\DriverController::class, 'show'])->name('driver.show');
//         Route::post('/driver/add-wallet/{id}', [App\Http\Controllers\DriverController::class, 'addWallet'])->name('driver.wallet');
//         Route::get('/driver/changeStatus/{id}', [App\Http\Controllers\DriverController::class, 'changeStatus'])->name('driver.changeStatus');
//         Route::get('/driver/changeStatus/{id}', [App\Http\Controllers\DriverController::class, 'changeStatus'])->name('driver.changeStatus');
//         Route::get('/driver/document/view/{id}', [App\Http\Controllers\DriverController::class, 'documentView'])->name('driver.documentView');
//         Route::get('/driver/uploaddocument/{id}/{doc_id}', [App\Http\Controllers\DriverController::class, 'uploaddocument'])->name('driver.uploaddocument');
//         Route::get('/driver/upload/document/{id}', [App\Http\Controllers\DriverController::class, 'uploaddocument'])->name('driver.upload_document');
//         Route::put('/driver/updatedocument/{id}', [App\Http\Controllers\DriverController::class, 'updatedocument'])->name('driver.updatedocument');
//         Route::post('/driver/model/{brandId}', [App\Http\Controllers\DriverController::class, 'getModel'])->name('driver.model');
//         Route::post('/driver/brand/{vehicleType_id}', [App\Http\Controllers\DriverController::class, 'getBrand'])->name('driver.brand');
//         Route::get('/driver/download', [App\Http\Controllers\DriverController::class, 'download'])->name('driver.download');
//         Route::get('status-update/{id}', [App\Http\Controllers\DriverController::class, 'statusupdate'])->name('status-update');
//         Route::post('driver/switch', [App\Http\Controllers\DriverController::class, 'toggalSwitch']);

//         Route::get('/rides/all/{id?}', [App\Http\Controllers\RidesController::class, 'all'])->name('rides.all');
//         Route::get('/rides/new', [App\Http\Controllers\RidesController::class, 'new'])->name('rides.new');
//         Route::get('/rides/confirmed', [App\Http\Controllers\RidesController::class, 'confirmed'])->name('rides.confirmed');
//         Route::get('/rides/onRide', [App\Http\Controllers\RidesController::class, 'onRide'])->name('rides.onRide');
//         Route::get('/rides/rejected', [App\Http\Controllers\RidesController::class, 'rejected'])->name('rides.rejected');
//         Route::get('/rides/completed', [App\Http\Controllers\RidesController::class, 'completed'])->name('rides.completed');
//         Route::get('/ride/delete/{rideid}', [App\Http\Controllers\RidesController::class, 'deleteRide'])->name('ride.delete');
//         Route::get('/ride/show/{id}', [App\Http\Controllers\RidesController::class, 'show'])->name('ride.show');
//         Route::get('/rides/filter', [App\Http\Controllers\RidesController::class, 'filterRides'])->name('rides.filter');
//         Route::put('/rides/update/{id}', [App\Http\Controllers\RidesController::class, 'updateRide'])->name('rides.update');
//         Route::get('/reviews/{id}', [App\Http\Controllers\RidesController::class, 'index'])->name('restaurants.reviews');

//         Route::get('/map', [App\Http\Controllers\MapController::class, 'index'])->name('map');
//         Route::post('/map/get_ride_info', [App\Http\Controllers\MapController::class, 'getRideInfo'])->name('map.getrideinfo');
//         Route::get('parcel/map', [App\Http\Controllers\ParcelMapController::class, 'index'])->name('parcel.map');
//         Route::post('parcel/map/get_ride_info', [App\Http\Controllers\ParcelMapController::class, 'getRideInfo'])->name('parcel.map.getrideinfo');

//         Route::get('complaints', [App\Http\Controllers\ComplaintsController::class, 'index'])->name('complaints');
//         Route::get('complaints/delete/{id}', [App\Http\Controllers\ComplaintsController::class, 'deleteComplaints'])->name('complaints.delete');
//         Route::get('complaints/show/{id}', [App\Http\Controllers\ComplaintsController::class, 'show'])->name('complaints.show');
//         Route::post('complaints/update', [App\Http\Controllers\ComplaintsController::class, 'update'])->name('complaints.update');

//         Route::get('sos', [App\Http\Controllers\SosController::class, 'index'])->name('sos');
//         Route::get('/sos/show/{id}', [App\Http\Controllers\SosController::class, 'show'])->name('sos.show');
//         Route::get('/sos/delete/{id}', [App\Http\Controllers\SosController::class, 'deleteSos'])->name('sos.delete');
//         Route::put('/sos/update/{id}', [App\Http\Controllers\SosController::class, 'sosUpdate'])->name('sos.update');

//         Route::get('/notification', [App\Http\Controllers\AdminNotificationController::class, 'index'])->name('notifications');
//         Route::get('/notification/create', [App\Http\Controllers\AdminNotificationController::class, 'create'])->name('notifications.create');
//         Route::post('/notification/send', [App\Http\Controllers\AdminNotificationController::class, 'send'])->name('notifications.send');
//         Route::get('/notification/delete/{id}', [App\Http\Controllers\AdminNotificationController::class, 'delete'])->name('notifications.delete');

//         Route::get('/vehicle/index', [App\Http\Controllers\VehicleController::class, 'vehicleType'])->name('vehicle-type');
//         Route::get('/vehicle/creates', [App\Http\Controllers\VehicleController::class, 'creates'])->name('vehicle.creates');
//         Route::post('/vehicle/store', [App\Http\Controllers\VehicleController::class, 'store'])->name('vehicle-type.store');
//         Route::post('vehicle/switch', [App\Http\Controllers\VehicleController::class, 'toggalSwitch']);
//         Route::get('/vehicle/edits/{id}', [App\Http\Controllers\VehicleController::class, 'vehicleTypeEdit'])->name('vehicle.edits');
//         Route::put('/vehicle-type/update/{id}', [App\Http\Controllers\VehicleController::class, 'vehicleTypeUpdate'])->name('vehicle-type.update');
//         Route::get('/vehicle-type/delete/{id}', [App\Http\Controllers\VehicleController::class, 'deleteVehicle'])->name('vehicle-type.delete');
//         Route::post('vehicle-type/switch', [App\Http\Controllers\VehicleController::class, 'vehicleTypeSwitch']);
        
//         Route::get('/vehicle/vehicle', [App\Http\Controllers\VehicleController::class, 'vehicleList'])->name('vehicle');
//         Route::post('/vehicle/vehicle/create', [App\Http\Controllers\VehicleController::class, 'create'])->name('vehicle.create');
//         Route::get('/vehicle/vehicle/edit/{id}', [App\Http\Controllers\VehicleController::class, 'edit'])->name('vehicle.edit');
//         Route::put('/vehicle/vehicle/update/{id}', [App\Http\Controllers\VehicleController::class, 'update'])->name('vehicle.update');
//         Route::get('/vehicle/vehicle/delete/{id}', [App\Http\Controllers\VehicleController::class, 'delete'])->name('vehicle.delete');
//         Route::get('/vehicle/vehicle_create', [App\Http\Controllers\VehicleController::class, 'vehiclecreates'])->name('vehicle.vehicle_create');
//         Route::get('/vehicle/vehicle_edit/{id}', [App\Http\Controllers\VehicleController::class, 'edit'])->name('vehicle.vehicle_edit');

//         Route::get('brands', [App\Http\Controllers\BrandController::class, 'index'])->name('brand');
//         Route::get('brands/create', [App\Http\Controllers\BrandController::class, 'createCurrency'])->name('brand.create');
//         Route::post('brands/create', [App\Http\Controllers\BrandController::class, 'store'])->name('brand.store');
//         Route::get('brands/edit/{id}', [App\Http\Controllers\BrandController::class, 'edit'])->name('brand.edit');
//         Route::put('brands/update/{id}', [App\Http\Controllers\BrandController::class, 'update'])->name('brand.update');
//         Route::get('brands/delete/{id}', [App\Http\Controllers\BrandController::class, 'deleteBrand'])->name('brand.delete');
//         Route::get('brands/show/{id}', [App\Http\Controllers\BrandController::class, 'show'])->name('brand.show');
//         Route::post('brand/switch', [App\Http\Controllers\BrandController::class, 'toggalSwitch']);

//         Route::get('/car_model', [App\Http\Controllers\CarModelController::class, 'index'])->name('car_model');
//         Route::get('/car_model/create', [App\Http\Controllers\CarModelController::class, 'create'])->name('car_model.create');
//         Route::get('/car_model/edit/{id}', [App\Http\Controllers\CarModelController::class, 'edit'])->name('car_model.edit');
//         Route::get('/car_model/delete/{id}', [App\Http\Controllers\CarModelController::class, 'deleteCarModel'])->name('car_model.delete');
//         Route::put('car_model/update/{id}', [App\Http\Controllers\CarModelController::class, 'UpdateCarModel'])->name('car_model.update');
//         Route::post('/car_model/storecarmodel', [App\Http\Controllers\CarModelController::class, 'storecarmodel'])->name('car_model.storecarmodel');
//         Route::post('carModel/switch', [App\Http\Controllers\CarModelController::class, 'toggalSwitch']);

//         Route::get('bookingtypes', [App\Http\Controllers\BookingTypeController::class, 'index'])->name('bookingtypes');
//         Route::get('bookingtypes/create', [App\Http\Controllers\BookingTypeController::class, 'create'])->name('bookingtypes.create');
//         Route::post('bookingtypes/storebookingtypemodel', [App\Http\Controllers\BookingTypeController::class, 'storebookingtypemodel'])->name('bookingtypes.storebookingtypemodel');
//         Route::get('bookingtypes/edit/{id}', [App\Http\Controllers\BookingTypeController::class, 'edit'])->name('bookingtypes.edit');
//         Route::get('bookingtypes/delete/{id}', [App\Http\Controllers\BookingTypeController::class, 'deleteBookingType'])->name('bookingtypes.delete');
//         Route::put('bookingtypes/update/{id}', [App\Http\Controllers\BookingTypeController::class, 'UpdateBookingType'])->name('bookingtypes.update');
//         Route::post('bookingtypes/switch', [App\Http\Controllers\BookingTypeController::class, 'toggalSwitch']);

//         Route::get('cmpricing', [App\Http\Controllers\CarModelPricingController::class, 'index'])->name('cmpricing');
//         Route::get('cmpricing/create', [App\Http\Controllers\CarModelPricingController::class, 'create'])->name('cmpricing.create');
//         Route::post('cmpricing/storecarmodelprice', [App\Http\Controllers\CarModelPricingController::class, 'storecarmodelprice'])->name('cmpricing.storecarmodelprice');
//         Route::get('cmpricing/edit/{id}', [App\Http\Controllers\CarModelPricingController::class, 'edit'])->name('cmpricing.edit');
//         Route::get('cmpricing/delete/{id}', [App\Http\Controllers\CarModelPricingController::class, 'deleteCarModelPrice'])->name('cmpricing.delete');
//         Route::put('cmpricing/update/{id}', [App\Http\Controllers\CarModelPricingController::class, 'UpdateCarModelPrice'])->name('cmpricing.update');
//         Route::post('cmpricing/switch', [App\Http\Controllers\CarModelPricingController::class, 'toggalSwitch']);
//         Route::post('cmpricing/filter', [App\Http\Controllers\CarModelPricingController::class, 'filter'])->name('cmpricing.filter');

//         Route::get('vehicles', [App\Http\Controllers\VehiclesController::class, 'index'])->name('vehicles');
//         Route::get('vehicles/create', [App\Http\Controllers\VehiclesController::class, 'create'])->name('vehicles.create');
//         Route::post('vehicles/store', [App\Http\Controllers\VehiclesController::class, 'store'])->name('vehicles.store');
//         Route::post('vehicles/switch', [App\Http\Controllers\VehiclesController::class, 'toggalSwitch']);
//         Route::get('vehicles/edit/{id}', [App\Http\Controllers\VehiclesController::class, 'edit'])->name('vehicles.edit');
//         Route::get('vehicles/delete/{id}', [App\Http\Controllers\VehiclesController::class, 'deletevehicle'])->name('vehicles.delete');
//         Route::put('vehicles/update/{id}', [App\Http\Controllers\VehiclesController::class, 'UpdateVehicle'])->name('vehicles.update');
//         Route::post('vehicles/deleteimage/{id}', [App\Http\Controllers\VehiclesController::class, 'deletevehicleImage'])->name('vehicles.deleteimage');

//         Route::get('addon', [App\Http\Controllers\AddOnController::class, 'index'])->name('addon');
//         Route::get('addon/create', [App\Http\Controllers\AddOnController::class, 'create'])->name('addon.create');
//         Route::post('addon/storecarmodelprice', [App\Http\Controllers\AddOnController::class, 'storecarmodelprice'])->name('addon.storecarmodelprice');
//         Route::get('addon/edit/{id}', [App\Http\Controllers\AddOnController::class, 'edit'])->name('addon.edit');
//         Route::get('addon/delete/{id}', [App\Http\Controllers\AddOnController::class, 'deleteCarModelPrice'])->name('addon.delete');
//         Route::put('addon/update/{id}', [App\Http\Controllers\AddOnController::class, 'UpdateCarModelPrice'])->name('addon.update');
//         Route::post('addon/switch', [App\Http\Controllers\AddOnController::class, 'toggalSwitch']);
//         Route::post('addon/filter', [App\Http\Controllers\AddOnController::class, 'filter'])->name('addon.filter');

//         Route::get('/reports/driverreport', [App\Http\Controllers\ReportController::class, 'driverreport'])->name('driverreport');
//         Route::get('/reports/downloadExcelDriver', [App\Http\Controllers\ReportController::class, 'downloadExcelDriver'])->name('driverreport.downloadExcelDriver');
//         Route::get('/reports/travelreport', [App\Http\Controllers\ReportController::class, 'travelreport'])->name('travelreport');
//         Route::get('/reports/downloadExcelTravel', [App\Http\Controllers\ReportController::class, 'downloadExcelTravel'])->name('travelreport.downloadExcelTravel');                        

// });