<?php

namespace Modules\TaskManagement\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Common\Utilities\ResponseStatus;

class TaskRequestTransformer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $json = @json_decode($request->get('data'), true);
        if ($json == null)
            return response()->json(["error" => "Invalid Json", "code" => Response::HTTP_BAD_REQUEST,
                "status" => ResponseStatus::ERROR], Response::HTTP_BAD_REQUEST);

        $request->request->add($json);
        return $next($request);
    }
}
