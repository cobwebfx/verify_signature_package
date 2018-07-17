<?php namespace Tests\Unit\Services;

use Cobwebfx\VerifySignature\Exceptions\AccessDeniedHttpException;
use Tests\Unit\BaseTestClass;
use Cobwebfx\VerifySignature\Validators\TokenValidator;

/**
 * Class TokenValidatorTest
 * @package Tests\Unit\Services
 */
class TokenValidatorTest extends BaseTestClass
{
	/**
	 * @throws AccessDeniedHttpException
	 */
	public function testValidateBearerToken()
	{
		$token = TokenValidator::validateBearerToken('token');
		$this->assertTrue($token);
	}
	
	/**
	 * @throws AccessDeniedHttpException
	 */
	public function testAccessDeniedHttpException()
	{
		$this->expectException(AccessDeniedHttpException::class);
		TokenValidator::validateBearerToken(null);
	}
}
