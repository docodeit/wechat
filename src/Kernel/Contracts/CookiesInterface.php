<?php
/**
 * Date: 2018/3/30
 * Time: 10:31.
 */

namespace JinWeChat\Kernel\Contracts;

use Psr\Http\Message\RequestInterface;

interface CookiesInterface
{
    public function getCookies(): array;

    public function refresh(): self;

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array                              $requestOptions
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function applyToRequest(RequestInterface $request, array $requestOptions = []): RequestInterface;
}
