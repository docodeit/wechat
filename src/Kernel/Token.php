<?php
/**
 * Date: 2018/4/11
 * Time: 10:51
 */
namespace JinWeChat\Kernel;
use JinWeChat\Kernel\Contracts\TokenInterface;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use JinWechat\Kernel\Traits\HasHttpRequests;
use JinWechat\Kernel\Traits\InteractsWithCache;
use JinWechat\Kernel\Exceptions\HttpException;
class Token implements TokenInterface{
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
    protected $cachePrefix = 'jinwechat.kernel.token.';
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