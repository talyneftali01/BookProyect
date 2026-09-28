<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Responses\ApiResponse;

class BaseController extends Controller
{
    protected function success($data=[],$message= null, $code= 200)
    {
        return ApiResponse::success($data,$message,$code);
    }
    protected function error($result=[],$message= 'Error', $code= 400)
    {
        return ApiResponse::error($result,$message,$code);
    }
}