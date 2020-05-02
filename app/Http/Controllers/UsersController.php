<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;


class UsersController extends Controller
{
    public function register(Request $request) {
        $json = $request->input('json', null);
        $params = json_decode($json);

        if(!is_null($json)) {
            $email = isset($params->email) ? $params->email : null;
            $name = isset($params->name) ? $params->name : null;
            $surname = isset($params->surname) ? $params->surname : null;
            $role = 'ROLE_USER';
            $password = isset($params->password) ? $params->password : null;

            if(!is_null($email) && !is_null($password) && !is_null($name)) {
                $user = new User();
                $user->email = $email;
                $user->name = $name;
                $user->surname = $surname;
                $user->role = $role;
                $user->password = hash('sha256', $password);

                $isset_user = User::where('email', $email)->first();

                if(count($isset_user) == 0) {
                    $user->save();
                } else {
                    return $this->userDuplicatedError();
                }

            } else {
                return $this->userNotCreatedError();
            }
        }
        return $this->userNotCreatedError();
    }

    private function userCreatedSuccess() {
        $data = array(
            'status' => 'success',
            'code' => 400,
            'message' => 'User created succesfully'
        );
        return response()->json($data, 200);
    }

    private function userNotCreatedError() {
        $data = array(
            'status' => 'error',
            'code' => 400,
            'message' => 'User not created'
        );
        return response()->json($data, 200);
    }

    private function userDuplicatedError() {
        $data = array(
            'status' => 'error',
            'code' => 400,
            'message' => 'User duplicated'
        );
        return response()->json($data, 200);
    }

    public function login(Request $request) {
        echo "login";
        die();
    }
}
