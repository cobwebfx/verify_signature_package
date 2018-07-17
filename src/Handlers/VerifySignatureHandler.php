<?php namespace Cobwebfx\VerifySignature\Handlers;

use Cobwebfx\VerifySignature\Services\VerifySignatureService;
use Cobwebfx\VerifySignature\Validators\TokenValidator;
use Exception;

/**
 * Class VerifySignatureHandler
 * @package Cobwebfx\VerifySignature\Handler
 */
class VerifySignatureHandler
{
    /**
     * @param $request
     * @param $queryParameter
     * @throws Exception
     * @throws \Cobwebfx\VerifySignature\Exceptions\AccessDeniedHttpException
     * @throws \Cobwebfx\VerifySignature\Exceptions\TokenKeyException
     */
    public static function decodeAndVerifyFromQueryParameter($request, $queryParameter)
    {
        self::decodeAndVerify($request->get($queryParameter), $request);
    }

    /**
     * @param $request
     * @throws \Cobwebfx\VerifySignature\Exceptions\AccessDeniedHttpException
     * @throws \Cobwebfx\VerifySignature\Exceptions\TokenKeyException
     * @throws Exception
     */
    public static function decodeAndVerifyFromBearerToken($request)
    {
        self::decodeAndVerify($request->bearerToken(), $request);
    }

    /**
     * @param $bearerToken
     * @param $request
     * @throws Exception
     * @throws \Cobwebfx\VerifySignature\Exceptions\AccessDeniedHttpException
     * @throws \Cobwebfx\VerifySignature\Exceptions\TokenKeyException
     */
    private static function decodeAndVerify($bearerToken, $request)
    {
        TokenValidator::validateBearerToken($bearerToken);
        $decodedToken = VerifySignatureService::decodeAndVerify($bearerToken);
        self::transferDecodedTokenIntoRequest($decodedToken, $request, $bearerToken);
    }
    
    /**
     * @param $decodedToken
     * @param $request
     * @param string $bearerToken
     * @throws Exception
     */
    private static function transferDecodedTokenIntoRequest($decodedToken, $request, $bearerToken)
    {
        foreach ($decodedToken as $key => $value) {
            $request->request->set($key, $value);
        }
        $request->request->set('bearerToken', $bearerToken);
    }
}
