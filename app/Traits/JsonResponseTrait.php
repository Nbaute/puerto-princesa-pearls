<?php

namespace App\Traits;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

trait JsonResponseTrait
{
    public function jsonResponse($message, $data, $code, $headers = [])
    {
        return response([
            'message' => $message,
            'data' => $data,
        ], $code, $headers);
    }

    public function jsonSuccess($message = "Success!", $data = [], $code = Response::HTTP_OK, $headers = [])
    {
        return $this->jsonResponse($message, $data, $code, $headers);
    }
    public function jsonError($message = "Error!", $data = [], $code = Response::HTTP_BAD_REQUEST, $headers = [])
    {
        return $this->jsonResponse($message, $data, $code, $headers);
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->jsonError('Validation failed', ['errors' => $validator->errors()]));
    }

}
