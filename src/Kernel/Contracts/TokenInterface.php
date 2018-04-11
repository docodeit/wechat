<?php
/**
 * Date: 2018/4/11
 * Time: 10:52
 */
namespace JinWechat\Kernel\Contracts;

use Psr\Http\Message\RequestInterface;
interface TokenInterface{
    /**
     * @return array
     */
    public function getToken():array ;

    /**
     * @param RequestInterface $request
     * @param array $requestOptions
     * @return RequestInterface
     */
    public function applyToRequest(RequestInterface $request, array $requestOptions = []): RequestInterface;
}