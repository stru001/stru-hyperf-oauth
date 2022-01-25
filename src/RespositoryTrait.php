<?php


namespace Stru\StruHyperfOauth;


use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Psr\Container\ContainerInterface;
use Stru\StruHyperfOauth\Exception\RuntimeException;

trait RespositoryTrait
{
    protected function getUserRespository()
    {

    }

    protected function getScopeRepository(ContainerInterface $container):ScopeRepositoryInterface
    {
        if (! $container->has(ScopeRepositoryInterface::class)) {
            throw new RuntimeException(
                '未实现 OAuth2 Scope Repository'
            );
        }
        return $container->get(ScopeRepositoryInterface::class);
    }

    protected function getAccessTokenRepository(ContainerInterface $container):AccessTokenRepositoryInterface
    {
        if (! $container->has(AccessTokenRepositoryInterface::class)) {
            throw new RuntimeException(
                '未实现 OAuth2 Access Token Repository'
            );
        }
        return $container->get(AccessTokenRepositoryInterface::class);
    }

    protected function getClientRepository(ContainerInterface $container):ClientRepositoryInterface
    {
        if (! $container->has(ClientRepositoryInterface::class)){
            throw new RuntimeException(
                "未实现 Oauth2 Client Repository"
            );
        }
        return $container->get(ClientRepositoryInterface::class);
    }

    protected function getRefreshTokenRepository(ContainerInterface $container):RefreshTokenRepositoryInterface
    {
        if (! $container->has(RefreshTokenRepositoryInterface::class)){
            throw new RuntimeException(
                "未实现 Oauth2 Client Repository"
            );
        }
        return $container->get(RefreshTokenRepositoryInterface::class);
    }

    protected function getAuthCodeRespository(ContainerInterface $container):AuthCodeRepository
    {
        if (! $container->has(AuthCodeRepository::class)){
            throw new RuntimeException(
                "未实现 Oauth2 Client Repository"
            );
        }
        return $container->get(AuthCodeRepository::class);
    }
}