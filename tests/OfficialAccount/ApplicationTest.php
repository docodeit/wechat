<?php

/*
 * This file is part of the docodeit/wechat.
 *
 * (c) docodeit <lqbzdyj@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JinWeChat\Tests\OfficialAccount;

use JinWeChat\OfficialAccount\Application;
use JinWeChat\Tests\TestCase;

/**
 * Class Application.
 */
class ApplicationTest extends TestCase
{
    public function testProperties()
    {
        $app = new Application();

        $this->assertInstanceOf(\JinWeChat\OfficialAccount\Users\Client::class, $app->users);
        $this->assertInstanceOf(\JinWeChat\OfficialAccount\Mass\Client::class, $app->mass);
        $this->assertInstanceOf(\JinWeChat\OfficialAccount\Login\Client::class, $app->login);
        $this->assertInstanceOf(\JinWeChat\OfficialAccount\Appmsg\Client::class, $app->appmsg);
    }
}
