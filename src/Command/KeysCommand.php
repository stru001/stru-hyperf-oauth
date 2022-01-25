<?php


namespace Stru\StruHyperfOauth\Command;


use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Utils\Arr;
use phpseclib\Crypt\RSA;
use Psr\Container\ContainerInterface;
use Stru\StruHyperfOauth\StruOauth;

/**
 * Class KeysCommand
 * @package Stru\StruHyperfOauth\Command
 * @Command
 */
class KeysCommand extends HyperfCommand
{

    protected $signature = 'stru:keys
                                      {--force : Overwrite keys they already exist}
                                      {--length=4096 : The length of the private key}';

    protected $description = 'Create the encryption keys for API authentication';
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;
    /**
     * @var RSA
     */
    protected RSA $rsa;

    public function __construct(ContainerInterface $container,RSA $rsa)
    {
        $this->container = $container;
        $this->rsa = $rsa;
        parent::__construct();
    }

    public function handle()
    {
        [$publicKey, $privateKey] = [
            StruOauth::keyPath('stru-public.key'),
            StruOauth::keyPath('stru-private.key'),
        ];

        if ((file_exists($publicKey) || file_exists($privateKey)) && ! $this->input->getOption('force')) {
            $this->error('Encryption keys already exist. Use the --force option to overwrite them.');
        } else {
            $keys = $this->rsa->createKey($this->input ? (int) $this->input->getOption('length') : 4096);

            file_put_contents($publicKey, Arr::get($keys, 'publickey'));
            file_put_contents($privateKey, Arr::get($keys, 'privatekey'));

            $this->info('Encryption keys generated successfully.');
        }
    }
}