<?php

namespace Stru\StruHyperfOauth\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Request;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AuthorizationController
 * @package Stru\StruHyperfOauth\Controller
 * @Controller(prefix="oauth")
 */
class AuthorizationController
{
    protected AuthorizationServer $server;
    /**
     * @Inject
     * @var ResponseInterface
     */
    protected ResponseInterface $response;

    public function __construct(AuthorizationServer $server)
    {
        $this->server = $server;
    }

    /**
     * @RequestMapping(path="authorize",methods="get")
     * @param ServerRequestInterface $psrRequest
     * @param Request $request
     */
    public function authorize(
        ServerRequestInterface $psrRequest,
        Request $request
    )
    {

        // 验证请求参数
        $authRequest = $this->server->validateAuthorizationRequest($psrRequest);


        // 通过 Hyperf 方式获取请求参数
        $clientId = $request->input('client_id','');
        $redirectUri = $request->input('redirect_uri','');
        $responseType = $request->input('response_type','');
        // 权限，暂时不进行实现
//        $scope = $request->input('scope','');
        // 防止重放攻击
        $state = $request->input('state','');
        // 校验参数

    }
}