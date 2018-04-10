<?php
/**
 * Date: 2018/4/2
 * Time: 16:54
 */
namespace JinWeChat\Kernel\Console;

use Carbon\Carbon;
use Monolog\Logger;
use Illuminate\Support\Arr;

class Console{
    const INFO = 'INFO';
    const WARNING = 'WARNING';
    const ERROR = 'ERROR';
    const MESSAGE = 'MESSAGE';

    protected $app;
}