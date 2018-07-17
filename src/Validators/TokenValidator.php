<?php namespace Cobwebfx\VerifySignature\Validators;

use Cobwebfx\VerifySignature\Exceptions\AccessDeniedHttpException;

/**
 * Class TokenValidator
 * @package Cobwebfx\VerifySignature\Validators
 */
class TokenValidator
{
	/**
	 * @param $bearerToken
	 * @return bool
	 * @throws AccessDeniedHttpException
	 */
	public static function validateBearerToken($bearerToken)
	{
		if (!$bearerToken) {
			throw new AccessDeniedHttpException('Invalid Token');
		}
		return true;
	}
}