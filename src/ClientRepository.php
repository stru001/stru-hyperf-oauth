<?php


namespace Stru\StruHyperfOauth;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Stru\StruHyperfOauth\Entity\ClientEntity;
use Stru\StruHyperfOauth\Exception\RuntimeException;
use Stru\StruHyperfOauth\Model\Client;

class ClientRepository implements ClientRepositoryInterface
{

    protected $clients;

    public function find($id)
    {
        $client = StruOauth::client();

        return $client->where($client->getKeyName(),$id)->first();
    }

    public function findActive($id)
    {
        $client = $this->find($id);

        return $client && ! $client->revoked ? $client : null;
    }

    public function findForUser($clientId, $userId)
    {
        $client = StruOauth::client();

        return $client
            ->where($client->getKeyName(), $clientId)
            ->where('user_id', $userId)
            ->first();
    }

    public function forUser($userId)
    {
        return StruOauth::client()
            ->where('user_id', $userId)
            ->orderBy('name', 'asc')->get();
    }

    public function activeForUser($userId)
    {
        return $this->forUser($userId)->reject(function ($client) {
            return $client->revoked;
        })->values();
    }

    public function personalAccessClient()
    {
        if (StruOauth::$personalAccessClientId) {
            return $this->find(StruOauth::$personalAccessClientId);
        }

        $client = StruOauth::personalAccessClient();

        if (! $client->exists()) {
            throw new RuntimeException('Personal access client not found. Please create one.');
        }

        return $client->orderBy($client->getKeyName(), 'desc')->first()->client;
    }

    public function create($userId, $name, $redirect, $provider = null, $personalAccess = false, $password = false, $confidential = true)
    {
        $secret = self::random(40);
        $client = StruOauth::client()->forceFill([
            'user_id' => $userId,
            'name' => $name,
            'secret' => ($confidential || $personalAccess) ? $secret : null,
            'provider' => $provider,
            'redirect' => $redirect,
            'personal_access_client' => $personalAccess,
            'password_client' => $password,
            'revoked' => false,
        ]);

        $client->save();
        // 设置临时属性
        $client->setSecretAttribute($secret);
        return $client;
    }

    public function createPersonalAccessClient($userId, $name, $redirect)
    {
        return tap($this->create($userId, $name, $redirect, null, true), function ($client) {
            $accessClient = StruOauth::personalAccessClient();
            $accessClient->client_id = $client->id;
            $accessClient->save();
        });
    }

    public function createPasswordGrantClient($userId, $name, $redirect, $provider = null)
    {
        return $this->create($userId, $name, $redirect, $provider, false, true);
    }

    public function update(Client $client, $name, $redirect)
    {
        $client->forceFill([
            'name' => $name, 'redirect' => $redirect,
        ])->save();

        return $client;
    }

    public function regenerateSecret(Client $client)
    {
        $client->forceFill([
            'secret' => self::random(40),
        ])->save();

        return $client;
    }

    public function revoked($id)
    {
        $client = $this->find($id);

        return is_null($client) || $client->revoked;
    }

    public function delete(Client $client)
    {
        $client->tokens()->update(['revoked' => true]);

        $client->forceFill(['revoked' => true])->save();
    }

    private static function random($length = 16)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    public function getClientEntity($clientIdentifier)
    {
        $entity = new ClientEntity();

        $client = $entity->find($clientIdentifier);

        if ($client){
            $client->setIdentifier($clientIdentifier);
        }
        $this->clients[$clientIdentifier] = [
            'name' => $client->name,
            'secret' => $client->secret,
            'redirect' => $client->redirect,
            'is_confidential' => false
        ];

        return $client;
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        // Check if client is registered
        if (\array_key_exists($clientIdentifier, $this->clients) === false) {
            return false;
        }
        // Check secret is eq
        if (($clientSecret !== $this->clients[$clientIdentifier]['secret'])) {
            return false;
        }

        return true;
    }
}