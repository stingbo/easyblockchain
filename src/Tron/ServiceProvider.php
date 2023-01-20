<?php

namespace EasyBlockchain\Tron;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 */
class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['client'] = function ($app) {
            return new Client($app);
        };
    }
}
