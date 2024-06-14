<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserApp;
use App\Models\Driver;
use App\Models\AdminNotification;
use App\Http\Controllers\GcmController;
use Validator;
use Illuminate\Support\Facades\DB;

class AdminNotificationController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');

    }

    public function index(Request $request)
    {

        $query = AdminNotification::select('*');

        $notifications = $query->orderBy('created_at', 'desc')->paginate(10);

       

        if ($request->has('search') && $request->search != '' && $request->selected_search == 'title') {
            $search = $request->input('search');
            
            $notifications = DB::table('admin_notification')
                ->where('admin_notification.title', 'LIKE', '%' . $search . '%')
               
                ->orderBy('admin_notification.created_at','desc')
                ->paginate(10);
        } else if ($request->has('search') && $request->search != '' && $request->selected_search == 'message') {
            $search = $request->input('search');
            $notifications = DB::table('admin_notification')
                ->where('admin_notification.message', 'LIKE', '%' . $search . '%')
               
                ->orderBy('admin_notification.created_at','desc')
                ->paginate(10);
         } else {
            
            $notifications = $query->orderBy('created_at', 'desc')->paginate(10);
        }
        
        return view("admin_notifications.index")->with("notifications", $notifications);


    }
    public function create()
    {
        return view("admin_notifications.send");

    }
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',
            'send_to' => 'required',
        ],
        [
            'send_to.required' => 'Please choose customer or driver or both to send notification.'
        ]);

        if ($validator->fails()) {
            return redirect('notification/create')->withErrors($validator)->withInput();
        }

        $title = $request->input('title');
        $message = $request->input('message');
        $send_to = $request->input('send_to');

        $messages = array("body" => $message, "title" => $title, "sound" => "default", "tag" => "notification");

        //Send notification to registered users
        if (in_array('customer', $send_to)) {

            $users = UserApp::where('fcm_id', '!=', '')->get();

            $tokens = $insert_data = array();
            $temp = array();

            if (count($users) > 0) {
                foreach ($users as $user) {
                    array_push($tokens,$user->fcm_id);
                }
                GcmController::send_notification($tokens, $messages,$temp);
                $msg = 'Notification successfully sent';
            }
        }

        //Send notification to guest users
        if (in_array('driver', $send_to)) {

            $drivers = Driver::where('fcm_id', '!=', '')->get();
            $tokens = $insert_data = array();
            $temp = array();

            if (count($drivers) > 0) {

                foreach ($drivers as $driver) {
                    array_push($tokens, $driver->fcm_id);
                }
                GcmController::send_notification($tokens, $messages,$temp);
                $msg = 'Notification successfully sent';
            }
        }

        AdminNotification::insert(array('title' => $title, 'message' => $message, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')));

        return redirect("notification")->with('message', $msg);

    }

    public function delete($id)
    {
       
       /*$id = json_decode($id);
     
        if ($id != "") {
            $notification = AdminNotification::where('id', $id)->get();
            dd($notification);
            $notification->delete();
        }

        return redirect('notification')->with('message', 'notification successfully deleted');*/
        
        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $user = AdminNotification::find($id[$i]);
                    $user->delete();
                }
                return redirect('notification')->with('message', 'notification successfully deleted');

            } else {
                $user = AdminNotification::find($id);
                $user->delete();
                return redirect('notification')->with('message', 'notification successfully deleted');
            }

        }
    }

}