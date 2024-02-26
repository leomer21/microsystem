<?php
namespace App\Http\Middleware;

use Closure;
use Config;

use Illuminate\Session\Middleware\StartSession as BaseStartSession;

class StartSessionMiddleware extends BaseStartSession
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        if($request->is('mikrotikapi') || $request->is('api')) {
            Config::set('session.driver', 'array');
        }

        return parent::handle($request, $next);
    }
}