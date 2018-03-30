<?php
/**
 * Date: 2018/3/29
 * Time: 16:00
 */

namespace JinWeChat\OfficialAccount\Cookies;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use GuzzleHttp\Cookie\CookieJar;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['cookies'] = function ($app) {
            $cookieArray = [];
            return new CookieJar(false, $cookieArray);
        };
    }
}