<?php

namespace Stru\StruHyperfOauth\Controller;

use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use Stru\StruHyperfAuth\AuthMiddleware;
use Laminas\Diactoros\Stream;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stru\StruHyperfOauth\Entity\UserEntity;

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
     * @RequestMapping(path="authorize",methods="GET")
     * @Middlewares({@Middleware(AuthMiddleware::class)})
     * @param ServerRequestInterface $psrRequest
     */
    public function authorize(ServerRequestInterface $psrRequest)
    {
        try {
            // Validate the HTTP request and return an AuthorizationRequest object.
            // The auth request object can be serialized into a user's session
            $authRequest = $this->server->validateAuthorizationRequest($psrRequest);

            // Once the user has logged in set the user on the AuthorizationRequest
            $authRequest->setUser(new UserEntity());

            // Once the user has approved or denied the client update the status
            // (true = approved, false = denied)
            $authRequest->setAuthorizationApproved(true);

            // Return the HTTP redirect response
            return $this->server->completeAuthorizationRequest($authRequest, $this->response);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($this->response);
        } catch (\Exception $exception) {
            $body = new Stream('php://temp', 'r+');
            $body->write($exception->getMessage());

            return $this->response->withStatus(500)->withBody($body);
        }

    }

    /**
     * @RequestMapping(path="token",methods="POST")
     * @param ServerRequestInterface $psrRequest
     * @return ResponseInterface
     */
    public function accessToken(ServerRequestInterface $psrRequest)
    {
        try {
            return $this->server->respondToAccessTokenRequest($psrRequest, $this->response);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($this->response);
        } catch (\Exception $exception) {
            $body = new Stream('php://temp', 'r+');
            $body->write($exception->getMessage());

        }
    }
}