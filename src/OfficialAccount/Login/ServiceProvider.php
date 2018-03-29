<?php
/**
 * Date: 2018/3/29
 * Time: 16:00
 */

namespace JinWeChat\OfficialAccount\Login;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    protected $loginUrl = "https://mp.weixin.qq.com/cgi-bin/bizlogin?action=startlogin";

    public function register(Container $pimple)
    {
        $app['login'] = function ($app) {
            return [
                'url' => $this->loginUrl,
                'username' => $app['config']['username'],
                'pwd' => md5($app['config']['password']),
                'imgcode' => '',
                'f' => ' json',
                'userlang' => 'zh_CN',
                'token' => '',
                'lang' => 'zh_CN',
                'ajax' => '1',
            ];
        };
    }
}