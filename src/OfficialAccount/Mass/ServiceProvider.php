<?php
/**
 * Date: 2018/3/29
 * Time: 16:00
 */

namespace JinWeChat\OfficialAccount\Mass;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['mass'] = function ($app) {
            return new Client($app);
        };
    }
}