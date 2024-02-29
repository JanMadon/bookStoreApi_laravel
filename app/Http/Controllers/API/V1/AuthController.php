<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TokenController;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    private TokenController $token;

    public function __construct(TokenController $token)
    {
        $this->token = $token;
    }

    public function create(CreateUserRequest $request)
    {
        $credentionals = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!Auth::attempt($credentionals)) {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);
        } else {
            return response()->json(['mesage' => 'User alredy exists!']);
        }

        return $this->token->create($credentionals, 'user');
    }

    public function createNewToken($request)
    {
        $credentionals = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentionals)){
            return $this->token->create($credentionals, 'user');

        }
    }
}
