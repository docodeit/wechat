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
    /**
     * 初始化Cookies
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function init()
    {
        return $this->httpGet('');
    }

    /**
     * 发送登陆请求
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     */
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

    /**
     * 请求调转链接，根据不同的链接判断公众号状态
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function redirect()
    {
        $url = 'cgi-bin/bizlogin?action=validate&lang=zh_CN&account=' . $this->app['config']['username'];
        $this->httpGet($url);
    }

    /**
     * 保存QrCode到指定文件
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function getQrCode()
    {
        return $this->httpGet('cgi-bin/loginqrcode?action=getqrcode&param=4300&rd=855');
    }

    /**
     * 获取二维码扫码确认状态
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function ask()
    {
        $url = 'cgi-bin/loginqrcode?action=ask&token=&lang=zh_CN&f=json&ajax=1';
        $res = $this->httpGet($url);
        return $this->judge($res);
    }

    /**
     * 判断方法
     * @param $res
     * @return bool
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function judge($res)
    {
        if (isset($res->status)) {
            switch ($res->status) {
                case 0://系统繁忙，请稍后再试
                    return false;
                case 1://未扫码
                    $this->ask();
                    break;
                case 2://已取消
                    return false;
                    break;
                case 3://超时
                    return false;
                    break;
                case 4://已确认
                    return true;
                    break;
            }
        }
        return false;
    }

    /**
     * 扫码确认后跳转请求跳转地址
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function bizlogin()
    {
        $url = 'https://mp.weixin.qq.com/cgi-bin/bizlogin?action=login';
        $this->httpGet($url);
    }
}
