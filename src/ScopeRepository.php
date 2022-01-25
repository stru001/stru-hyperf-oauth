<?php


namespace Stru\StruHyperfOauth;


use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Stru\StruHyperfOauth\Entity\ScopeEntity;

class ScopeRepository implements ScopeRepositoryInterface
{
    public static $scopes = [
        //
    ];

    public static function hasScope($id)
    {
        return $id === '*' || array_key_exists($id, static::$scopes);
    }

    public function getScopeEntityByIdentifier($identifier)
    {
        if (self::hasScope($identifier)){
            return new ScopeEntity($identifier);
        }
    }
    // 不进行范围处理
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        if ($scopes){
            return new ScopeEntity($scopes);
        }
        return new ScopeEntity('*');
    }
}