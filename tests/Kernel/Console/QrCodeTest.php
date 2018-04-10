<?php
/**
 * Date: 2018/4/2
 * Time: 19:06
 */

namespace JinWeChat\Tests\Kernel\Console;

use JinWeChat\Tests\TestCase;
use JinWeChat\Kernel\Console\QrCode;


class QrCodeTest extends TestCase
{
    public function testShow()
    {
        $qr = new QrCode();
        $qr->show('https://login.weixin.qq.com/qrcode/QfB295ZlVQ==');
        $this->assertTrue(true);
    }
}
