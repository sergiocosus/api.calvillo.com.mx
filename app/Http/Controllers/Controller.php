<?php

namespace CalvilloComMx\Http\Controllers;

use CalvilloComMx\Http\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function success($data = null)
    {
        return Response::success($data);
    }

    protected function fail($data = null)
    {
        return Response::fail($data);
    }

    protected function error($code = 500, $message = "",  $data = null)
    {
        return Response::error($code, $message, $data);
    }

}