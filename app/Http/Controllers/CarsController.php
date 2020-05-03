<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class CarsController extends Controller
{
    public function index(Request $request) {
        echo "index";
        die();
    }

    public function store(Request $request) {

    }
}