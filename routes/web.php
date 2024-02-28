<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    info('created customer');

    return view('welcome');
});



Route::get('/gettoken', function() {

    $credentionals = [
        'email' => 'user@test.pl',
        'password' => 'password'
    ];

    if(!Auth::attempt($credentionals)) { //TODO sprawdz do czego jest attempt
        $user = new User();
        
        $user->name = 'Zbyszek';
        $user->email = $credentionals['email'];
        $user->password = Hash::make($credentionals['password']);
        $user->save();
        dd(Auth::attempt($credentionals));
    }

    if(Auth::attempt($credentionals)){
        /** @var \App\Models\MyUserModel $user **/
        $user = Auth::user();
        $adminToken = $user->createToken('admin-token', ['read']);  //['create', 'update', 'delete']

        return [
            'user' => 'user -> read only',
            'user' => $adminToken->plainTextToken
        ];


    }


});