<?php

/*
 * This file is part of the docodeit/wechat.
 *
 * (c) docodeit <lqbzdyj@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JinWeChat;

/**
 * 入口.
 *
 * @method static OfficialAccount\Application    officialAccount(array $config)
 **/
class Factory
{
    /**
     * @param $name
     * @param array $config
     *
     * @return mixed
     */
    public static function make($name, array $config)
    {
//        $namespace = 'OfficialAccount';
        $application = "\\JinWeChat\\{$name}\\Application";

        return new $application($config);
    }

    /**
     * @param $name
     * @param $args
     *
     * @return mixed
     */
    public static function __callStatic($name, $args)
    {
        $name = ucwords(str_replace(['-', '_'], ' ', $name));

        return self::make($name, ...$args);
    }
}
