<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ]); 
        }

        $input = $req->input();
        
                    
        $credentials = ['email' => $input['email'], 'password' => $input['password']];
        if (Auth::attempt($credentials)) {
            $ip = $req->ip();
            $sessionId = Session::getId();    
            $user = Auth::user();
            if($user->ip == $ip){
                if($user->session){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Another session is active from this IP. Do you want to continue with this new session and close the old one?',
                        'continue_url' => url('api/session-closed/'.$user->id),
                    ]);
                }else{
                    $data  =User::find($user->id);
                    $data->session = $sessionId;
                    $data->save();
        
                    return response()->json([
                        'status' => 'success',
                        'token' => $sessionId,
                        'user' => $user,
                    ]);
                }
            }

        } else {
            return response()->json([
                'status' => 'error',
                'msg' => 'Invalid credentials',
            ]);
        }
                  
        
    }

    public function oldSessionExpried($id) {

        $data = User::find($id);
        $data->session = '';
        if($data->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'Old session closed. You are now logged in with the new session.',
            ]);
        }

    }

}
