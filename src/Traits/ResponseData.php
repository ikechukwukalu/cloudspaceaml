<?php

namespace Cloudspace\AML\Traits;

use Illuminate\Http\Response;

trait ResponseData
{

    public function responseData(bool $success, mixed $data, string|null $customMsg = null): array
    {
        $status = $success ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
        $message = $success ? 'We\'ve got a response' : 'We encountered some errors';

        if ($customMsg) {
            $message = $customMsg;
        }

        return [
            'success' => $success,
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
    }
}
