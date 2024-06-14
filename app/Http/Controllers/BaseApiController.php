<?php
namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Response as ReturnResponse;


class BaseApiController extends Controller
{

    public static function successResponse($data=[], $message = '', $errors = [], $statusCode = Response::HTTP_OK){
        $result = ['status' => 1, 'message'=>$message, 'errors'=>$errors, 'data'=>$data];
        return ReturnResponse::json($result, $statusCode)->header('Content-Type', "application/json");
    }

    public static function errorResponse($data=[], $message = '', $errors = [], $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR){
        $data['status']=$statusCode;
        $data['message']=$message;
        $data['error']=$message;
        return response([
            'response_time'=>microtime(true) - LARAVEL_START,
            'data'=>$data,
        ],$statusCode);
    }

    public static function error401Response($data=[], $message = '', $errors = [], $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR){
        $data=[
            'error' => [
                'message'     => $message,
                'status_code' => $statusCode
            ],
        ];
        return ReturnResponse::json($data, $statusCode)->header('Content-Type', "application/json");
    }

    public function returnResponse($result){
        if ($result['status']==1){
            return BaseApiController::successResponse($result['data'],$result['message']);
        }else{
            return BaseApiController::errorResponse($result['data'],$result['message']);
        }
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}