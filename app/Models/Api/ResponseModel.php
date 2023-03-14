<?php

namespace App\Models\Api;

class ResponseModel {
    public string $message;
    public ResponseState $state;

    function __construct()
    {
        $this->state = ResponseState::Error;
    }

    public function success()
    {
        $this->state = ResponseState::Success;
    }

}

enum ResponseState: string {
    case Success = "success";
    case Error = "error";
}
