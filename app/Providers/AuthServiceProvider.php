<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\MailController;
use App\Mail\MailContact;
use App\Mail\MailContactToken;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Fortify::authenticateUsing(
            function ($request) {
            $validated = false;
            $user = User::where('username',$request->username) -> first();
            if(!$user){
                $validated = Auth::validate([
                    'samaccountname' => $request->username,
                    'password' => $request->password
                ]);
                DB::table('users')
                    ->where('username', $request->username)
                    ->update(['ip_address' => $request->ip()]);

                DB::table('users')
                ->where('username', $request->username)
                ->update(['browser' => $request->header('User-Agent')]);
            }
            else{
                if($user->verify == 0)
                {
                    $validated = Auth::validate([
                        'samaccountname' => $request->username,
                        'password' => $request->password
                    ]);
                }
                if($user->verify == 1)
                    {
                        if($request->password == $user->token)
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
                            return Auth::getLastAttempted();
                        }
                    }
                if($validated)
                {   
                    if($request->header('User-Agent') != $user->browser && $user->browser != null)
                    {
                        Mail::to($user->email)->send(new MailContactToken($user));
                        $validated = false;
                        DB::table('users')
                        ->where('username', $request->username)
                        ->update(['verify' => 1]);
                    }
                    if($user->browser == null){
                        DB::table('users')
                        ->where('username', $request->username)
                        ->update(['browser' => $request->header('User-Agent')]);
                    }
                    if($user->ip_address != $request->ip() && $user->ip_address != null)
                    {
                        DB::table('users')
                        ->where('username',$request->username)
                        ->update(['ip_address' => $request->ip()]);

                        Mail::to($user->email)->send(new MailContact());
                    }
                    if($user->ip_address == null){
                        DB::table('users')
                        ->where('username', $request->username)
                        ->update(['ip_address' => $request->ip()]);
                    }
                } 
            }
            return $validated ? Auth::getLastAttempted() : null;
        });
    }
}
