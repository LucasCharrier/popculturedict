<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\User as UserResource;

class SignUpController extends Controller
{
    
    public function __invoke(Request $request) {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $user = User::create(request(['name', 'email', 'password']));
        $token = auth()->attempt($request->only('email', 'password'));
        if (!$token) {
            return response(null, 401);
        }

        return response()->json(compact('token'));
    }
}
