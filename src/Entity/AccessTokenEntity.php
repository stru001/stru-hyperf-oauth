<?php


namespace Stru\StruHyperfOauth\Entity;


use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use Stru\StruHyperfOauth\Model\AccessToken;

class AccessTokenEntity extends AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait,TokenEntityTrait,EntityTrait;
}