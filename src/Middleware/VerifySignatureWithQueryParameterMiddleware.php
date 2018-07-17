<?php namespace Cobwebfx\VerifySignature\Middleware;

use Cobwebfx\VerifySignature\Handlers\VerifySignatureHandler;
use Closure;

/**
 * Class VerifySignatureWithQueryPAarameterMiddleware
 * @package App\Http\Middleware
 */
class VerifySignatureWithQueryParameterMiddleware
{

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     * @throws \Cobwebfx\VerifySignature\Exceptions\AccessDeniedHttpException
     * @throws \Cobwebfx\VerifySignature\Exceptions\TokenKeyException
     */
    public function handle($request, Closure $next, $queryParameter)
    {
        VerifySignatureHandler::decodeAndVerifyFromQueryParameter($request, $queryParameter);
        return $next($request);
    }
}
