<?php

namespace App\Shareds;

class ApiResponser
{
    const unprocessableEntity = 'unprocessableEntity';

    public static function generateMessageStore(string $model)
    {
        return 'Store ' . $model . ' successfully';
    }

    public static function generateMessageUpdate(string $model)
    {
        return 'Update ' . $model . ' successfully';
    }

    public static function successResponser($data, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function errorResponse($message = null, $code = 400)
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => null
        ], $code);
    }
}
