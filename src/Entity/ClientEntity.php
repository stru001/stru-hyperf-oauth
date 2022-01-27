<?php


namespace Stru\StruHyperfOauth\Entity;


use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use Stru\StruHyperfOauth\Model\Client;

class ClientEntity extends Client implements ClientEntityInterface
{
    use ClientTrait,EntityTrait;

    public function getRedirectUri()
    {
        return $this->redirect;
    }
}