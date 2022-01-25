<?php


namespace Stru\StruHyperfOauth\Grant;


use League\OAuth2\Server\Grant\AuthCodeGrant;
use Psr\Container\ContainerInterface;
use Stru\StruHyperfOauth\ConfigTrait;
use Stru\StruHyperfOauth\RespositoryTrait;

class AuthCodeGrantFactory
{
    use RespositoryTrait,ConfigTrait;

    public function __invoke(ContainerInterface $container)
    {
        $grant = new AuthCodeGrant(
            $this->getAuthCodeRespository($container),
            $this->getRefreshTokenRepository($container),
            new \DateInterval($this->getAuthCodeExpire())
        );

        $grant->setRefreshTokenTTL(
            new \DateInterval($this->getRefreshTokenExpire())
        );
        return $grant;
    }

}