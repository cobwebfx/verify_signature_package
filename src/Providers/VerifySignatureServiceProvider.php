<?php namespace Cobwebfx\VerifySignature\Providers;

use Illuminate\Support\ServiceProvider;
use Cobwebfx\VerifySignature\Middleware\VerifySignatureMiddleware;
use Cobwebfx\VerifySignature\Middleware\VerifySignatureWithQueryParameterMiddleware;

/**
 * Class VerifySignatureServiceProvider
 * @package Cobwebfx\VerifySignature\Providers
 */
class VerifySignatureServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['router']->aliasMiddleware('verifySignature', VerifySignatureMiddleware::class);
        $this->app['router']->aliasMiddleware(
            'verifySignatureWithQueryParameter',
            VerifySignatureWithQueryParameterMiddleware::class
        );
    }
    
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../Config/VerifySignature.php' => config_path('verify-signature.php'),
        ]);
    }
}
