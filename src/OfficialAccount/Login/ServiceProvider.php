<?php
/**
 * Date: 2018/3/29
 * Time: 16:00.
 */

namespace JinWeChat\OfficialAccount\Login;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['login'] = function ($app) {
            return new Client($app);
        };
    }
}
