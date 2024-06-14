<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->has('search') && $request->search != '' && $request->selected_search == 'title') {

            $search = $request->input('search');
            $notifications = DB::table('tj_notification')
                ->where('tj_notification.titre', 'LIKE', '%' . $search . '%')
                ->where('tj_notification.deleted_at', '=', NULL)
                ->paginate(20);
        } elseif ($request->has('search') && $request->search != '' && $request->selected_search == 'message') {

            $search = $request->input('search');
            $notifications = DB::table('tj_notification')
                ->where('tj_notification.message', 'LIKE', '%' . $search . '%')
                ->where('tj_notification.deleted_at', '=', NULL)
                ->paginate(20);
        } else {

            $notifications = DB::table('tj_notification')
                ->where('tj_notification.deleted_at', '=', NULL)
                ->paginate(20);

        }

        return view("notification.index")->with("notifications", $notifications);
    }

    public function show(Request $request, $id)
    {

        $notifications = Notification::where('id', $id)->first();
        return view("notification.show")->with("notifications", $notifications);
    }

    public function delete($id)
    {

        if ($id != "") {

            $id = json_decode($id);

            if (is_array($id)) {

                for ($i = 0; $i < count($id); $i++) {
                    $user = Notification::find($id[$i]);
                    $user->delete();
                }

            } else {
                $user = Notification::find($id);
                $user->delete();
            }

        }

        return redirect()->back();
    }

}


