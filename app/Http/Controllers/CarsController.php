<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Car;
use Exception;

class CarsController extends Controller
{
    protected $user;

    public function __construct()
    {
        // $jwtAuth = new JwtAuth();
        // $hash = $request->header('Authorization', null);
    }

    public function index(Request $request) {
        echo "index";
        die();
    }

    public function store(Request $request) {
        $jwtAuth = new JwtAuth();
        $hash = $request->header('Authorization', null);
        
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json,true);
        
        $user = $jwtAuth->checkToken($hash, true);
        
        $request->merge($params_array);
        
        try {
            $validate = $this->validate($request, [
                'title' => 'required|min:5',
                'description' => 'required',
                'price' => 'required',
                'status' => 'required',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            $errors = '';
            foreach($e->errors() as $key => $value) {
                $errors .= $value[0];
            }
            $data = array(
                'error' => $errors,
                'status' => 'error',
                'code' => 400,
            );
            return $data;
        }

        $car = new Car();
        $car->user_id = $user->sub;
        $car->title = $params->title;
        $car->description = $params->description;
        $car->price = $params->price;
        $car->status = $params->status;
        $car->save();

        $data = array(
            'car' => $car,
            'status' => 'success',
            'code' => 200,
        );

        return response()->json($data);

    }
}