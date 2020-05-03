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

    public function index() {
        $cars = Car::all()->load('user');
        return response()->json(array(
            'cars' => $cars,
            'status' => 'success',
        ), 200);
    }

    public function show($car_id) {
        $car = Car::find($car_id)->load('user');
        return response()->json(array(
            'car' => $car,
            'status' => 'success',
        ), 200);
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

    public function update($car_id, Request $request) {
        
        $json = $request->input('json', null);
        $params_array = json_decode($json,true);
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

        $car = Car::where('id',$car_id)->update($params_array);
        $car = Car::find($car_id);
        $data = array(
            'car' => $car,
            'status' => 'success',
            'code' => 200,
        );

        return response()->json($data);   
    }

    public function destroy($car_id, Request $request) {
        $car = Car::find($car_id);
        $car->delete();

        $data = array(
            'car' => $car,
            'status' => 'success',
            'code' => 200,
        );
        return response()->json($data);
    }
}