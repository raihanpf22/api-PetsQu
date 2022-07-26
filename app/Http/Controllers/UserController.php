<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            
            if(! $token = JWTAuth::attempt($credentials))
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
        $validator = Validator::make($request -> all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required',
            'postal_code' => 'required|numeric|min:5',
            'telp' => 'required|numeric|min:12',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            
            return response()->json($validator ->errors()->toJson(), 400);
        }
        
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request ->get('email'),
            'address' => $request ->get('address'),
            'postal_code' => $request->get('postal_code'),
            'telp' => $request->get('telp'),
            'password' => Hash::make($request->get('password'))
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    public function logout(Request $request)
    {
       auth()->logout();

       return response()->json(['message' => 'User successfully signed out']);
    }

    public function index()
    {
        $data = User::all();

        return response()->json(["messages"=>"Get All Users", "status"=>200, 'data'=>compact('data')], 200);
    }

    public function show($id)
    {
        $data = User::find($id);

        return response()->json(["messages"=>"Get User By Id", "status"=>200, 'data'=>compact('data')], 200);
    }


    public function update(Request $request, $id)
    {
        $users = User::find($id);

        $users -> name = $request->input('name');
        $users ->email = $request->input('email');
        $users ->address = $request->input('address');
        $users ->postal_code = $request->input('postal_code');
        $users ->telp = $request->input('telp');
        $users ->password = Hash::make($request->input('password'));

        $users->update();

        return response()->json(['messages'=>"Updated User Successfullt", "status"=>200, "data"=>$users], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['messages'=>'Deleted User Successfully', 'status'=>200], 200);
    }

    
}
