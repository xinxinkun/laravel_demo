<?php
/**
 * Created by PhpStorm.
 * User: eastown
 * Date: 2018/12/5
 * Time: 11:01
 */

namespace App\Http\Responses;


use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse
{
    const CODE_SUCCESS = 1000;

    public function __construct($data = null, $code = self::CODE_SUCCESS, $message = '', $status = 200, $headers = [])
    {
        $json = [
            'data' => $data,
            'code' => $code,
            'msg' => $message
        ];
        parent::__construct($json, $status, $headers, JSON_UNESCAPED_SLASHES);
    }

    public function isSuccessful(): bool
    {
        return parent::isSuccessful() && $this->getData()->code == self::CODE_SUCCESS;
    }

    public function isOk(): bool
    {
        return parent::isOk() && $this->getData()->code == self::CODE_SUCCESS;
    }
}