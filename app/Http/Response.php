<?php namespace CalvilloComMx\Http;



class Response
{
    public static function success($data = null)
    {
        $response =  \Response::json([
            'status' => 'success',
            'data' => $data
        ]);

        return self::appendControlAccessHeaders($response);
    }

    public static function fail($data = null)
    {
        $response = \Response::json([
            'status' => 'fail',
            'data' => $data
        ]);

        return self::appendControlAccessHeaders($response);
    }

    public static function error($code = 500, $message = "",  $data = null)
    {
        $response = \Response::json([
            'status' => 'error',
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ],500);

        return self::appendControlAccessHeaders($response);
    }

    public static function appendControlAccessHeaders($response){
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->header('Access-Control-Allow-Headers', 'X-Auth-Token, X-Auth-Key, X-Auth-Secret-Key, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
        return $response;
    }

}