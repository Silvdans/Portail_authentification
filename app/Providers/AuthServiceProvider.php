<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\MailController;
use App\Mail\MailContact;
use App\Models\User;

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

        Fortify::authenticateUsing(function ($request) {
            
            $validated = Auth::validate([
                'samaccountname' => $request->username,
                'password' => $request->password
            ]);
            
            if($validated)
            {   
                $user = User::where('username',$request->username) -> first();
                Mail::to("loic.delpierre16@gmail.com")->send(new MailContact());
            } 
            return $validated ? Auth::getLastAttempted() : null;
        });
    }
}
