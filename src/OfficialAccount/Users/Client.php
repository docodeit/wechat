<?php

/*
 * This file is part of the docodeit/wechat.
 *
 * (c) docodeit <lqbzdyj@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JinWeChat\OfficialAccount\Users;

use JinWeChat\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * 用户管理列表.
     * @param int $offset
     * @param int $limit
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list($offset = 0, $limit = 10)
    {
        $query = [
            'query' => [
                'action' => 'get_user_list',
                'limit' => $limit,
                'offset' => $offset,
                'f' => 'json',
                'lang' => 'zh_CN',
                'ajax' => '1',
                'random' => $this->getMillisecond(),
            ]
        ];
        return $this->httpGet('cgi-bin/user_tag', $query);
    }
}
