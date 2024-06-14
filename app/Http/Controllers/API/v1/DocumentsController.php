<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use App\Models\DriversDocuments;

class DocumentsController extends Controller
{

    public function __construct()
    {
        $this->limit = 20;
    }

    public function getData(Request $request)
    {

        $admin_documents = DB::table('admin_documents')->where('is_enabled','Yes')->get();
        $output=[];
        foreach ($admin_documents as $row){
            $row->id = (string)$row->id;
            $output[]= $row;

        }
            if (!empty($admin_documents)) {
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'successfully';
                $response['data'] = $output;
            } else {
                $response['success'] = 'Failed';
                $response['error'] = 'Failed to fetch data';
                $response['message'] = 'successfully';
            }



        return response()->json($response);


    }

    public function addDriverDocuments(Request $request)
    {

        $driver_id = $request->get('driver_id');

		$documents = $request->get('documents');

		$attachment = $request->file('attachment');

		if (empty($driver_id) || $driver_id == 0) {

            $response['success'] = 'Failed';
            $response['error'] = 'Driver Not Found';

		} else if (empty($documents)) {

		    $response['success'] = 'Failed';
            $response['error'] = 'Documents Not Found';

		} else if (empty($attachment)) {

            $response['success'] = 'Failed';
            $response['error'] = 'Attachment Not Found';

		} else {

            $documents = json_decode($documents);

            foreach ($documents as $data) {

                $document_id = $data->document_id;

                $attachmentIndex = $data->attachmentIndex;

                if ($attachmentIndex != '') {

                    $image_path = $attachment[$attachmentIndex];

                    $extenstion = $image_path->getClientOriginalExtension();

                    $document_name = DB::table('admin_documents')->where('id', $document_id)->first();

                    $filename = str_replace(' ','_',$document_name->title) . '_' . time() . '.' . $extenstion;

                    $image_path->move(public_path('assets/images/driver/documents/'), $filename);

                    if (file_exists(public_path('assets/images/driver/documents' . '/' . $filename))) {

						$driver_document = new DriversDocuments;
						$driver_document->driver_id = $driver_id;
						$driver_document->document_id = $document_id;
						$driver_document->document_path = $filename;
						$driver_document->document_status = 'Pending';
						$driver_document->save();

                        if($driver_document->id > 0) {
                            $response['success'] = 'success';
                            $response['error'] = null;
                            $response['message'] = 'Documents Add Successfully';
                        } else {
                            $response['success'] = 'Failed';
                            $response['error'] = 'Documents Not Add';
                        }
                    } else {
                        $response['success'] = 'Failed';
                        $response['error'] = 'File Not Found';
                    }
                } else {
                    $response['success'] = 'Failed';
                    $response['error'] = 'Document Not Found';
                }
            }
        }

        return response()->json($response);

    }

    public function updateDriverDocuments(Request $request)
    {

		$driver_id = $request->get('driver_id');

		$document_id = $request->get('document_id');

        $attachment = $request->file('attachment');

		if (empty($document_id) || $document_id == 0) {

		    $response['success'] = 'Failed';
            $response['error'] = 'Document Id Not Found';

		}else if (empty($driver_id) || $driver_id == 0) {

		    $response['success'] = 'Failed';
            $response['error'] = 'Driver Id Not Found';

		} else if (empty($attachment)) {

		    $response['success'] = 'Failed';
            $response['error'] = 'Attachment Not Found';

		} else {

			$file = $request->file('attachment');
            $extenstion = $file->getClientOriginalExtension();

            $document_name = DB::table('admin_documents')->where('id',$document_id)->first();
            $filename = str_replace(' ','_',$document_name->title) . '_' . time() . '.' . $extenstion;
            $file->move(public_path('assets/images/driver/documents/'), $filename);

            $get_driver_document = DB::table('driver_document')->where('document_id',$document_id)->where('driver_id',$driver_id)->first();
			if($get_driver_document){
				if(file_exists(public_path('assets/images/driver/documents' . '/' . $get_driver_document->document_path))) {
	                unlink(public_path('assets/images/driver/documents' . '/' . $get_driver_document->document_path));
	            }
				$driver_document = DriversDocuments::find($get_driver_document->id);
				$driver_document->document_path = $filename;
				$driver_document->document_status = 'Pending';
				$driver_document->save();
			}else{
				$driver_document = new DriversDocuments;
				$driver_document->driver_id = $driver_id;
				$driver_document->document_id = $document_id;
				$driver_document->document_path = $filename;
				$driver_document->document_status = 'Pending';
				$driver_document->save();
			}

			$get_driver_document = DB::table('driver_document')->where('document_id',$document_id)->where('driver_id',$driver_id)->first();

            if($get_driver_document){

				$get_driver_document->document_path = url('assets/images/driver/documents/'.$get_driver_document->document_path);
				$get_driver_document->document_name = $document_name->title;
				$get_driver_document->id = $get_driver_document->document_id;

				unset($get_driver_document->document_id);

                $response['success'] = 'Success';

                $response['error'] = null;

                $response['message'] = $document_name->title . ' Updated';

                $response['data'] = $get_driver_document;

			} else {

			    $response['success'] = 'Failed';

			    $response['error'] = $document_name->title . ' Not Updated';
            }
        }

        return response()->json($response);

    }

    public function getDriverDocuments(Request $request)
    {

        $driver_id = $request->get('driver_id');

		$admin_documents = DB::table('admin_documents')->where('is_enabled','=','Yes')->get();

        if(!empty($admin_documents)){

            foreach ($admin_documents as $key=>$document) {
              $id=$document->id;
				$get_driver_document = DB::table('driver_document')->where('document_id', $document->id)->where('driver_id', $driver_id)->first();
				 $document->id=(string)$id;
				if($get_driver_document){
					$document->document_path = url('assets/images/driver/documents/'.$get_driver_document->document_path);
					$document->document_status = $get_driver_document->document_status;
					$document->comment = $get_driver_document->comment;
				}else{
					$document->document_path = '';
					$document->document_status = 'Pending';
					$document->comment = '';
				}
            	$document->document_name = $document->title;

				$admin_documents[$key] = $document;
            }

            $response['success'] = 'success';
            $response['error'] = null;
            $response['message'] = 'successfully';
            $response['data'] = $admin_documents;
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Failed to fetch data';
            $response['message'] = 'successfully';
        }

        return response()->json($response);

    }


}
