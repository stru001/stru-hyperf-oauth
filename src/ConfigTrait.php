<?php


namespace Stru\StruHyperfOauth;


use Psr\Container\ContainerInterface;
use Stru\StruHyperfOauth\Exception\InvalidConfigException;

trait ConfigTrait
{

    protected function getPrivateKey()
    {
        $config = config('oauth');
        if (!isset($config['private_key_path']) || empty($config['private_key_path'])){
            throw new InvalidConfigException(
                "The private_key value is missing in config oauth"
            );
        }
        return $config['private_key_path'];
    }

    protected function getEncryptionKey()
    {
        $config = config('oauth');
        if (! isset($config['encryption_key']) || empty($config['encryption_key'])) {
            throw new InvalidConfigException(
                'The encryption_key value is missing in config oauth'
            );
        }

        return $config['encryption_key'];
    }

    protected function getAccessTokenExpire()
    {
        $config = config('oauth');
        if (! isset($config['access_token_expire'])) {
            throw new InvalidConfigException(
                'The access_token_expire value is missing in config oauth'
            );
        }

        return $config['access_token_expire'];
    }

    protected function getRefreshTokenExpire()
    {
        $config = config('oauth');
        if (! isset($config['refresh_token_expire'])){
            throw new InvalidConfigException(
                "Thie refresh_token_expire value is missing in config oauth"
            );
        }
        return $config['refresh_token_expire'];
    }

    protected function getAuthCodeExpire()
    {
        $config = config('oauth');
        if (! isset($config['auth_code_expire'])){
            throw new InvalidConfigException(
                "Thie auth_code_expire value is missing in config oauth"
            );
        }
        return $config['auth_code_expire'];
    }

    protected function getListenersConfig()
    {
        $config = config('oauth');
        if (empty($config['event_listeners'])) {
            return [];
        }
        if (! is_array($config['event_listeners'])) {
            throw new InvalidConfigException(
                'The event_listeners config must be an array value'
            );
        }

        return $config['event_listeners'];

    }

    protected function getListenerProvidersConfig()
    {
        $config = config('oauth');
        if (empty($config['event_listener_providers'])) {
            return [];
        }
        if (! is_array($config['event_listener_providers'])) {
            throw new InvalidConfigException(
                'The event_listener_providers config must be an array value'
            );
        }

        return $config['event_listener_providers'];
    }

}