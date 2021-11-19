<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;
use App\User;
use Hash;

class ApiController extends Controller
{
    public function  register(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);


        $user = User::firstOrCreate(
            ['email' =>  $request->email],
            ['name' => $request->name, 'password' =>  Hash::make($request->password)]
        );



        $http = new Client;

//        $response = $http->post('http://localhost/1_zubayer_lara_ecom_api/public/oauth/token', [

        $response = $http->post(url("/oauth/token"), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '2',
                'client_secret' => 'zDb3uiJo28YRI8wnzfSYH58rV5onW6cYOPV6OX05',
                'username' =>$request->email,
                'password' =>$request->password,
                'scope' => '',
            ],
        ]);

        return json_decode((string) $response->getBody(), true);

    }

    public function login(Request  $request){
        $validatedData = $request->validate([

            'email' => 'required',
            'password' => 'required',
        ]);

        $user =  User::where('email',$request->email)->first();

        if($user){
            if(Hash::check($request->password, $user->password)){

                $http = new Client;

                $response = $http->post(url('oauth/token'), [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => '2',
                        'client_secret' => 'zDb3uiJo28YRI8wnzfSYH58rV5onW6cYOPV6OX05',
                        'username' =>$request->email,
                        'password' =>$request->password,
                        'scope' => '',
                    ],
                ]);
                return response()->json([
                    'status' => 'success',
                    'data' => json_decode((string) $response->getBody(), true)
                ]);
                // return json_decode((string) $response->getBody(), true);
              
            }
            else{
                return response()->json([
                   'error' => 'Password not match'
                ]);
            }
        }
        else{
            return response()->json([
                'error' => 'Data not found'
            ]);
        }

        return response()->json([
            'data' => $user
        ]);
    }

    public function authuser(Request $request){
        $http = new Client;

        $response = $http->request('GET', url("/api/user"), [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$request->accessToken,
            ],
        ]);

         return response()->json([
            'status' => 'success',
                'data' => json_decode((string) $response->getBody(), true)
            ]);
    }


   


}
