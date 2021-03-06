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

use JinWeChat\Kernel\Contracts\TokenInterface;
use JinWeChat\Kernel\Exceptions\HttpException;
use JinWeChat\Kernel\Traits\HasHttpRequests;
use JinWeChat\Kernel\Traits\InteractsWithCache;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;

class Token implements TokenInterface
{
    use HasHttpRequests, InteractsWithCache;

    protected $app;

    protected $requestMethod = 'GET';

    protected $token;

    protected $queryName;

    protected $safeSeconds = 500;

    /**
     * @var string
     */
    protected $tokenKey = 'token';

    /**
     * @var string
     */
    protected $cachePrefix = 'JinWeChat.kernel.token.';

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function getToken(): array
    {
        $cacheKey = $this->getCacheKey();
        $cache = $this->getCache();
        if ($cache->has($cacheKey)) {
            return $cache->get($cacheKey);
        }

        $token = $this->requestToken($this->getCredentials(), true);

        $this->setToken($token[$this->tokenKey], $token['expires_in'] ?? 57600);

        return $token;
    }

    public function applyToRequest(RequestInterface $request, array $requestOptions = []): RequestInterface
    {
        parse_str($request->getUri()->getQuery(), $query);

        $query = http_build_query(array_merge($this->getQuery(), $query));

        return $request->withUri($request->getUri()->withQuery($query));
    }

    protected function getQuery(): array
    {
        return [$this->queryName ?? $this->token => $this->getToken()[$this->token]];
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->cachePrefix.$this->app['config']['username'];
    }

    public function requestToken(array $credentials, $toArray = false)
    {
        $response = $this->sendRequest($credentials);
        $result = json_decode($response->getBody()->getContents(), true);
        $formatted = $this->castResponseToType($response, $this->app['config']->get('response_type'));
        if (preg_match('/token=([\d]+)/i', $result['redirect_url'], $match)) {
            $this->token = $match[1];
        }
        $this->baseRefer = $result['redirect_url'];

        if (empty($result['redirect_url'])) {
            throw new HttpException('Request token fail: '.json_encode($result, JSON_UNESCAPED_UNICODE), $response, $formatted);
        }

        return $toArray ? $result : $formatted;
    }
}
