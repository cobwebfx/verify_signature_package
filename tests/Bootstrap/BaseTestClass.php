<?php namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Cobwebfx\VerifySignature\Services\VerifySignatureService;

class BaseTestClass extends TestCase
{
	/**
	 * @var string
	 */
	protected $token;
	const KEY_IDENTIFIER = 'client';
	const KEY_VALUE = 'cobwebfx-uk';
	const PAYLOAD = [
		'param1' =>'This is the first param',
		'param2' =>'This is the second param',
	];
	
	protected function encodePayloadToToken($type)
	{
		switch ($type) {
			case ('expectedKey'):
				$this->token = VerifySignatureService::encode(self::KEY_VALUE, self::PAYLOAD);
				break;
			case ('unexpectedKey'):
				$this->token = VerifySignatureService::encode(str_random(8), self::PAYLOAD);
				break;
		}
	}
}
