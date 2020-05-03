<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class CarsController extends Controller
{
    public function index(Request $request) {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();

        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken) {
            echo "index";
            die();
        } else {
            echo "index error";
            die();
        }
    }

    public function store(Request $request) {

    }
}