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
     * 图文素材列表.
     *
     * @param $query
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function list($query)
    {
        return $this->httpGet('cgi-bin/appmsg', $query);
    }
}
