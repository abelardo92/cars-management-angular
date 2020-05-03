<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Helpers\JwtAuth;


class UsersController extends Controller
{
    public function register(Request $request) {
        $json = $request->input('json', null);
        $params = json_decode($json);

        if(!is_null($json)) {
            $email = isset($params->email) ? $params->email : null;
            $name = isset($params->name) ? $params->name : null;
            $last_name = isset($params->last_name) ? $params->last_name : null;
            $role = 'ROLE_USER';
            $password = isset($params->password) ? $params->password : null;
            if(!is_null($email) && !is_null($password) && !is_null($name)) {
                // echo "$name $email $password"; die();
                $user = new User();
                $user->email = $email;
                $user->name = $name;
                $user->last_name = $last_name;
                $user->role = $role;
                $user->password = hash('sha256', $password);

                $isset_user = User::where('email', $email)->first();

                if(!$isset_user) {
                    $user->save();
                    return $this->userCreatedSuccess();
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

    private function wrongDataError() {
        $data = array(
            'status' => 'error',
            'code' => 400,
            'message' => 'Wrong data'
        );
        return response()->json($data, 200);
    }

    public function login(Request $request) {

        $jwtAuth = new JwtAuth();
        $json = $request->input('json', null);
        $params = json_decode($json);

        if(!is_null($json)) {
            $email = isset($params->email) ? $params->email : null;
            $password = isset($params->password) ? $params->password : null;
            $getToken = isset($params->get_token) ? $params->get_token : null;

            $pwd = hash('sha256', $password);

            if($getToken) {
                $signup = $jwtAuth->signup($email, $pwd, $getToken);
            } else {
                $signup = $jwtAuth->signup($email, $pwd);
            }
            // echo $pwd; die();
            return response()->json($signup, 200);

        }
        return $this->wrongDataError();
    }
}
