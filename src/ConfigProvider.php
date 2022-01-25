<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Stru\StruHyperfOauth;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Stru\StruHyperfOauth\Command\ClientCommand;
use Stru\StruHyperfOauth\Grant\AuthCodeGrantFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                AuthorizationServer::class => AuthorizationServerFactory::class,
                ClientRepositoryInterface::class => ClientRepository::class,
                AccessTokenRepositoryInterface::class => AccessTokenRepository::class,
                AuthCodeRepositoryInterface::class => AuthCodeRepository::class,
                RefreshTokenRepositoryInterface::class => RefreshTokenRepository::class,
                ScopeRepositoryInterface::class => ScopeRepository::class,
                UserRepositoryInterface::class => UserRepository::class,
                //Grant
                AuthCodeGrant::class => AuthCodeGrantFactory::class,
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for oauth.',
                    'source' => __DIR__ . '/../config/oauth.php',
                    'destination' => BASE_PATH . '/config/autoload/oauth.php',
                ],
                [
                    'id' => 'migration',
                    'description' => 'The migration for oauth.',
                    'source' => __DIR__ . '/../database/migrations/2022_01_14_000001_create_oauth_auth_codes_table.php',
                    'destination' => BASE_PATH . '/migrations/2022_01_14_000001_create_oauth_auth_codes_table.php',
                ],
                [
                    'id' => 'migration',
                    'description' => 'The migration for oauth.',
                    'source' => __DIR__ . '/../database/migrations/2022_01_14_000002_create_oauth_access_tokens_table.php',
                    'destination' => BASE_PATH . '/migrations/2022_01_14_000002_create_oauth_access_tokens_table.php',
                ],
                [
                    'id' => 'migration',
                    'description' => 'The migration for oauth.',
                    'source' => __DIR__ . '/../database/migrations/2022_01_14_000003_create_oauth_refresh_tokens_table.php',
                    'destination' => BASE_PATH . '/migrations/2022_01_14_000003_create_oauth_refresh_tokens_table.php',
                ],
                [
                    'id' => 'migration',
                    'description' => 'The migration for oauth.',
                    'source' => __DIR__ . '/../database/migrations/2022_01_14_000004_create_oauth_clients_table.php',
                    'destination' => BASE_PATH . '/migrations/2022_01_14_000004_create_oauth_clients_table.php',
                ],
                [
                    'id' => 'migration',
                    'description' => 'The migration for oauth.',
                    'source' => __DIR__ . '/../database/migrations/2022_01_14_000005_create_oauth_personal_access_clients_table.php',
                    'destination' => BASE_PATH . '/migrations/2022_01_14_000005_create_oauth_personal_access_clients_table.php',
                ],
            ]
        ];
    }
}
