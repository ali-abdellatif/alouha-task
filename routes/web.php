<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Notification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $user = User::first();
    $user->notify(new \App\Notifications\newPost());

//    Notification::route('mail','aligamal875@gmail.com')
//        ->notify(new \App\Notifications\newPost());

    Notification::route('mail', [
        'aligamal875@gmail.com' => 'Barrett Blair',
    ])->notify(new \App\Notifications\newPost());
    return view('welcome');
});
