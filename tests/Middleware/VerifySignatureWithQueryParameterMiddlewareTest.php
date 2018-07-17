<?php namespace Tests\Unit\Services;

use Illuminate\Http\Request;
use Tests\Unit\BaseTestClass;
use Cobwebfx\VerifySignature\Middleware\VerifySignatureWithQueryParameterMiddleware;

/**
 * Class VerifySignatureWithQueryParameterMiddlewareTest
 * @package Tests\Unit\Services
 */
class VerifySignatureWithQueryParameterMiddlewareTest extends BaseTestClass
{
	public function testMiddleware()
	{
		$request = new Request();
		$this->encodePayloadToToken('expectedKey');

		$request->request->set('q', $this->token);

		$middleware = new VerifySignatureWithQueryParameterMiddleware();
		$middleware->handle($request, function () {}, 'q');
		
		foreach (self::PAYLOAD as $key => $item) {
			$this->assertEquals($item, $request->get($key));
		}
		$this->assertEquals(self::KEY_VALUE, $request->get(self::KEY_IDENTIFIER));
	}
	
}


