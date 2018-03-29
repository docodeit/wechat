<?php
/**
 * Date: 2018/3/29
 * Time: 16:00
 */
namespace JinWeChat\OfficialAccount\Cookies;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface {
    public function register(Container $pimple)
    {
        $app['cookies'] = function ($app) {

        };
    }
}