<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ControllerLoginToken;
use App\Models\User;
use Laravel\Fortify\Fortify;
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
    return redirect('/login');
});
Route::get('login_with_token/{id}', function($id){
    return view('auth.login_token')->with('id', $id);
});

Route::post('/login_with_token', function($request){
    
    /* Fortify::authenticateUsing(
        function ($request) {
            $user = User::find($id);
            $validated = false;
            if($request->token == $user->token)
            {
                Auth::login($user);
                DB::table('users')
                ->where('username', $user->username)
                ->update(['verify' => 0]);
                
                DB::table('users')
                ->where('username', $user->username)
                ->update(['browser' => $request->header('User-Agent')]);

                DB::table('users')
                ->where('username', $user->username)
                ->update(['ip_address' => $request->ip()]);
                $validated = true;
            } 
            return $validated ? Auth::getLastAttempted() : null;
        }
        ); */
        dd($request->input());
})->name('login_token');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
