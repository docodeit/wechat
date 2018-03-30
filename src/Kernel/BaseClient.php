<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JinWeChat\Kernel;

use JinWeChat\Kernel\Contracts\CookiesInterface;
use JinWeChat\Kernel\Http\Response;
use JinWeChat\Kernel\Traits\HasHttpRequests;
use GuzzleHttp\Client;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class BaseClient.
 *
 * @author overtrue <i@overtrue.me>
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
     * @var
     */
    protected $baseUri;

    /**
     * BaseClient constructor.
     *
     * @param \JinWeChat\Kernel\ServiceContainer $app
     * @param \JinWeChat\Kernel\Contracts\CookiesInterface|null $cookies
     */
    public function __construct(ServiceContainer $app, CookiesInterface $cookies = null)
    {
        $this->app = $app;
        $this->cookies = $cookies ?? $this->app['cookies'];
    }

    /**
     * GET request.
     *
     * @param string $url
     * @param array $query
     *
     * @return \Psr\Http\Message\ResponseInterface|\JinWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function httpGet(string $url, array $query = [])
    {
        $data = [
            'query' => $query,
            'timeout' => 60,
            'cookies' => $this->cookies,
            'headers' => ["Referer" => $this->baseUri, "Connection" => "keep-alive"],
            'allow_redirects' => true];
        return $this->request($url, 'GET', $data);
    }

    /**
     * POST request.
     *
     * @param string $url
     * @param array $data
     *
     * @return \Psr\Http\Message\ResponseInterface|\JinWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function httpPost(string $url, array $data = [])
    {
        $data = [
            'timeout' => 60,
            'form_params' => $data,
            'cookies' => $this->cookies,
            'headers' => ["Referer" => $this->baseUri, "Connection" => "keep-alive"],
            'allow_redirects' => true];
        return $this->request($url, 'POST', $data);
    }

    /**
     * JSON request.
     *
     * @param string $url
     * @param string|array $data
     * @param array $query
     *
     * @return \Psr\Http\Message\ResponseInterface|\JinWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
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
     * @return \Psr\Http\Message\ResponseInterface|\JinWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
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
     * @return CookiesInterface
     */
    public function getAccessToken(): CookiesInterface
    {
        return $this->cookies;
    }

    /**
     * @param \JinWeChat\Kernel\Contracts\CookiesInterface $cookies
     *
     * @return $this
     */
    public function setAccessToken(CookiesInterface $cookies)
    {
        $this->cookies = $cookies;

        return $this;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     * @param bool $returnRaw
     *
     * @return \Psr\Http\Message\ResponseInterface|\JinWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $response = $this->performRequest($url, $method, $options);

        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     *
     * @return \JinWeChat\Kernel\Http\Response
     *
     * @throws \JinWeChat\Kernel\Exceptions\InvalidConfigException
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
        // retry
        $this->pushMiddleware($this->retryMiddleware(), 'retry');
        // access token
//        $this->pushMiddleware($this->cookiesMiddleware(), 'cookies');
        // log
//        $this->pushMiddleware($this->logMiddleware(), 'log');
    }

    /**
     * Attache access token to request query.
     *
     * @return \Closure
     */
//    protected function cookiesMiddleware()
//    {
//        return function (callable $handler) {
//            return function (RequestInterface $request, array $options) use ($handler) {
//                if ($this->cookies) {
//                    $request = $this->cookies->applyToRequest($request, $options);
//                }
//
//                return $handler($request, $options);
//            };
//        };
//    }

    /**
     * Log the request.
     *
     * @return \Closure
     */
    protected function logMiddleware()
    {
        $formatter = new MessageFormatter($this->app['config']['http.log_template'] ?? MessageFormatter::DEBUG);

        return Middleware::log($this->app['logger'], $formatter);
    }

    /**
     * Return retry middleware.
     *
     * @return \Closure
     */
    protected function retryMiddleware()
    {
        return Middleware::retry(function (
            $retries,
            RequestInterface $request,
            ResponseInterface $response = null
        ) {
            // Limit the number of retries to 2
            if ($retries < $this->app->config->get('http.retries', 1) && $response && $body = $response->getBody()) {
                // Retry on server errors
                $response = json_decode($body, true);

                if (!empty($response['errcode']) && in_array(abs($response['errcode']), [40001, 42001], true)) {
                    $this->cookies->refresh();
                    $this->app['logger']->debug('Retrying with refreshed access token.');

                    return true;
                }
            }

            return false;
        }, function () {
            return abs($this->app->config->get('http.retry_delay', 500));
        });
    }
}
