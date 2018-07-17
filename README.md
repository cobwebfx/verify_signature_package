# Verify Signature

[![Software License][ico-license]](LICENSE.md)

**Verify Signature** allows services to communicate with each other, and have their identities verified by using a shared key.

Services would need to share a key and algorithm that they would then use to encode and sign the data that they wish to share with each other.  

Service1 would use **Verify Signature** to encode data into an outgoing token. 

Service2 would use **Verify Signature** to decode the data and verify that the incoming token was signed with the expected key. 

Dependencies:
* **Verify Signature** uses Json Web Tokens 
* The below instructions assume you're utilising the Laravel Framework.



*Please note that Json Web Tokens can be decoded by anyone. This package provides a way of verifying the source of the token. Please don't pass sensitive data.*


## Structure


```
src/
tests/
vendor/
```


## Install

Add a repo to the repository section in your composer.json

``` bash
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/cobwebfx/verify_signature_package"
    }
  ],
```

Require the package in your composer.json
``` bash
  "require": {
    ...
    "cobwebfx/verify-signature": "dev-master"
  },
```

Update composer in your terminal
``` bash
  $ composer update
```

## Configure

Add the Service Provider to your /config/app.php

``` php
'providers' => [
    ...
    Cobwebfx\VerifySignature\Providers\VerifySignatureServiceProvider::class,
],
```

Run Vendor:Publish to generate your config file
``` bash
  $ php artisan vendor:publish
```

Select Verify Signature from the list. In the below case, you would type "1"

``` bash
 Which provider or tag's files would you like to publish?:
  [0] Publish files from all providers and tags listed below
  [1] Provider: Cobwebfx\VerifySignature\Providers\VerifySignatureServiceProvider
```

Edit your configuration in /config/verify-signature.php. Your keyIdentifier is the identifier for the key that verify-signature will use to decode the token.
Also add your keys into the key array.  Please use the same format as the example.
``` php
return [
    'keyIdentifier' => 'id',
    'keys' => [
        'service-name' => [
            'key'    => env('VERIFY_SIG.KEYS.service-name.KEY'),
            'alg'    => env('VERIFY_SIG.KEYS.service-name.ALG'),
        ],
    ],
];
```

Edit your .env file. The below is a continuation of the above example. 
The algorithm and shared key are specific to the service you're contacting.

``` bash
VERIFY_SIG.KEYS.service-name.LOCALE=uk
VERIFY_SIG.KEYS.service-name.KEY=sharedKey
VERIFY_SIG.KEYS.service-name.ALG=ChosenAlgorithm
```

## Usage
To encode a payload. 

*Note. Do not use 'id' as a key in your payload as it is a reserved key and will be replaced*.
``` php
$id = 'service-name';
$payload = [
    'param1' =>'This is the first param',
    'param2' =>'This is the second param',
];
$bearerToken = VerifySignatureService::encode($id, $payload);
```
To send your new bearer token to another service. Add your token to the authorisation header
``` bash
['Authorization' => 'Bearer {$bearerToken}']
```

To verify a token, you can do it manually by hitting the validateBearerToken and decodeAndVerify Service methods
``` php
$bearerToken = $request->bearerToken();
TokenValidator::validateBearerToken($bearerToken);
$decodedToken = VerifySignatureService::decodeAndVerify($bearerToken);
```

Alternatively, **Verify Signature** includes plug and play middleware that verifies all requests sent through it. To verify all requests, you can create a group and add the Middleware.

If you are using the bearerToken in the header use this approach
``` php
$router->group(
    [
        'middleware' => [
            'verifySignature',
        ],
    ], 
    function () use ($router) {
        // All routes go here
        ...
        $router->get('/', [
            'uses' => 'Controller@get',
        ]);
        ...
    }
);
```


If you are using a query parameter as the source of the token
``` php
$router->group(
    [
        'middleware' => [
            'verifySignatureWithQueryParameter:queryParameterName',
        ],
    ], 
    function () use ($router) {
        // All routes go here
        ...
        $router->get('/', [
            'uses' => 'Controller@get',
        ]);
        ...
    }
);
```

To validate only one route, you can simply bolt it on
``` php
Route::get('/', 'Controller@get')->middleware('verifySignature');
Route::get('/', 'Controller@get')->middleware('verifySignatureWithQueryParameter:queryParameterName');
```
## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ phpunit tests
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email jamie.potter@dm-companies.com instead of using the issue tracker.

## Credits

- [Cobwebfx][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/cobwebfx/verifysignature.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/cobwebfx/verifysignature/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/cobwebfx/verifysignature.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/cobwebfx/verifysignature.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/cobwebfx/verifysignature.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/cobwebfx/verifysignature
[link-travis]: https://travis-ci.org/cobwebfx/verifysignature
[link-scrutinizer]: https://scrutinizer-ci.com/g/cobwebfx/verifysignature/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/cobwebfx/verifysignature
[link-downloads]: https://packagist.org/packages/cobwebfx/verifysignature
[link-author]: https://github.com/cobwebfx
[link-contributors]: ../../contributors
