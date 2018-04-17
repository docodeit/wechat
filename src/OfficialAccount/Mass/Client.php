<?php

/*
 * This file is part of the docodeit/wechat.
 *
 * (c) docodeit <lqbzdyj@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JinWeChat\OfficialAccount\Mass;

use JinWeChat\Kernel\BaseClient;
use JinWeChat\Kernel\Console\QrCode;

class Client extends BaseClient
{
    /**
     * 获取msgid
     * 通过页面抓取，正则匹配.
     *
     * @return string
     */
    public function msgid()
    {
        $query = [
            't' => 'mass/send',
            'lang' => 'zh_CN',
        ];
        $url = 'cgi-bin/masssendpage';
        $res = $this->httpGet($url, $query);
        $operation_seq = false;
        $matchRes = preg_match('/operation_seq:[\s]+\"(.*?)\"/i', $res, $match);
        if ($matchRes) {
            $operation_seq = $match[1];
        }

        return $operation_seq;
    }

    /**
     * 获取ticket.
     */
    public function ticket()
    {
        $options = [
            'token' => $this->token,
            'lang' => 'zh_CN',
            'f' => 'json',
            'ajax' => '1',
            'random' => $this->getMillisecond(),
            'action' => 'get_ticket',
        ];
        $url = 'misc/safeassistant?lang=zh_CN';
        $res = $this->httpPost($url, $options);
        $ticket = false;
        if ($res) {
            if ($json = $this->jsonDecode($res)) {
                $ticket = $json->ticket;
            }
        }

        return $ticket;
    }

    /**
     * 获取uuid.
     *
     * @param $ticket
     *
     * @return string|bool
     */
    public function uuid($ticket)
    {
        $options = [
            'token' => $this->token,
            'lang' => 'zh_CN',
            'f' => 'json',
            'ajax' => '1',
            'random' => $this->getMillisecond(),
            'state' => '0',
            'login_type' => 'safe_center',
            'type' => 'json',
            'ticket' => $ticket,
        ];
        $uuid = false;
        $url = 'safe/safeqrconnect?lang=zh_CN';
        $res = $this->httpPost($url, $options);
        if ($res) {
            if ($json = $this->jsonDecode($res)) {
                $uuid = $json->uuid;
            }
        }

        return $uuid;
    }

    /**
     * 获取二维码图片.
     */
    public function getQrCode()
    {
        $msgid = $this->msgid();
        $ticket = $this->ticket();
        $uuid = $this->uuid($ticket);
        $query = [
            'ticket' => $ticket,
            'uuid' => $uuid,
            'action' => 'check',
            'type' => 'msgs',
            'msgid' => $msgid,
        ];
        $text = "https://mp.weixin.qq.com/safe/safeqrcode?ticket=$ticket&uuid=$uuid&action=check&type=msgs&msgid=$msgid";
        $qr = new QrCode();
        $qr->show($text);
        $url = 'safe/safeqrcode';
        $res = $this->httpGet($url, $query);
        file_put_contents('qr.jpg', $res);
    }

    /**
     * 检查二维码是否扫码
     *
     * @param $uuid
     * @param $token
     */
    public function checkScan($token, $uuid)
    {
        $time = $this->getMillisecond();
        $options = [
            'token' => $token,
            'lang' => 'zh_CN',
            'f' => 'json',
            'ajax' => '1',
            'random' => $time,
            'uuid' => $uuid,
            'action' => 'json',
            'type' => 'json',
        ];
        $url = 'safe/safeuuid?timespam='.$time.'&lang=zh_CN';
        $res = $this->httpPost($url, $options);
        if ($res) {
            var_dump($res);
        }
    }

    /**
     * 群发图文.
     *
     * @param $uuid
     * @param $msgid
     * @param $appmsgid
     *
     * @return string
     */
    public function mass($uuid, $msgid, $appmsgid)
    {
        $query = [
            't' => 'ajax-response',
            'lang' => 'zh_CN',
        ];
        $data = [
            'token' => $this->token,
            'lang' => 'zh_CN',
            'f' => 'json',
            'ajax' => '1',
            'random' => $this->getMillisecond(),
            'smart_product' => '0',
            'type' => '10',
            'appmsgid' => $appmsgid,
            'share_page' => '1',
            'send_time' => '0',
            'cardlimit' => '1',
            'sex' => '0',
            'groupid' => '-1',
            'synctxweibo' => '0',
            'need_open_comment' => '1',
            'only_fans_can_comment' => '0',
            'country' => '',
            'province' => '',
            'city' => '',
            'imgcode' => '',
            'operation_seq' => $msgid,
            'req_id' => $this->getRandomStr(),
            'req_time' => time(),
            'direct_send' => '1',
            'code' => $uuid,
        ];

        return $this->httpPost('cgi-bin/masssend', $data, $query);
    }
}
