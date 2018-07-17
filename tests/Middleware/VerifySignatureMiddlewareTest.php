<?php namespace Tests\Unit\Services;

use Illuminate\Http\Request;
use Cobwebfx\VerifySignature\Middleware\VerifySignatureMiddleware;
use Tests\Unit\BaseTestClass;

/**
 * Class VerifySignatureMiddlewareTest
 * @package Tests\Unit\Services
 */
class VerifySignatureMiddlewareTest extends BaseTestClass
{
	public function testMiddleware()
	{
		$request = new Request();
		$this->encodePayloadToToken('expectedKey');

		$request->headers->set('Authorization', [
			'Bearer ' . $this->token,
		]);

		$middleware = new VerifySignatureMiddleware;
		$middleware->handle($request, function () {});
		
		foreach (self::PAYLOAD as $key => $item) {
			$this->assertEquals($item, $request->get($key));
		}
		$this->assertEquals(self::KEY_VALUE, $request->get(self::KEY_IDENTIFIER));
	}
	
}


