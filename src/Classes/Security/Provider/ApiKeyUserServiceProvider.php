<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 3/10/16
 * Time: 11:40 AM
 */

namespace App\Classes\Security\Provider;


use Silex\Application;
use Silex\ServiceProviderInterface;

class ApiKeyUserServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $app['security.user_provider.apikey'] = $app->protect(function () use ($app) {
            return new ApiKeyUserProvider($app['users']);
        });
        return true;
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }
}