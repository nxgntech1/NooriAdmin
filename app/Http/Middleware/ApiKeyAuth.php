<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\Language;
use App\Http\Controllers\BaseApiController;
use Symfony\Component\HttpFoundation\Response;
use Request;
use DB;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */

    public function handle($request, Closure $next, $guard = null)
    {
        if(empty($request->header('apikey')) || $request->header('apikey')!=env('APP_KEY')){
            return BaseApiController::errorResponse([],'Unauthorized',[],Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}