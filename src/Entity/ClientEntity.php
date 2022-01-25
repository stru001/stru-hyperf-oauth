<?php


namespace Stru\StruHyperfOauth\Entity;


use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;

class ClientEntity implements ClientEntityInterface
{
    use ClientTrait;

    protected $identifier;
    /**
     * @var mixed|null
     */
    protected $provider;

    public function __construct($identifier,$name,$redirectUri,$isConfidential = false,$provider = null)
    {
        $this->setIdentifier((string)$identifier);

        $this->name = $name;
        $this->isConfidential = $isConfidential;
        $this->redirectUri = $redirectUri;
        $this->provider = $provider;
    }

    public function getIdentifier()
    {
        return (string)$this->identifier;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }
}