<?php

namespace JinWeChat\OfficialAccount;

use JinWeChat\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Login\ServiceProvider::class,
    ];
}
