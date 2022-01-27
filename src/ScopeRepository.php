<?php


namespace Stru\StruHyperfOauth;


use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Stru\StruHyperfOauth\Entity\ScopeEntity;

class ScopeRepository implements ScopeRepositoryInterface
{

    public function getScopeEntityByIdentifier($identifier)
    {
        $scopes = [
            'public' => [],
            'read' => [],
            'write' => []
        ];

        if (array_key_exists($identifier,$scopes) === false){
            return false;
        }

        $scope = new ScopeEntity();
        $scope->setIdentifier($identifier);

        return $scope;
    }

    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        if ((int) $userIdentifier === 1) {
            $scope = new ScopeEntity();
            $scope->setIdentifier('public');
            $scopes[] = $scope;
        }

        return $scopes;
    }
}