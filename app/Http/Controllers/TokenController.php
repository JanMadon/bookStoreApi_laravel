<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    public function create(array $credentionals, string $privileges)
    {

        if (Auth::attempt($credentionals)) {
            /** @var \App\Models\MyUserModel $user **/
            $user = Auth::user();

            if ($privileges === 'user') {
            } elseif ($privileges === 'admin') {
            }

            switch ($privileges) {
                case 'user':
                    $token = $user->createToken($user->name);
                    break;
                case 'admin':
                    $token = $user->createToken(
                        $user->name,
                        ['create', 'update', 'delete']
                    );
                    break;
                default:
                    return ['error' => 'Something went wrong'];
            }


            return ['token:' => $token->plainTextToken];
        } else {
            return ['error' => 'Something went wrong'];
        }
    }
}
