<?php


namespace Stru\StruHyperfOauth\Command;


use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;
use Stru\StruHyperfOauth\Model\Client;
use Stru\StruHyperfOauth\ClientRepository;

/**
 * Class ClientCommand
 * @package Stru\StruHyperfOauth\Command
 * @Command
 */
class ClientCommand extends HyperfCommand
{
    protected $container;

    protected $signature = 'stru:client
            {--personal : Create a personal access token client}
            {--password : Create a password grant client}
            {--client : Create a client credentials grant client}
            {--name= : The name of the client}
            {--provider= : The name of the user provider}
            {--redirect_uri= : The URI to redirect to after authorization }
            {--user_id= : The user ID the client should be assigned to }
            {--public : Create a public client (Auth code grant type only) }';

    protected $description = 'Create a client for issuing access tokens';

    protected ClientRepository $clients;

    public function __construct(ContainerInterface $container,ClientRepository $clients)
    {
        $this->container = $container;
        $this->clients = $clients;
        parent::__construct();
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription($this->description);
    }

    public function handle()
    {
        if ($this->input->getOption('personal')) {
            $this->createPersonalClient($this->clients);
        } elseif ($this->input->getOption('password')) {
            $this->createPasswordClient($this->clients);
        } elseif ($this->input->getOption('client')) {
            $this->createClientCredentialsClient($this->clients);
        } else {
            $this->createAuthCodeClient($this->clients);
        }
    }

    protected function createPersonalClient($clients)
    {
        $name = $this->input->getArgument('name') ?: $this->ask(
            'What should we name the personal access client?',
            config('app_name').' Personal Access Client'
        );

        $client = $clients->createPersonalAccessClient(
            null, $name, 'http://localhost'
        );

        $this->info('Personal access client created successfully.');

        $this->outputClientDetails($client);
    }

    protected function createPasswordClient($clients)
    {
        $name = $this->input->getArgument('name') ?: $this->ask(
            'What should we name the password grant client?',
            config('app_name').' Password Grant Client'
        );

        $providers = array_keys(config('oauth.providers'));

        $provider = $this->input->getArgument('provider') ?: $this->choice(
            'Which user provider should this client use to retrieve users?',
            $providers,
            in_array('users', $providers) ? 'users' : null
        );

        $client = $clients->createPasswordGrantClient(
            null, $name, 'http://localhost', $provider
        );

        $this->info('Password grant client created successfully.');

        $this->outputClientDetails($client);
    }

    protected function createClientCredentialsClient($clients)
    {
        $name = $this->input->getArgument('name') ?: $this->ask(
            'What should we name the client?',
            config('app_name').' ClientCredentials Grant Client'
        );

        $client = $clients->create(
            null, $name, ''
        );

        $this->info('New client created successfully.');

        $this->outputClientDetails($client);
    }

    protected function createAuthCodeClient($clients)
    {
        $userId = $this->input->getOption('user_id') ?: $this->ask(
            'Which user ID should the client be assigned to?'
        );

        $name = $this->input->getOption('name') ?: $this->ask(
            'What should we name the client?'
        );

        $redirect = $this->input->getOption('redirect_uri') ?: $this->ask(
            'Where should we redirect the request after authorization?',
            'http://localhost:9501/auth/callback'
        );

        $client = $clients->create(
            $userId, $name, $redirect, null, false, false, ! $this->input->getOption('public')
        );

        $this->info('New client created successfully.');

        $this->outputClientDetails($client);
    }

    protected function outputClientDetails(Client $client)
    {
        $this->line('<comment>Client ID:</comment> '.$client->id);
        $this->line('<comment>Client secret:</comment> '.$client->plainSecret);
    }
}