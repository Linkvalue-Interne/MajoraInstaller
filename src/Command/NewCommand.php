<?php
namespace Majora\Installer\Command;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class NewCommand
 *
 * @author LinkValue <contact@link-value.fr>
 */
class NewCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('new');
        $this->addArgument('directory', InputArgument::REQUIRED, 'The directory destination');
        $this->addArgument('version', InputArgument::OPTIONAL, 'The version of MajoraStandardEdition', 'master');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $httpClient = new Client();

        $promise = $httpClient->requestAsync(
            'GET',
            $this->getRemoteFileUrl($input->getArgument('version'))
        );

        $promise->wait();
    }

    /**
     * Gets the remote file URL to download
     *
     * @param string $version The version of the file to download
     * @return string
     */
    protected function getRemoteFileUrl($version)
    {
        return sprintf('https://github.com/LinkValue/majora-standard-edition/archive/%s.zip', $version);
    }
}