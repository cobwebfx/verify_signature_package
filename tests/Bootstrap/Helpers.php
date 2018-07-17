<?php

/**
 * This replaces Laravel's config helper when testing
 */
if (! function_exists('config')) {
	function config($param)
	{
		switch ($param) {
			case('verify-signature.keys.cobwebfx-uk'):
				return [
					'locale' => 'en',
					'key'    => 'sharedKey',
					'alg'    => 'HS256',
				];
				break;
		}
	}
}
