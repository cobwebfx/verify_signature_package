<?php namespace Cobwebfx\VerifySignature\Services;

use Firebase\JWT\JWT as JWTClient;
use Cobwebfx\VerifySignature\Exceptions\InvalidPayloadException;
use Cobwebfx\VerifySignature\Exceptions\TokenKeyException;

/**
 * Class VerifySignatureService
 * @package Cobwebfx\VerifySignature\Services
 */
class VerifySignatureService
{
    const BASE_PATH = 'verify-signature';
    const KEY_PATH = self::BASE_PATH . '.keys.%s';
    const KEY_IDENTIFIER_PATH = self::BASE_PATH . '.keyIdentifier';
    
    /**
     * @param $bearerToken
     * @return \StdClass
     * @throws TokenKeyException
     * @throws InvalidPayloadException
     */
    public static function decodeAndVerify($bearerToken): \StdClass
    {
        $key = self::getKeyByToken($bearerToken);
        return JWTClient::decode($bearerToken, $key['key'], [$key['alg']]);
    }
    
    /**
     * @param $bearerToken
     * @return string
     * @throws InvalidPayloadException
     */
    public static function getIdFromUnverifiedToken($bearerToken): string
    {
        $decodedId = json_decode(
            base64_decode(
                explode('.', $bearerToken)[1]
            ),
            true
        )[self::getKeyIdentifier()];
        
        if (!is_string($decodedId)) {
            throw new InvalidPayloadException("Failed to get id from payload");
        }
        
        return $decodedId;
    }
    
    /**
     * @param $id
     * @param array $payload
     * @return string
     * @throws TokenKeyException
     */
    public static function encode($id, array $payload): string
    {
        $key = self::getKeyById($id);
        $payload = array_merge([self::getKeyIdentifier() => $id], $payload);
        return JWTClient::encode($payload, $key['key'], $key['alg']);
    }
    
    /**
     * @param $bearerToken
     * @return \Illuminate\Config\Repository|mixed
     * @throws TokenKeyException
     * @throws InvalidPayloadException
     */
    private static function getKeyByToken($bearerToken)
    {
        $id = self::getIdFromUnverifiedToken($bearerToken);
        return self::getKeyById($id);
    }
    
    /**
     * @return string
     */
    private static function getKeyIdentifier(): string
    {
        return config(self::KEY_IDENTIFIER_PATH);
    }
    
    /**
     * @param $id
     * @return string
     */
    private static function setKeyConfigPath($id)
    {
        return sprintf(self::KEY_PATH, $id);
    }
    
    /**
     * @param $id
     * @return \Illuminate\Config\Repository|mixed
     * @throws TokenKeyException
     */
    private static function getKeyById($id)
    {
        $pathToKey = self::setKeyConfigPath($id);
        self::doesKeyExist($pathToKey);
        return config($pathToKey);
    }
    
    /**
     * @param $pathToKey
     * @throws TokenKeyException
     */
    private static function doesKeyExist($pathToKey)
    {
        if (config($pathToKey) === null) {
            throw new TokenKeyException('Key does not exist');
        }
    }
}
