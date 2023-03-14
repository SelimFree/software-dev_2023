<?php

namespace App\Models\Api;

use App\Models\Api\ResponseModel;
use App\Models\User;

class UserModel extends ResponseModel {
    public string $name;
    public string $email;
    public string $dob;
    public string $lang;
    public bool $license_accepted;

    public static function fromUser(User $user) : UserModel
    {
        $viewUser = new UserModel();
        $viewUser->name = $user->first_name . " " . $user->last_name;
        $viewUser->email = $user->email;
        $viewUser->dob = $user->dob;
        $viewUser->lang = "en-US";
        $viewUser->license_accepted = $user->license_accepted;

        $viewUser->success();

        return $viewUser;
    }

    public static function fromError(string $message) : UserModel
    {
        $viewUser = new UserModel();
        $viewUser->message = $message;
        return $viewUser;
    }
}
