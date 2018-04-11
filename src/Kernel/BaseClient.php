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
use JinWeChat\Kernel\Contracts\CookiesInterface;
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
     * @param \JinWeChat\Kernel\ServiceContainer                $app
     * @param \JinWeChat\Kernel\Contracts\CookiesInterface|null $cookies
     */
    public function __construct(ServiceContainer $app, CookiesInterface $cookies = null)
    {
        $this->app = $app;
        $this->referrer = $app['config']->get('http.base_uri', 'https://mp.weixin.qq.com/');
    }

    /**
     * GET request.
     *
     * @param string $url
     * @param array  $query
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function httpGet(string $url, array $query = [])
    {
        return $this->request($url, 'GET', $query);
    }

    /**
     * POST request.
     *
     * @param string $url
     * @param array  $data
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function httpPost(string $url, array $data = [])
    {
        return $this->request($url, 'POST', ['form_params' => $data]);
    }

    /**
     * JSON request.
     *
     * @param string       $url
     * @param string|array $data
     * @param array        $query
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
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
     * @param array  $files
     * @param array  $form
     * @param array  $query
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
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
     * @param array  $options
     * @param bool   $returnRaw
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $response = $this->performRequest($url, $method, $options);
        //保存Token todo::改为缓存
        if (preg_match('/cgi-bin\/bizlogin?action=startlogin/', $url, $urlMatch)) {
            if (preg_match('/token=([\d]+)/i', $response['redirect_url'], $match)) {
                $this->token = $match[1];
            }
        }

        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $options
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
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
}
