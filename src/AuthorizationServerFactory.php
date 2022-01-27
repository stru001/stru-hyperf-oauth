<?php


namespace Stru\StruHyperfOauth;


use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use Psr\Container\ContainerInterface;

class AuthorizationServerFactory
{
    use ConfigTrait,CryptKeyTrait,RepositoryTrait;

    public function __invoke(ContainerInterface $container):AuthorizationServer
    {
        $clientRepository = $this->getClientRepository($container);
        $accessTokenRepository = $this->getAccessTokenRepository($container);
        $scopeRepository = $this->getScopeRepository($container);

        $privateKey = $this->getCryptKey($this->getPrivateKey());
        $encryptKey = $this->getEncryptionKey();

        $authServer = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            $encryptKey
        );

        $accessTokenInterval = new \DateInterval($this->getAccessTokenExpire());

        $authServer->enableGrantType(
            $this->makeAuthCodeGrant($container),
            $accessTokenInterval
        );

        return $authServer;
    }

    protected function makeAuthCodeGrant($container)
    {
        return tap($this->buildAuthCodeGrant($container), function ($grant) {
            $grant->setRefreshTokenTTL(StruOauth::refreshTokensExpireIn());
        });
    }

    protected function buildAuthCodeGrant($container)
    {
        $authCodeInterval = new \DateInterval($this->getAuthCodeExpire());
        return new AuthCodeGrant(
            $this->getAuthCodeRespository($container),
            $this->getRefreshTokenRepository($container),
            $authCodeInterval
        );
    }
}