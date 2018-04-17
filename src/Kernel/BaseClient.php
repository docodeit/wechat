<?php

/*
 * This file is part of the docodeit/wechat.
 *
 * (c) docodeit <lqbzdyj@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JinWeChat\Kernel;

use GuzzleHttp\Client;
use JinWeChat\Kernel\Http\Response;
use JinWeChat\Kernel\Traits\HasHttpRequests;
use Psr\Http\Message\RequestInterface;

/**
 * Class BaseClient.
 *
 * @author docodeit <lqbzdyj@qq.com>
 */
class BaseClient
{
    use HasHttpRequests {
        request as performRequest;
    }

    /**
     * @var \JinWeChat\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * @var \JinWeChat\Kernel\Contracts\CookiesInterface
     */
    protected $cookies;

    /**
     * @var string
     */
    protected $referrer;

    /**
     * @var int
     */
    protected $token;

    /**
     * @var
     */
    protected $baseUri;

    /**
     * BaseClient constructor.
     *
     * @param \JinWeChat\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
        $this->referrer = $app['config']->get('http.base_uri', 'https://mp.weixin.qq.com/');
        $this->token = isset($this->app->getConfig()['cookies']['token']) ? $this->app->getConfig()['cookies']['token'] : '';
    }

    /**
     * GET request.
     *
     * @param string $url
     * @param array $query
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function httpGet(string $url, array $query = [])
    {
        return $this->request($url, 'GET', ['query' => $query]);
    }

    /**
     * POST request.
     *
     * @param string $url
     * @param array $data
     * @param array $query
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function httpPost(string $url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', ['query' => $query, 'form_params' => $data]);
    }

    /**
     * JSON request.
     *
     * @param string $url
     * @param string|array $data
     * @param array $query
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function httpPostJson(string $url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', ['query' => $query, 'json' => $data]);
    }

    /**
     * Upload file.
     *
     * @param string $url
     * @param array $files
     * @param array $form
     * @param array $query
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function httpUpload(string $url, array $files = [], array $form = [], array $query = [])
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r'),
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request($url, 'POST', ['query' => $query, 'multipart' => $multipart]);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     * @param bool $returnRaw
     *
     * @return string
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }
        //options 加入token
        if (isset($options['query'])) {
            $options['query'] = array_merge($options['query'], ['token' => $this->token]);
        }
        $response = $this->performRequest($url, $method, $options);
        //保存Token todo::改为缓存
        if (preg_match('/cgi-bin\/bizlogin?action=startlogin/', $url, $urlMatch)) {
            if (preg_match('/token=([\d]+)/i', $response['redirect_url'], $match)) {
                $this->token = $match[1];
            }
        }

        return $returnRaw ? $response : $response->getBody()->getContents();
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     *
     * @return \JinWeChat\Kernel\Http\Response
     */
    public function requestRaw(string $url, string $method = 'GET', array $options = [])
    {
        return Response::buildFromPsrResponse($this->request($url, $method, $options, true));
    }

    /**
     * Return GuzzleHttp\Client instance.
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient(): Client
    {
        if (!($this->httpClient instanceof Client)) {
            $this->httpClient = $this->app['http_client'] ?? new Client();
        }

        return $this->httpClient;
    }

    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares()
    {
        //referrer
        $this->pushMiddleware($this->headerMiddleware('Referer', $this->referrer), 'referrer');
    }

    /**
     * apply referrer to the request header.
     *
     * @param $header
     * @param $value
     *
     * @return \Closure
     */
    public function headerMiddleware($header, $value)
    {
        return function (callable $handler) use ($header, $value) {
            return function (
                RequestInterface $request,
                array $options
            ) use (
                $handler,
                $header,
                $value
            ) {
                $request = $request->withHeader($header, $value);

                return $handler($request, $options);
            };
        };
    }

    /**
     * 保存Cookies todo::保存到Cache.
     */
    public function saveCookies()
    {
        $cookies = $this->app['http_client']->getConfig()['cookies'];
    }

    /**
     * 获取微秒.
     *
     * @return float
     */
    protected function getMillisecond()
    {
        list($s1, $s2) = explode(' ', microtime());

        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 转换json.
     *
     * @param $json
     *
     * @return bool|string
     */
    protected function jsonDecode($json)
    {
        $res = json_decode($json);
        if (JSON_ERROR_NONE === json_last_error()) {
            return $res;
        } else {
            return false;
        }
    }

    /**
     * 获取随机字符
     * @param int $length
     * @return string
     */
    protected function getRandomStr($length = 32)
    {
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for ($randomStr = '', $i = 0; $i < $length; $i++) {
            $randomStr .= substr($str, floor(lcg_value() * strlen($str)), 1);
        }
        return $randomStr;
    }

    /**
     * 判断微信错误码返回
     * @param $code
     * @return string
     */
    protected function judgeCode($code)
    {
        switch ($code) {
            case "0":
                return "发送成功";
            case "67014":
                return "该时刻定时消息过多，请选择其他时刻";
            case "67012":
                return "设置失败，定时时间与已有互选广告订单时间冲突";
            case "67013":
                return "设置失败，定时时间超过卡券有效期";
            case "200013":
                return "操作频率过高，请明天再试";
            case "64004":
                return "剩余定时群发数量不足";
            case "67011":
                return "设置的定时群发时间错误，请重新选择";
            default:
                return "系统繁忙，请稍后再试";
        }
    }
}
