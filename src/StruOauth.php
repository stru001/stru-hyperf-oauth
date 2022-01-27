<?php


namespace Stru\StruHyperfOauth;


use Carbon\Carbon;
use Hyperf\Utils\ApplicationContext;
use League\OAuth2\Server\ResourceServer;
use Mockery;
use Psr\Http\Message\ServerRequestInterface;
use Stru\StruHyperfOauth\Exception\RuntimeException;
use Stru\StruHyperfOauth\Scope;

class StruOauth
{
    public static $implicitGrantEnable = false;

    public static $personalAccessClientId;

    public static $defaultScope;

    public static $scopes = [];

    public static $tokenExpireAt;
    public static $refreshTokensExpireAt;
    public static $personalAccessTokensExpireAt;

    public static $cookie = 'stru_token';

    public static $keyPath;

    public static $clientUuids = false;
    public static $clientModel = 'Stru\StruHyperfOauth\Model\Client';
    public static $authCodeModel = 'Stru\StruHyperfOauth\Model\AuthCode';
    public static $personalAccessClientModel = 'Stru\StruHyperfOauth\Model\PersonalAccessClient';
    public static $tokenModel = 'Stru\StruHyperfOauth\Model\AccessToken';
    public static $refreshTokenModel = 'Stru\StruHyperfOauth\Model\RefreshToken';

    public static $runsMigrations = true;
    public static $unserializesCookies = false;
    public static $hashesClientSecrets = false;
    public static $withInheriteScopes = false;
    public static $tokensExpireAt;
    public static bool $ignoreCsrfToken;

    public static function personalAccessClinetId($clientId)
    {
        static::$personalAccessClientId = $clientId;

        return new static;
    }

    public static function setDefaultScope($scope)
    {
        static::$defaultScope = is_array($scope) ? implode(' ',$scope) : $scope;
    }

    public static function scopeIds()
    {
        return static::scopes()->pluck('id')->values()->all();
    }

    public static function scopes()
    {
        return collect(static::$scopes)->map(function ($description,$id){
            return new Scope($id,$description);
        })->values();
    }

    public static function hasScope($id)
    {
        return $id === '*' || array_key_exists($id,static::$scopes);
    }

    public static function scopesFor(array $ids)
    {
        return collect($ids)->map(function ($id){
            if (isset(static::$scopes[$id])){
                return new Scope($id,static::$scopes[$id]);
            }
            return null;
        })->filter()->values()->all();
    }

    public static function tokenCan(array $scopes)
    {
        static::$scopes = $scopes;
    }

    public static function tokensExpireIn(\DateTimeInterface $date = null)
    {
        if (is_null($date)) {
            return static::$tokensExpireAt
                ? Carbon::now()->diff(static::$tokensExpireAt)
                : new \DateInterval('P1Y');
        }

        static::$tokensExpireAt = $date;

        return new static;
    }

    public static function refreshTokensExpireIn(\DateTimeInterface $date = null)
    {
        if (is_null($date)) {
            return static::$refreshTokensExpireAt
                ? Carbon::now()->diff(static::$refreshTokensExpireAt)
                : new \DateInterval('P1Y');
        }

        static::$refreshTokensExpireAt = $date;

        return new static;
    }

    public static function personalAccessTokensExpireIn(\DateTimeInterface $date = null)
    {
        if (is_null($date)) {
            return static::$personalAccessTokensExpireAt
                ? Carbon::now()->diff(static::$personalAccessTokensExpireAt)
                : new \DateInterval('P1Y');
        }

        static::$personalAccessTokensExpireAt = $date;

        return new static;
    }

    public static function cookie($cookie = null)
    {
        if (is_null($cookie)) {
            return static::$cookie;
        }

        static::$cookie = $cookie;

        return new static;
    }

    public static function ignoreCsrfToken($ignoreCsrfToken = true)
    {
        static::$ignoreCsrfToken = $ignoreCsrfToken;

        return new static;
    }


//    public static function actingAs($user, $scopes = [], $guard = 'api')
//    {
//        $token = Mockery::mock(self::tokenModel())->shouldIgnoreMissing(false);
//
//        foreach ($scopes as $scope) {
//            $token->shouldReceive('can')->with($scope)->andReturn(true);
//        }
//
//        $user->withAccessToken($token);
//
//        if (isset($user->wasRecentlyCreated) && $user->wasRecentlyCreated) {
//            $user->wasRecentlyCreated = false;
//        }
//
//        app('auth')->guard($guard)->setUser($user);
//
//        app('auth')->shouldUse($guard);
//
//        return $user;
//    }

//    public static function actingAsClient($client, $scopes = [])
//    {
//        $container = ApplicationContext::getContainer();
//
//        $token = $container->get(self::tokenModel());
//
//        $token->client = $client;
//        $token->scopes = $scopes;
//
//        $mock = Mockery::mock(ResourceServer::class);
//        $mock->shouldReceive('validateAuthenticatedRequest')
//            ->andReturnUsing(function (ServerRequestInterface $request) use ($token) {
//                return $request->withAttribute('oauth_client_id', $token->client->id)
//                    ->withAttribute('oauth_access_token_id', $token->id)
//                    ->withAttribute('oauth_scopes', $token->scopes);
//            });
//
//        $container->get(ResourceServer::class, $mock);
//
//        $mock = Mockery::mock(TokenRepository::class);
//        $mock->shouldReceive('find')->andReturn($token);
//
//        app()->instance(TokenRepository::class, $mock);
//
//        return $client;
//    }

    public static function loadKeysFrom($path)
    {
        static::$keyPath = $path;
    }


    public static function keyPath($file)
    {
        $path = BASE_PATH.'/storage';

        $file = ltrim($file, '/\\');

        if (!is_dir($path)){
            try {
                mkdir($path);
            }catch (RuntimeException $e){
                throw new RuntimeException(
                    'Please create a storage directory at the root of the project'
                );
            }
        }
        return static::$keyPath
            ? rtrim(static::$keyPath, '/\\').DIRECTORY_SEPARATOR.$file
            : BASE_PATH.'/storage/'.$file;
    }


    public static function useAuthCodeModel($authCodeModel)
    {
        static::$authCodeModel = $authCodeModel;
    }

    public static function authCodeModel()
    {
        return static::$authCodeModel;
    }

    public static function authCode()
    {
        return new static::$authCodeModel;
    }

    public static function useClientModel($clientModel)
    {
        static::$clientModel = $clientModel;
    }

    public static function clientModel()
    {
        return static::$clientModel;
    }

    public static function client()
    {
        return new static::$clientModel;
    }

    public static function clientUuids()
    {
        return static::$clientUuids;
    }

    public static function setClientUuids($value)
    {
        static::$clientUuids = $value;
    }

    public static function usePersonalAccessClientModel($clientModel)
    {
        static::$personalAccessClientModel = $clientModel;
    }

    public static function personalAccessClientModel()
    {
        return static::$personalAccessClientModel;
    }

    public static function personalAccessClient()
    {
        return new static::$personalAccessClientModel;
    }

    public static function useTokenModel($tokenModel)
    {
        static::$tokenModel = $tokenModel;
    }

    public static function tokenModel()
    {
        return static::$tokenModel;
    }

    public static function token()
    {
        return new static::$tokenModel;
    }

    public static function useRefreshTokenModel($refreshTokenModel)
    {
        static::$refreshTokenModel = $refreshTokenModel;
    }

    public static function refreshTokenModel()
    {
        return static::$refreshTokenModel;
    }

    public static function refreshToken()
    {
        return new static::$refreshTokenModel;
    }

    public static function hashClientSecrets()
    {
        static::$hashesClientSecrets = true;

        return new static;
    }

    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;

        return new static;
    }

    public static function withCookieSerialization()
    {
        static::$unserializesCookies = true;

        return new static;
    }

    public static function withoutCookieSerialization()
    {
        static::$unserializesCookies = false;

        return new static;
    }
}