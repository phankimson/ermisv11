<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Routing\Middleware;
use View;

class Localization
{
  protected $app;
  protected $redirector;
  protected $request;
  public function __construct(Application $app, Redirector $redirector, Request $request) {
     $this->app = $app;
     $this->redirector = $redirector;
     $this->request = $request;
 }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if($this->request->method() == "GET"){
        // Make sure current locale exists.
        $locale = $request->segment(1);
        if(!array_search($locale, $this->app->config->get('app.locales_skip'))){
          if ( ! array_key_exists($locale, $this->app->config->get('app.locales'))) {
              $segments = $request->segments();
              $segments[0] = $this->app->config->get('app.fallback_locale');
              $segments[1] = $locale;
              return $this->redirector->to(implode('/', $segments));
          }

          $this->app->setLocale($locale);
          View::share ('lang', $locale );
        }
      }
      return $next($request);
    }
}
