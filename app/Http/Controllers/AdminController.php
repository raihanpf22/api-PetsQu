<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exception\JWTException;


class AdminController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            
            if(! $token = auth('admin-api')->attempt($credentials))
            {
                return response()->json(['error' => 'Invalid Credentials !'], 400);
            }

        } catch (JWTException $e) {
            
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'admin_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admin',
            'telp' => 'required|numeric|min:12',
            'password' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $admin = Admin::create([
            'admin_name' => $request->get('admin_name'),
            'email' => $request->get('email'),
            'telp' => $request->get('telp'),
            'password' => Hash::make($request->get('password'))
        ]);
        $token = JWTAuth::fromUser($admin);
        $data = [
            'messages'=>'Successfullly Created Account Admin',
            'data'=>[
                'admin_name'=>$admin->admin_name,
                'email'=>$admin->email,
                'telp'=>$admin->telp,
                'password'=>'Password Successfully Created !'
            ],
            'token'=>'token Successfully created !' 

        ];

        return response()->json($data, 201);
    }

    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json(['messages'=>'Admin Successfully Logout', 'status'=>200], 200);
    }

    public function index()
    {
        $admin = Admin::all('admin_name','email','telp');
        $data = [
            'messages'=>'Get All Data Admins',
            'data'=>$admin
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);

        $admin -> admin_name = $request->input('admin_name');
        $admin -> email = $request->input('email');
        $admin -> telp = $request->input('telp');
        $admin -> password = Hash::make($request->input('password'));

        $admin->update();

        $data = [
            'messages'=>'Account Admin Updated',
            'data'=>[
                'admin_name'=>$admin->admin_name,
                'email'=>$admin->email,
                'telp'=>$admin->telp
            ]
            ];
            return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $admin = Admin::find($id);
        $admin->delete();
        $data = [
            'messages'=>'Account Admin Deleted'
        ];

        return response()->json($data, 200);
    }
}
