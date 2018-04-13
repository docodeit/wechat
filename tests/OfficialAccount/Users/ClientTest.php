<?php


namespace JinWeChat\Tests\OfficialAccount\Users;

use JinWeChat\OfficialAccount\Users\Client;
use JinWeChat\Tests\TestCase;

class ClientTest extends TestCase
{
    public function testGet()
    {
        $client = $this->mockApiClient(Client::class);
        $query = [
            'query' => [
                'action' => 'get_user_list',
                'limit' => 10,
                'offset' => 0,
                'f' => 'json',
                'lang' => 'zh_CN',
                'ajax' => '1',
                'random' => $client->expects()->getMillisecond(),
            ],
        ];
        $client->expects()->httpGet('cgi-bin/user_tag', $query)->andReturn('mock-result')->once();
        $this->assertSame('mock-result', $client->get($query));
    }
}
