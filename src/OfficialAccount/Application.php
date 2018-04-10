<?php

namespace JinWeChat\OfficialAccount;

use Illuminate\Support\Facades\App;
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
//        Login\ServiceProvider::class,
        Appmsg\ServiceProvider::class
    ];
}
