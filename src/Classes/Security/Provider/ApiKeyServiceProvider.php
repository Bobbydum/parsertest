<?php

namespace Rest\Classes\Security\Provider;

use Rest\Classes\Security\ApiKeyAuthenticator;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\SimpleAuthenticationProvider;
use Symfony\Component\Security\Http\Firewall\SimplePreAuthenticationListener;

/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 3/10/16
 * Time: 11:05 AM
 */
class ApiKeyServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $app['security.apikey.authenticator'] = $app->protect(function() use ($app) {
            return new ApiKeyAuthenticator();
        });

        $app['security.authentication_listener.factory.apikey'] = $app->protect(function($name, $options) use ($app) {

            $app['security.authentication_provider.'.$name.'.apikey'] = $app->share(function() use ($app, $name) {
                return new SimpleAuthenticationProvider(
                    $app['security.apikey.authenticator'](),
                    $app['security.user_provider.apikey'](),
                    $name
                );
            });

            $app['security.authentication_listener.' . $name . '.apikey'] = $app->share(function () use ($app, $name, $options) {
                return new SimplePreAuthenticationListener(
                    $app['security'],
                    $app['security.authentication_manager'],
                    $name,
                    $app['security.apikey.authenticator']()
                );
            });

            return array(
                'security.authentication_provider.'.$name.'.apikey',
                'security.authentication_listener.'.$name.'.apikey',
                null,       // entrypoint
                'pre_auth'  // position of the listener in the stack
            );

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