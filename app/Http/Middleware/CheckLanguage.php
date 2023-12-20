<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CheckLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    { 
        //check if the language session variable exist
        if(session()->has('language')){
            //change the language
            if(App::getLocale() != session()->get('language')) {
                App::setLocale(session()->get('language'));
            } else{
                //set spanish language by default
                session()->put('language', 'es');
                App::setLocale('es');
            }
        }
        return $next($request);
    }
}
