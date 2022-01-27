<?php


namespace Stru\StruHyperfOauth\Entity;


use League\OAuth2\Server\Entities\UserEntityInterface;
use App\Model\User;

class UserEntity extends User implements UserEntityInterface
{

    public function getIdentifier()
    {
        return $this->getKeyName();
    }
}