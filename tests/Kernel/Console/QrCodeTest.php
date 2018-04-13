<?php

/*
 * This file is part of the docodeit/wechat.
 *
 * (c) docodeit <lqbzdyj@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JinWeChat\Tests\Kernel\Console;

use JinWeChat\Kernel\Console\QrCode;
use JinWeChat\Tests\TestCase;

class QrCodeTest extends TestCase
{
    public function testShow()
    {
        $qrcode = new QrCode();
        $qrcode->show('https://login.weixin.qq.com/qrcode/QfB295ZlVQ==');
        $this->assertTrue(true);
    }
}
