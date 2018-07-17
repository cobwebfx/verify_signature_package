<?php namespace Tests\Unit\Services;

use Cobwebfx\VerifySignature\Exceptions\InvalidPayloadException;
use Cobwebfx\VerifySignature\Exceptions\TokenKeyException;
use Cobwebfx\VerifySignature\Services\VerifySignatureService;
use Tests\Unit\BaseTestClass;

/**
 * Class VerifySignatureServiceTest
 * @package Tests\Unit\Services
 */
class VerifySignatureServiceTest extends BaseTestClass
{
	public function testEncode()
	{
		$this->assertNull($this->token);
		$this->encodePayloadToToken('expectedKey');
		$expectedToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjbGllbnQiOiJ2aXZhc3RyZWV0LXVrIiwicGFyYW0xIjoiVGhpcyBpcyB0aGUgZmlyc3QgcGFyYW0iLCJwYXJhbTIiOiJUaGlzIGlzIHRoZSBzZWNvbmQgcGFyYW0ifQ.Is9zmsRBFpK8j6n1UzzTF9HlRt5isokQ9zTyOt3g92Y';
		$this->assertEquals($expectedToken, $this->token);
	}

    /**
     * @throws InvalidPayloadException
     */
    public function testGetIdFromUnverifiedToken()
    {
		$this->encodePayloadToToken('expectedKey');
		$id = VerifySignatureService::getIdFromUnverifiedToken($this->token);
        $this->assertEquals(self::KEY_VALUE, $id);
    }

    /**
     * @throws TokenKeyException
     * @throws InvalidPayloadException
     */
	public function testDecodeAndVerify()
	{
		$this->encodePayloadToToken('expectedKey');
		$decodedToken = VerifySignatureService::decodeAndVerify($this->token);
		$expectedResponse = (Object)array_merge([self::KEY_IDENTIFIER => self::KEY_VALUE], self::PAYLOAD);
		$this->assertEquals($expectedResponse, $decodedToken);
	}

    /**
     * @throws TokenKeyException
     * @throws InvalidPayloadException
     */
	public function testTokenKeyExceptionException()
	{
		$this->expectException(TokenKeyException::class);
		$this->encodePayloadToToken('unexpectedKey');
		VerifySignatureService::decodeAndVerify($this->token);
	}

    /**
     * @throws InvalidPayloadException
     */
    public function testInvalidPayloadExceptionException()
    {
        $this->expectException(InvalidPayloadException::class);
        VerifySignatureService::getIdFromUnverifiedToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.yJjbGllbnQiOiJ2aXZhc3RyZWV0LXVrIiwicGFyYW0xIjoiVGhpcyBpcyB0aGUgZmlyc3QgcGFyYW0iLCJwYXJhbTIiOiJUaGlzIGlzIHRoZSBzZWNvbmQgcGFyYW0ifQ.Is9zmsRBFpK8j6n1UzzTF9HlRt5isokQ9zTyOt3g92Y');
    }
}
