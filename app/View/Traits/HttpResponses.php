<?php

namespace App\View\Traits;

trait HttpResponses
{

    protected function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'error' => 0,
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error($data, $message = null, $code)
    {
        return response()->json([
            'error' => 1,
            'status' => 'fail',
            'message' => $message,
            'data' => $data
        ], $code);
    }


}
