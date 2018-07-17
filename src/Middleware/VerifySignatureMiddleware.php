<?php namespace Cobwebfx\VerifySignature\Middleware;

use Cobwebfx\VerifySignature\Handlers\VerifySignatureHandler;
use Closure;

/**
 * Class VerifySignature
 * @package App\Http\Middleware
 */
class VerifySignatureMiddleware
{

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     * @throws \Cobwebfx\VerifySignature\Exceptions\AccessDeniedHttpException
     * @throws \Cobwebfx\VerifySignature\Exceptions\TokenKeyException
     */
    public function handle($request, Closure $next)
    {
        VerifySignatureHandler::decodeAndVerifyFromBearerToken($request);
        return $next($request);
    }
}
