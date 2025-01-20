<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\TrustHosts;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\ValidateSignature;
use App\Http\Middleware\CheckRole;
use Illuminate\Auth\Middleware\Authenticate as MiddlewareAuthenticate;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated as MiddlewareRedirectIfAuthenticated;
use Illuminate\Cookie\Middleware\EncryptCookies as MiddlewareEncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as MiddlewarePreventRequestsDuringMaintenance;
use Illuminate\Foundation\Http\Middleware\TrimStrings as MiddlewareTrimStrings;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as MiddlewareVerifyCsrfToken;
use Illuminate\Http\Middleware\TrustProxies as MiddlewareTrustProxies;
use Illuminate\Routing\Middleware\ValidateSignature as MiddlewareValidateSignature;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        MiddlewareTrustProxies::class,
        HandleCors::class,
        MiddlewarePreventRequestsDuringMaintenance::class,
        ValidatePostSize::class,
        MiddlewareTrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            MiddlewareEncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            MiddlewareVerifyCsrfToken::class,
            SubstituteBindings::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ],

        'api' => [
            ThrottleRequests::class.':api',
            SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => MiddlewareAuthenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'auth.session' => AuthenticateSession::class,
        'cache.headers' => SetCacheHeaders::class,
        'can' => Authorize::class,
        'guest' => MiddlewareRedirectIfAuthenticated::class,
        'password.confirm' => RequirePassword::class,
        'precognitive' => HandlePrecognitiveRequests::class,
        'signed' => MiddlewareValidateSignature::class,
        'throttle' => ThrottleRequests::class,
        'verified' => EnsureEmailIsVerified::class,
        'checkRole' => \App\Http\Middleware\CheckRole::class,
    ];
}