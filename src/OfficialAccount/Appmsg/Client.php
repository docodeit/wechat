<?php
/**
 * Date: 2018/3/30
 * Time: 10:36.
 */

namespace JinWeChat\OfficialAccount\Appmsg;

use JinWeChat\Kernel\BaseClient;

class Client extends BaseClient
{
//    protected $baseUri = 'https://mp.weixin.qq.com/';

    public function list($query)
    {
        return $this->httpGet('/cgi-bin/appmsg', $query);
    }
}
