<?php


namespace Stru\StruHyperfOauth;


use http\Exception\RuntimeException;
use League\OAuth2\Server\CryptKey;

trait CryptKeyTrait
{
    protected function getCryptKey($keyConfig):CryptKey
    {
        if (is_string($keyConfig)){
            return new CryptKey($keyConfig,null,false);
        }
        if (isset($keyConfig['key_path'])){
            throw new RuntimeException(
                "私钥路径未配置"
            );
        }

        $passPhrase = $keyConfig['pass_phrase'] ?? null;

        if (isset($keyConfig['key_permissions_check'])){
            return new CryptKey($keyConfig['key_path'],$passPhrase,(bool)$keyConfig['key_permissions_check']);
        }
        return new CryptKey($keyConfig['key_path'],$passPhrase);
    }
}