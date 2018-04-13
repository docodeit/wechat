<?php

/*
 * This file is part of the docodeit/wechat.
 *
 * (c) docodeit <lqbzdyj@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JinWeChat\Kernel\Console;

class Console
{
    const INFO = 'INFO';

    const WARNING = 'WARNING';

    const ERROR = 'ERROR';

    const MESSAGE = 'MESSAGE';

    protected $app;

    /**
     * determine the console is windows or linux.
     *
     * @return bool
     */
    public static function isWin()
    {
        return 'WIN' === strtoupper(substr(PHP_OS, 0, 3));
    }
}
