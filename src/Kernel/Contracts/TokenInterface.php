<?php

/*
 * This file is part of the docodeit/wechat.
 *
 * (c) docodeit <lqbzdyj@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JinWechat\Kernel\Contracts;

use Psr\Http\Message\RequestInterface;

interface TokenInterface
{
    /**
     * @return array
     */
    public function getToken(): array;

    /**
     * @param RequestInterface $request
     * @param array            $requestOptions
     *
     * @return RequestInterface
     */
    public function applyToRequest(RequestInterface $request, array $requestOptions = []): RequestInterface;
}
