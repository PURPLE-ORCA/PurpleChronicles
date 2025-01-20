<?php

namespace App\Providers;

use App\Models\Comment;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
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

        // Define an 'admin' gate
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        // Define an 'author' gate
        Gate::define('author', function (User $user) {
            return $user->role === 'author';
        });

        Gate::define('update-comment', function (User $user, Comment $comment) {
            // Only admins or the comment author can update the comment
            return $user->role === 'admin' || $user->id === $comment->user_id;
        });
    }
}