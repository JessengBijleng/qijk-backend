<?php

namespace App\Http\Middleware;

use App\Actions\AnalyzeKitchenPhoto;
use App\Actions\DryRunAnalyzeKitchenPhoto;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UseDryRunKitchenAnalysis
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->isLocal() && $request->query->getBoolean('dry_run')) {
            app()->bind(AnalyzeKitchenPhoto::class, DryRunAnalyzeKitchenPhoto::class);
        }

        return $next($request);
    }
}
