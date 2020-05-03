<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;
use Exception;
use UnexpectedValueException;

class JwtAuth {

    public $key = '';

    public function __construct() {
        $this->key = '951qUBnKUqf6CNzmAP7cQpv7KZiswXFGrnX2jat';
    }

    public function signup($email, $password, $getToken = null) {

        if($user = User::where('email', $email)->where('password', $password)->first()) {

            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60), 
            );

            $jwt = JWT::encode($token, $this->key);
            $decoded = JWT::decode($jwt, $this->key, array('HS256'));
            return is_null($getToken) ? $jwt : $decoded;
        } 
        return $this->loginFailedError();
    }

    public function checkToken($jwt, $getIdentity = false) {
        $auth = false;
        try {
            $decoded = JWT::decode($jwt, $this->key, array('HS256'));
            $auth = is_object($decoded) && isset($decoded->sub) ? true : false;

            if($getIdentity && isset($decoded) && is_object($decoded)) {
                return $decoded;
            }
        } 
        // catch(UnexpectedValueException $e) {
        //     $auth = false;
        // }
        // catch(UnexpectedValueException $e) {
        //     $auth = false;
        // }
        catch(Exception $e) {
            $auth = false;
        }


        return $auth;
    }

    private function loginFailedError() {
        $data = array(
            'status' => 'error',
            'message' => 'Login has failed',
        );
        return $data;
    }
}
?>