<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

trait ApiResponser
{

    function successResponse($data, $code = Response::HTTP_OK)
    {
        // return response()->json(['data'=>$data,'message'=>$message,'success'=>$success], $code);
        return response()->json($data, $code);
    }

    function errorResponse($exception, $code = Response::HTTP_UNPROCESSABLE_ENTITY, $data = null)
    {
        Log::error($exception);
        return response()->json(['data' => $data, 'message' => $exception->getMessage(), 'success' => false], $code);
    }

    function validateResponse($exception, $code = Response::HTTP_UNPROCESSABLE_ENTITY, $data = null)
    {
        return response()->json(['data' => $data, 'message' => $exception->getMessage(), 'success' => false], $code);
    }
}
