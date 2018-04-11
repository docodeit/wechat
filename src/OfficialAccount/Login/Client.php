<?php

/*
 * This file is part of the docodeit/wechat.
 *
 * (c) docodeit <lqbzdyj@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JinWeChat\OfficialAccount\Login;

use JinWeChat\Kernel\BaseClient;

class Client extends BaseClient
{
    public function init()
    {
        return $this->httpGet('');
    }

    public function startLogin()
    {
        $this->init();
        $params = [
            'username' => $this->app['config']['username'],
            'pwd' => md5($this->app['config']['password']),
            'imgcode' => '',
            'f' => 'json',
            'userlang' => 'zh_CN',
            'token' => '',
            'lang' => 'zh_CN',
            'ajax' => '1',
        ];

        return $this->httpPost('cgi-bin/bizlogin?action=startlogin', $params);
    }

    public function redirect()
    {
        $url = 'cgi-bin/bizlogin?action=validate&lang=zh_CN&account='.$this->app['config']['username'];
        $this->httpGet($url);
    }

    public function getQrCode()
    {
        return $this->httpGet('cgi-bin/loginqrcode?action=getqrcode&param=4300&rd=855');
    }
}
