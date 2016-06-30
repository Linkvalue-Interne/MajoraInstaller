<?php
namespace Majora\Installer\Command;

use Distill\Distill;
use Distill\Exception\IO\Input\FileCorruptedException;
use Distill\Exception\IO\Input\FileEmptyException;
use Distill\Exception\IO\Output\TargetDirectoryNotWritableException;
use Distill\Strategy\MinimumSize;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Majora\Installer\Download\Downloader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * Class NewCommand
 *
 * @author LinkValue <contact@link-value.fr>
 */
class NewCommand extends Command
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $destinationPath;

    /**
     * @var string
     */
    private $version;

    /**
     * @var Downloader
     */
    private $majoraDownloader;

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('new');
        $this->addArgument('destination', InputArgument::REQUIRED, 'The directory destination');
        $this->addArgument('version', InputArgument::OPTIONAL, 'The version of MajoraStandardEdition', 'master');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->destinationPath = $input->getArgument('destination');
        $this->version = $input->getArgument('version');

        if (file_exists(  $this->destinationPath)) {
            throw new \InvalidArgumentException(sprintf('The directory %s already exists',   $this->destinationPath));
        }

        $this->filesystem = new Filesystem();

        $io->writeln(PHP_EOL . ' Downloading Majora Standard Edition...' . PHP_EOL);
        $distill = new Distill();
        $archiveFile = $distill
            ->getChooser()
            ->setStrategy(new MinimumSize())
            ->addFilesWithDifferentExtensions($this->getRemoteFileUrl($this->version), ['zip'])
            ->getPreferredFile()
        ;
        $temporaryDownloadedFilePath = rtrim(getcwd(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'.'.uniqid(time()).'.'.pathinfo($archiveFile, PATHINFO_EXTENSION);
        $this->majoraDownloader = new Downloader($archiveFile, $temporaryDownloadedFilePath, $output);
        try {
            $this->majoraDownloader->download();
        } catch (RequestException $requestException) {
            throw new \RuntimeException('Majora Standard Edition can not be downloaded');
        }
        if (!$this->majoraDownloader->isDownloaded()) {
            //todo: soft cleanUp
            throw new \RuntimeException('Majora Standard Edition can not be downloaded');
        }


        $io->writeln(PHP_EOL . PHP_EOL . ' Preparing project...' . PHP_EOL);

        $io->note('Extracting...');
        try {
            $distill = new Distill();
            $extractionSucceeded = $distill->extractWithoutRootDirectory($this->majoraDownloader->getDestinationFile(), $this->destinationPath);
        } catch (FileCorruptedException $e) {
            $this->clean(true);
            throw new \RuntimeException(sprintf(
                "Majora Standard Edition can't be installed because the downloaded package is corrupted"
            ));
        } catch (FileEmptyException $e) {
            $this->clean(true);
            throw new \RuntimeException(sprintf(
                "Majora Standard Edition can't be installed because the downloaded package is empty"
            ));
        } catch (TargetDirectoryNotWritableException $e) {
            $this->clean(true);
            throw new \RuntimeException(sprintf(
                "Majora Standard Edition can't be installed because the installer doesn't have enough\n".
                "permissions to uncompress and rename the package contents."
            ));
        } catch (\Exception $e) {
            $this->clean(true);
            throw new \RuntimeException(sprintf(
                "Majora Standard Edition can't be installed because the downloaded package is corrupted\n".
                "or because the installer doesn't have enough permissions to uncompress and\n".
                "rename the package contents.\n".
                "To solve this issue, check the permissions of the %s directory",
                getcwd()
            ), null, $e);
        }

        if (!$extractionSucceeded) {
            $this->clean(true);
            throw new \RuntimeException(sprintf(
                "Majora Standard Edition can't be installed because the downloaded package is corrupted\n".
                "or because the uncompress commands of your operating system didn't work."
            ));
        }

        $io->note('Installing dependencies (this operation may take a while)...');
        $composerProcess = new Process(
            '/usr/bin/env composer install -o',
            getcwd() . DIRECTORY_SEPARATOR . $input->getArgument('directory'),
            null,
            null,
            null
        );
        if ($output->getVerbosity() === OutputInterface::VERBOSITY_VERY_VERBOSE) {
            $composerProcess->run(
                function ($type, $buffer) use ($output) {
                    $output->write($buffer);
                }
            );
        } else {
            $composerProcess->run();
        }

        if ($composerProcess->getExitCode() != 0) {
            $this->clean();
            throw new \RuntimeException(sprintf(
                "Majora Standard Edition can't be installed because an error occurred during the dependencies\n".
                "installation. The destination directory has not been deleted."
            ));
        }

        $io->note('Cleaning...');
        $this->clean();

        /*
         * todo : prepare the project with Ansible, VagrantFile using a "Preparator" feature
         */

        $io->success([
            sprintf('Majora Standard Edition %s was successfully installed', $this->version)
        ]);
    }

   protected function clean($removeDestinationPath = false)
   {
       $this->filesystem->remove($this->majoraDownloader->getDestinationFile());
       if ((bool)$removeDestinationPath) {
           $this->filesystem->remove($this->destinationPath);
       }
   }

    /**
     * Gets the remote file URL to download
     *
     * @param string $version The version of the file to download
     * @return string
     */
    protected function getRemoteFileUrl($version)
    {
        return sprintf('https://github.com/LinkValue/majora-standard-edition/archive/%s', $version);
    }
}