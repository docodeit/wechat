<?php

/*
 * This file is part of the docodeit/wechat.
 *
 * (c) docodeit <lqbzdyj@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JinWeChat\OfficialAccount\Appmsg;

use JinWeChat\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
<<<<<<< HEAD
     * 图文素材列表
     * @param $begin
     * @param $count
=======
     * 图文素材列表.
     *
     * @param $query
     *
>>>>>>> 89a68d3d1fb6e06e14839c3e85267700a0aa95bd
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function list($begin = 0, $count = 10)
    {
        $query = [
            'query' =>
                ['type' => '10',
                    'action' => 'list',
                    'begin' => $begin,
                    'count' => $count,
                    'f' => 'json',
                    'lang' => 'zh_CN',
                    'ajax' => '1',
                    'random' => $this->getMillisecond()
                ]];
        return $this->httpGet('cgi-bin/appmsg', $query);
    }
}
