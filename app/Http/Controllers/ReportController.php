<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserApp;
use App\Models\Driver;
use App\Models\VehicleType;
use App\Models\Requests;
use App\Models\PaymentMethod;
use Codedge\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;

class ReportController extends Controller
{

    public function __construct()
    {
       $this->middleware('auth'); 
    }

  public function userreport(Request $request)
    {
        return view("reports.userreport");
    }    

    public function downloadExcel(Request $request){
    	       
        $finalarr=array();         
        $status = $request->input('user_status');
        
        if($request->input('date') == 'today'){
        	$now = Carbon::now();
            $today =  $now->format('Y-m-d');
        }else{
            $today = '';
        }
		
        if($request->input('date') == 'week'){
            $now = Carbon::now();
            $week_start = $now->startOfWeek()->format('Y-m-d'); 
            $week_end = $now->endOfWeek()->format('Y-m-d'); 
        }else{
            $week_start = '';
            $week_end = '';
        }
		
        if($request->input('date') == 'month'){
            $now = Carbon::now();
            $month =  $now->month;
        }else{
            $month = '';
        }
		
        if($request->input('date') == 'year'){
            $now = Carbon::now();
            $year =  $now->year;
        }else{
            $year ='';
        }
        
        $from_date = $request->input('from');
        $to_date = $request->input('to');
        $type = $request->input('type');
        
    	$users = UserApp::when($status, function ($query) use ($status) {
            return $query->where('statut', $status);})
        ->when($today, function ($query) use ($today) {
            return $query->whereDate('creer', $today);})
         ->when($week_start && $week_end, function ($query, $condition) use($week_start, $week_end) { 
            return $query->whereBetween('creer', [$week_start, $week_end]);
            })
        ->when($month, function ($query) use ($month) {
            return $query->whereMonth('creer', $month);})
        ->when($year, function ($query) use ($year) {
            return $query->whereYear('creer', $year);})
        ->when($from_date && $to_date, function ($query, $condition) use($from_date, $to_date) { 
            return $query->whereBetween('creer', [$from_date, $to_date]);
            })
        ->get();
                
        $fp = fopen('php://output', 'w');

        if($users->count() > 0){
        	
        	$users11 = array("Sr. No.","First Name", "Last Name", "Email", "Phone","statut"); 
            
            $temp_max=0;

            foreach ($users as $row1 => $k1){
               
    
                $followupslist=array();

                $followupslist = DB::table('tj_user_app as user')->select('user.*')->get();

                $finalarr=$followupslist->toArray();

                $description=array();

                $description = array_column($finalarr, 'description');
                $description_cnt=count($description);
                $temp_max = max(isset($description_cnt)?$description_cnt:$temp_max,$temp_max);
                $tArray = array();
                 $max_value =array('');
              
                foreach ($description as $key1 => $value1){
                   $tArray[$key1] = $value1;
                   $max_value[] = $tArray;
                }
            }
            
            $myarr = array();

            foreach ($max_value as $key => $value){
                $max_count =count(array($value));
                $myarr[] =  $max_count;                    
            }   

            $num=1;

            for ($i=0; $i < $temp_max; $i++){ 
              $mytest = 'Follow-up '.$num;
              $users11[]=$mytest;
              $num++;
            }          

            $filename = "user_report_".date("Y.m.d").".".$type;

            if($type== 'csv'){
            	
                 header("Content-type: application/csv");
                 header("Content-Disposition: attachment; filename=".$filename." ");
            
			}elseif($type == 'xlsx'){
                header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
				header("Content-Disposition: attachment; filename=$filename");
				
			}elseif($type == 'xls'){
                header("Content-type: application/xls");
                header("Content-Disposition: attachment; filename=".$filename." ");
                
            }else{
                
                $arrnew = array();
                $pdf = new FPDF('P','mm',array(400,550));
                $pdf->SetFont('Arial','',8);
                $pdf->AddPage();
                
                foreach($users11 as $col)
                $pdf->Cell(40,7,$col,1,0);
               
                $pdf->Ln();
                
                foreach($users as $row){
                    $pdf->Cell(40,7,$row['id'],1,0);
                    $pdf->Cell(40,7,$row['nom'],1,0);
                    $pdf->Cell(40,7,$row['prenom'],1,0);
                    $pdf->Cell(40,7,$row['email'],1,0);
                    $pdf->Cell(40,7,$row['phone'],1,0);
                    $pdf->Cell(40,7,$row['statut'],1,0);
                    $pdf->Ln();
                    
                }
                $pdf->Output();
                
            }


            if($type != 'pdf'){
               $fp = fopen('php://output', 'w');
               fputcsv($fp,$users11);
            }

           $arrnew=array();
           
           foreach ($users as $row){

                $listarray=array('user.id','user.nom','user.prenom','user.email','user.phone','user.login_type','user.photo','user.photo_path','user.photo_nic','user.photo_nic_path','user.statut','user.statut_nic','user.tonotify','user.device_id','user.fcm_id','user.creer','user.updated_at','user.modifier','user.amount','user.reset_password_otp','user.reset_password_otp_modifier','user.age','user.gender','user.deleted_at','user.created_at');

                $list = DB::table('tj_user_app as user')
                        ->select($listarray)
                        ->where('user.id',$row['id'])                      
                        ->get();

                $arrnew=$list->toArray();
               
                $description = array_column($arrnew, 'description');

                $row5=array();

                foreach($arrnew as $key2 => $value2)

                {

                    $row5['id']=$row['id'];

                    $row5['nom']=$row['nom'];

                    $row5['prenom']=$row['prenom'];

                    $row5['email']=$row['email'];

                    $row5['phone']=$row['phone'];

                    $row5['statut']=$row['statut'];

                }

                foreach ($description as $key1 => $value1){

                        $mytest = 'Follow-up '.$key1;
                     $row5[$mytest]=$value1;

                }

            fputcsv($fp, $row5);
               
           }

        }else{
            $error = 'No Data Found';
            return Redirect()->back()->with(['message' => $error]);
        }
		
        fclose($fp);

    }

    public function driverreport(Request $request)
    {

        return view("reports.driverreport");
    }    

    public function downloadExcelDriver(Request $request){       
        $finalarr=array();         
        $status = $request->input('user_status');
        if($request->input('date') == 'today')
        {   $now = Carbon::now();
            $today =  $now->format('Y-m-d');
        }
        else{
            $today = '';
        }
        if($request->input('date') == 'week')
        {
            $now = Carbon::now();
            $week_start = $now->startOfWeek()->format('Y-m-d'); 
            $week_end = $now->endOfWeek()->format('Y-m-d'); 
        }
        else{
            $week_start = '';
            $week_end = '';
        }
        if($request->input('date') == 'month')
        {
            $now = Carbon::now();
            $month =  $now->month;
        }
        else{
            $month = '';
        }
        if($request->input('date') == 'year'){
            $now = Carbon::now();
            $year =  $now->year;
        }else{
            $year ='';
        }
        
        $from_date = $request->input('from');
        $to_date = $request->input('to');
        $type = $request->input('type');
        
        $drivers = Driver::when($status, function ($query) use ($status) {
                return $query->where('statut', $status);})
            ->when($today, function ($query) use ($today) {
                return $query->whereDate('creer', $today);})
                ->when($week_start && $week_end, function ($query, $condition) use($week_start, $week_end) { 
                return $query->whereBetween('creer', [$week_start, $week_end]);
                })
            ->when($month, function ($query) use ($month) {
                return $query->whereMonth('creer', $month);})
            ->when($year, function ($query) use ($year) {
                return $query->whereYear('creer', $year);})
            ->when($from_date && $to_date, function ($query, $condition) use($from_date, $to_date) { 
                return $query->whereBetween('creer', [$from_date, $to_date]);
                })
            
            ->get();

        $fp = fopen('php://output', 'w');

        if($drivers->count() > 0){
        $drivers11 = array("Sr. No.","First Name", "Last Name","Phone", "Latitude", "Longitude","email","Status","online", "Login Type", "Photo Path","Tonotify","Address", "Bank Name", "Branch Name", "Holder Name" ,"Account No","Other Info","Creer","Modifier","Amount"); 
            $temp_max=0;

            foreach ($drivers as $row1 => $k1) {
               
    
                 $followupslist=array();

                 $followupslist = DB::table('tj_conducteur as driver')->select('driver.*')->get();

                $finalarr=$followupslist->toArray();

                $description=array();

                $description = array_column($finalarr, 'description');

                $description_cnt=count($description);
                $temp_max = max(isset($description_cnt)?$description_cnt:$temp_max,$temp_max);
                $tArray = array();
                 $max_value =array('');
              
                foreach ($description as $key1 => $value1)

                {
                   $tArray[$key1] = $value1;

                   $max_value[] = $tArray;
                }

            }            
            
            $myarr = array();

            foreach ($max_value as $key => $value)

            {
                $max_count =count(array($value));

                $myarr[] =  $max_count;                    

            }   

            $num=1;

            for ($i=0; $i < $temp_max; $i++)
            { 

              $mytest = 'Follow-up '.$num;

              $drivers11[]=$mytest;

              $num++;

            }          
            $filename = "Driver_report_".date("Y.m.d").".".$type;

            if($type== 'csv')

            {

                 header("Content-type: application/csv");
                 header("Content-Disposition: attachment; filename=".$filename." ");
            }

            elseif($type == 'xlsx')

            {
                header("Content-type: application/xlsx");

                header("Content-Disposition: attachment; filename=".$filename." ");
                
            }

            elseif($type == 'xls')


            {
                header("Content-type: application/xls");

                header("Content-Disposition: attachment; filename=".$filename." ");
            }
            else  {
                
                $arrnew = array();
                $dir = 'assets\pdf';
                $pdf = new FPDF('P','mm',array(400,550));
                $pdf->SetFont('Arial','',8);
                $pdf->AddPage();
                
                $pdf->Cell(10,7,'Sr. No.',1,0);
                $pdf->Cell(20,7,'First Name',1,0);
                $pdf->Cell(20,7,'Last Name',1,0);
                $pdf->Cell(30,7,'phone',1,0);
                $pdf->Cell(15,7,'Latitude',1,0);
                $pdf->Cell(15,7,'Longitude',1,0);
                $pdf->Cell(40,7,'email',1,0);
                $pdf->Cell(10,7,'Status',1,0);
                $pdf->Cell(10,7,'online',1,0);
                $pdf->Cell(15,7,'Login Type',1,0);
                $pdf->Cell(30,7,'Address',1,0);
                $pdf->Cell(20,7,'Bank Name',1,0);
                $pdf->Cell(20,7,'Branch Name',1,0);
                $pdf->Cell(30,7,'Holder Name',1,0);
                $pdf->Cell(30,7,'Account',1,0);
                $pdf->Cell(30,7,'Other Info',1,0);
                $pdf->Cell(30,7,'Creer',1,0);
                $pdf->Cell(15,7,'Amount',1,0);
                $pdf->Ln();
                
                foreach($drivers as $row)
                {
                    
                    $pdf->Cell(10,7,$row['id'],1,0);
                    $pdf->Cell(20,7,$row['nom'],1,0);
                    $pdf->Cell(20,7,$row['prenom'],1,0);
                    $pdf->Cell(30,7,$row['phone'],1,0);
                    $pdf->Cell(15,7,$row['latitude'],1,0);
                    $pdf->Cell(15,7,$row['longitude'],1,0);
                    $pdf->Cell(40,7,$row['email'],1,0);
                    $pdf->Cell(10,7,$row['statut'],1,0);
                    $pdf->Cell(10,7,$row['online'],1,0);
                    $pdf->Cell(15,7,$row['login_type'],1,0);
                    $pdf->Cell(30,7,$row['address'],1,0);
                    $pdf->Cell(20,7,$row['bank_name'],1,0);
                    $pdf->Cell(20,7,$row['branch_name'],1,0);
                    $pdf->Cell(30,7,$row['holder_name'],1,0);
                    $pdf->Cell(30,7,$row['account_no'],1,0);
                    $pdf->Cell(30,7,$row['other_info'],1,0);
                    $pdf->Cell(30,7,$row['creer'],1,0);
                    $pdf->Cell(15,7,$row['amount'],1,0);
                    $pdf->Ln();
                    
                }
                
                $pdf->Output();
              
            }
            if($type != 'pdf'){
            fputcsv($fp,$drivers11,',','"');  
            }
            $arrnew=array();  

            $fp = fopen('php://output', 'w');
            foreach ($drivers as $row) {

                 $listarray=array('driver.id','driver.nom','driver.prenom','driver.phone','driver.latitude','driver.longitude','driver.email','driver.statut','driver.online','driver.login_type','driver.photo_path','driver.tonotify','driver.device_id','driver.fcm_id','driver.address','driver.bank_name','driver.branch_name','driver.holder_name','driver.account_no','driver.other_info','driver.creer','driver.modifier','driver.updated_at','driver.amount','driver.reset_password_otp','driver.reset_password_otp_modifier','driver.deleted_at');
                $list = DB::table('tj_conducteur as driver')
                        ->select($listarray)
                        ->where('driver.id',$row['id'])                      
                        ->get();

                $arrnew=$list->toArray();

                $description = array_column($arrnew, 'description');


                $row5=array();

                foreach($arrnew as $key2 => $value2)

                {

                    $row5['id']=$row['id'];

                    $row5['nom']=$row['nom'];

                    $row5['prenom']=$row['prenom'];                    

                    $row5['phone']=$row['phone'];

                    $row5['latitude']=$row['latitude'];

                    $row5['longitude']=$row['longitude'];

                    $row5['email']=$row['email'];

                    $row5['statut']=$row['statut'];

                    $row5['online']=$row['online'];

                    $row5['login_type']=$row['login_type'];

                    $row5['photo_path']=$row['photo_path'];

                    $row5['tonotify']=$row['tonotify'];

                    $row5['address']=$row['address'];
                  
                    $row5['bank_name']=$row['bank_name'];

                    $row5['branch_name']=$row['branch_name'];

                    $row5['holder_name']=$row['holder_name'];

                    $row5['account_no']=$row['account_no'];

                    $row5['other_info']=$row['other_info'];

                    $row5['creer']=$row['creer'];

                    $row5['modifier']=$row['modifier'];

                    $row5['amount']=$row['amount'];

                }

                foreach ($description as $key1 => $value1){

                        $mytest = 'Follow-up '.$key1;

                         $row5[$mytest]=$value1;

                }

            fputcsv($fp, $row5);

           }

        }else{
            $error = 'No Data Found';
            return Redirect()->back()->with(['message' => $error]);
        }
       fclose($fp);

    }
    public function travelreport(Request $request)
    {

       $type =  VehicleType::where('deleted_at','=',Null)->get();
        return view("reports.travelreport")->with('type',$type);
    }    

    public function downloadExcelTravel(Request $request){       
        $finalarr=array();         
        $trip_status = $request->input('trip_status');
        $payment = $request->input('payment');
        if($request->input('date') == 'today')
        {   $now = Carbon::now();
            $today =  $now->format('Y-m-d');
        }
        else{
            $today = '';
        }
        if($request->input('date') == 'week')
        {
            $now = Carbon::now();
            $week_start = $now->startOfWeek()->format('Y-m-d'); 
            $week_end = $now->endOfWeek()->format('Y-m-d'); 
        }
        else{
            $week_start = '';
            $week_end = '';
        }
        if($request->input('date') == 'month')
        {
            $now = Carbon::now();
            $month =  $now->month;
        }
        else{
            $month = '';
        }
        if($request->input('date') == 'year'){
            $now = Carbon::now();
            $year =  $now->year;
        }else{
            $year ='';
        }
        
        $from_date = $request->input('from');
        $to_date = $request->input('to');
        $type = $request->input('type');
        
        $rides = Requests::when($trip_status, function ($query) use ($trip_status) {
            return $query->where('statut', $trip_status);})
            ->when($payment, function ($query) use ($payment) {
                return $query->where('id_payment_method', $payment);})
        ->when($today, function ($query) use ($today) {
            return $query->whereDate('creer', $today);})
            ->when($week_start && $week_end, function ($query, $condition) use($week_start, $week_end) { 
            return $query->whereBetween('creer', [$week_start, $week_end]);
            })
        ->when($month, function ($query) use ($month) {
            return $query->whereMonth('creer', $month);})
        ->when($year, function ($query) use ($year) {
            return $query->whereYear('creer', $year);})
        ->when($from_date && $to_date, function ($query, $condition) use($from_date, $to_date) { 
            return $query->whereBetween('creer', [$from_date, $to_date]);
            })
        
        ->get();
        
        $fp = fopen('php://output', 'w');

        if($rides->count() > 0){
        $rides11 = array("S.No","Trip Start From","Trip End To","User Name","Driver Name","Trip Status","Paid Status","Payment Option","Trip Distance","Total Amount"); 
            $temp_max=0;

            foreach ($rides as $row1 => $k1) {
               
    
                 $followupslist=array();
                 $followupslist = DB::table('tj_requete as request')
                 ->join('tj_user_app', 'request.id_user_app', '=', 'tj_user_app.id')
                 ->join('tj_conducteur', 'request.id_conducteur', '=', 'tj_conducteur.id')
                 ->join('tj_payment_method', 'request.id_payment_method', '=', 'tj_payment_method.id')
                 ->select('request.id','request.statut', 'request.statut_paiement','request.depart_name','request.destination_name','request.distance','request.montant','request.creer','tj_conducteur.id as driver_id','tj_conducteur.prenom as driverPrenom','tj_conducteur.nom as driverNom','tj_user_app.id as user_id','tj_user_app.prenom as userPrenom','tj_user_app.nom as userNom','tj_payment_method.libelle','tj_payment_method.image')
                 ->where('tj_conducteur.id','=','request.id_conducteur')
                 ->where('tj_user_app.id','=','request.id_user_app')
                 ->where('tj_payment_method.id','=','request.id_payment_method')
                 ->get();
                $finalarr=$followupslist->toArray();

                $description=array();

                $description = array_column($finalarr, 'description');

                $description_cnt=count($description);
                $temp_max = max(isset($description_cnt)?$description_cnt:$temp_max,$temp_max);
                $tArray = array();
                $max_value =array('');
              
                foreach ($description as $key1 => $value1)
                {
                   $tArray[$key1] = $value1;

                   $max_value[] = $tArray;
                }

            }            
            $myarr = array();

            foreach ($max_value as $key => $value)

            {
                $max_count =count(array($value));

                $myarr[] =  $max_count;                    

            }   

            $num=1;

           for ($i=0; $i < $temp_max; $i++)
            { 

              $mytest = 'Follow-up '.$num;

              $drivers11[]=$mytest;

              $num++;

            }          
            $filename = "Travel_report_".date("Y.m.d").".".$type;

            if($type== 'csv')

            {

                 header("Content-type: application/csv");
                 header("Content-Disposition: attachment; filename=".$filename." ");
            }

            elseif($type == 'xlsx')

            {
                header("Content-type: application/xlsx");

                header("Content-Disposition: attachment; filename=".$filename." ");
                
            }

            elseif($type == 'xls')


            {
                header("Content-type: application/xls");

                header("Content-Disposition: attachment; filename=".$filename." ");
            }
            else  {
                
                $arrnew = array();
                $dir = 'assets\pdf';
                $pdf = new FPDF('P','mm','A4');
                $pdf->SetFont('Arial','',8);
                $pdf->AddPage();
                
                $pdf->Cell(20,7,'Sr. No.',1,0);
                $pdf->Cell(20,7,'Request Id',1,0);
                $pdf->Cell(30,7,'Trip Start From',1,0);
                $pdf->Cell(30,7,'Trip End To',1,0);
                $pdf->Cell(15,7,'User Name',1,0);
                $pdf->Cell(15,7,'Driver Name',1,0);
                $pdf->Cell(15,7,'Trip Status',1,0);
                $pdf->Cell(15,7,'Paid Status',1,0);
                $pdf->Cell(10,7,'Payment Option',1,0);
                $pdf->Cell(15,7,'Trip Distance',1,0);
                $pdf->Cell(30,7,'Total Amount',1,0);
              
                $pdf->Ln();
                
                foreach($rides as $row)
                {
                    
                    $pdf->Cell(20,7,$row['id'],1,0);
                    $pdf->Cell(20,7,$row['id'],1,0);
                    $pdf->Cell(30,7,$row['depart_name'],1,0);
                    $pdf->Cell(30,7,$row['destination_name'],1,0);
                    $userName = UserApp::where('id',$row['id_user_app'])->get();
                    foreach($userName as $row_user) {
                        $pdf->Cell(15,7,$row_user['nom'],1,0);
                    }
                    $driverName = Driver::where('id',$row['id_conducteur'])->get();
                    foreach($driverName as $row_driver) {
                        $pdf->Cell(15,7,$row_driver['nom'],1,0);
                    }
                    $pdf->Cell(15,7,$row['statut'],1,0);
                    $pdf->Cell(15,7,$row['statut_paiement'],1,0);
                    $payment = PaymentMethod::where('id',$row['id_payment_method'])->get();
                    foreach($payment as $row_payment) {
                        $pdf->Cell(10,7,$row_payment['libelle'],1,0);

                    }
                    $pdf->Cell(15,7,$row['distance'],1,0);
                    $pdf->Cell(30,7,$row['montant'],1,0);
                    $pdf->Ln();
                    
                }
                
                $pdf->Output();
              
            }
            if($type != 'pdf'){
            fputcsv($fp,$rides11,',','"');  
            }
            $arrnew=array();  

            $fp = fopen('php://output', 'w');

            foreach ($rides as $row) {

                 $listarray=array('request.id','request.depart_name','request.destination_name','tj_user_app.nom','tj_conducteur.nom','request.statut','request.statut_paiement','tj_payment_method.libelle','request.distance','request.montant');
                 
                 $list = DB::table('tj_requete as request')
                ->join('tj_user_app', 'request.id_user_app', '=', 'tj_user_app.id')
                 ->join('tj_conducteur', 'request.id_conducteur', '=', 'tj_conducteur.id')
                 ->join('tj_payment_method', 'request.id_payment_method', '=', 'tj_payment_method.id')
                 ->select('request.id','request.statut', 'request.statut_paiement','request.depart_name','request.destination_name','request.distance','request.montant','request.creer','tj_conducteur.id as driver_id','tj_conducteur.prenom as driverPrenom','tj_conducteur.nom as driverNom','tj_user_app.id as user_id','tj_user_app.prenom as userPrenom','tj_user_app.nom as userNom','tj_payment_method.libelle','tj_payment_method.image')
                 ->where('tj_conducteur.id',$row['id_conducteur'])   
                 ->where('tj_user_app.id',$row['id_user_app'])   
                 ->where('tj_payment_method.id',$row['id_payment_method'])  
                 ->get();

                $arrnew=$list->toArray();

                $description = array_column($arrnew, 'description');
                $row5=array();

                foreach($arrnew as $key2 => $value2)

                {
                   
                    $row5['id']=$row['id'];

                    $row5['depart_name']=$row['depart_name'];                    

                    $row5['destination_name']=$row['destination_name'];
                    $userName = UserApp::where('id',$row['id_user_app'])->get();
                    foreach($userName as $row_user) {
                        $row5['userNom']=$row_user['nom'];
                    }
                    $driverName = Driver::where('id',$row['id_conducteur'])->get();
                    foreach($driverName as $row_driver) {
                        $row5['driverNom']=$row_driver['nom'];

                    }
                   
                    $row5['statut']=$row['statut'];

                    $row5['statut_paiement']=$row['statut_paiement'];
                    $payment = PaymentMethod::where('id',$row['id_payment_method'])->get();
                    foreach($payment as $row_payment) {
                        $row5['libelle']=$row_payment['libelle'];

                    }
                    $row5['distance']=$row['distance'];

                    $row5['montant']=$row['montant'];

                  
                }
              
                foreach ($description as $key1 => $value1){

                        $mytest = 'Follow-up '.$key1;

                         $row5[$mytest]=$value1;

                }

            fputcsv($fp, $row5);

           }

        }else{
            $error = 'No Data Found';
            return Redirect()->back()->with(['message' => $error]);
        }
       fclose($fp);

    }
}
