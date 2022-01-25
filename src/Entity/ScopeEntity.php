<?php


namespace Stru\StruHyperfOauth\Entity;


use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ScopeEntity implements ScopeEntityInterface
{
    use EntityTrait;

    public function __construct($name)
    {
        $this->setIdentifier($name);
    }

    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }
}