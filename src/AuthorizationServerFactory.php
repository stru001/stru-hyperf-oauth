<?php


namespace Stru\StruHyperfOauth;


use League\OAuth2\Server\AuthorizationServer;
use Psr\Container\ContainerInterface;

class AuthorizationServerFactory
{
    use ConfigTrait,CryptKeyTrait,RepositoryTrait;

    public function __invoke(ContainerInterface $container):AuthorizationServer
    {
        $clientRepository = $this->getClientRepository($container);
        $accessTokenRepository = $this->getAccessTokenRepository($container);
        $scopeRepository = $this->getScopeRepository($container);

        $privateKey = $this->getCryptKey($this->getPrivateKey($container));
        $encryptKey = $this->getEncryptionKey($container);

        $authServer = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            $encryptKey
        );

        $accessTokenInterval = new \DateInterval($this->getAccessTokenExpire($container));
        $grants = $this->getGrantsConfig();
        foreach ($grants as $grant) {
            if (empty($grant)){
                continue;
            }
            $authServer->enableGrantType(
                $container->get($grant),
                $accessTokenInterval
            );
        }
        return $authServer;
    }
}