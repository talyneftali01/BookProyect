<?php
namespace App\Responses;

class ApiResponse
{
    public static function success($result =[],$message=null,$code=200)
    {
        return response()->json ([
            'response'=> true,
            'result'=>$result,
            'message' => $message,
        ],$code);
    }
    public static function error($result =[],$message=null,$code=400)
    {
        return response()->json ([
            'response'=> false,
            'result'=>$result,
            'message' => $message,
        ],$code);
    }
}