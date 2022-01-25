<?php


namespace Stru\StruHyperfOauth;


use League\OAuth2\Server\AuthorizationServer;
use Psr\Container\ContainerInterface;

class AuthorizationServerFactory
{
    use ConfigTrait,CryptKeyTrait,RespositoryTrait;

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
        // 处理监听和，监听服务器提供者
//        $this->addListeners($authServer,$container);
//        $this->addListenerProviders($authServer,$container);
        return $authServer;
    }

    private function addListeners(
        AuthorizationServer $authServer,
        ContainerInterface $container
    )
    {
        $listeners = $this->getListenersConfig();

        foreach ($listeners as $idx => $listenerConfig) {
            $event = $listenerConfig[0];
            $listener = $listenerConfig[1];
            $priority = $listenerConfig[2] ?? null;
            if (is_string($listener)) {
                if (! $container->has($listener)) {
                    throw new Exception\InvalidConfigException(sprintf(
                        'The second element of event_listeners config at ' .
                        'index "%s" is a string and therefore expected to ' .
                        'be available as a service key in the container. ' .
                        'A service named "%s" was not found.',
                        $idx,
                        $listener
                    ));
                }
                $listener = $container->get($listener);
            }
            $authServer->getEmitter()->addListener($event, $listener, $priority);
        }
    }

    private function addListenerProviders(
        AuthorizationServer $authServer,
        ContainerInterface $container
    )
    {
        $providers = $this->getListenerProvidersConfig();

        foreach ($providers as $idx => $provider) {
            if (is_string($provider)) {
                if (! $container->has($provider)) {
                    throw new Exception\InvalidConfigException(sprintf(
                        'The event_listener_providers config at ' .
                        'index "%s" is a string and therefore expected to ' .
                        'be available as a service key in the container. ' .
                        'A service named "%s" was not found.',
                        $idx,
                        $provider
                    ));
                }
                $provider = $container->get($provider);
            }
            $authServer->getEmitter()->useListenerProvider($provider);
        }
    }
}