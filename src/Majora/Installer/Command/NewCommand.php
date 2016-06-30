<?php
namespace Majora\Installer\Command;

use Distill\Distill;
use Distill\Exception\IO\Input\FileCorruptedException;
use Distill\Exception\IO\Input\FileEmptyException;
use Distill\Exception\IO\Output\TargetDirectoryNotWritableException;
use Distill\Strategy\MinimumSize;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
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

        if (file_exists($input->getArgument('directory'))) {
            throw new \InvalidArgumentException(sprintf('The directory %s already exists', $input->getArgument('directory')));
        }

        $io->writeln(PHP_EOL . ' Downloading Majora Standard Edition...' . PHP_EOL);

        $distill = new Distill();
        $archiveFile = $distill
            ->getChooser()
            ->setStrategy(new MinimumSize())
            ->addFilesWithDifferentExtensions($this->getRemoteFileUrl($input->getArgument('version')), ['zip'])
            ->getPreferredFile()
        ;

        $downloadingProgressBar = null;
        $httpClient = new Client();
        $request = new Request('GET', $archiveFile);
        $response = $httpClient->send($request, [
            RequestOptions::PROGRESS => function($total, $current) use ($io, &$downloadingProgressBar) {
                if ($total <= 0) {
                    return;
                }

                if (!$downloadingProgressBar) {
                    $downloadingProgressBar = $io->createProgressBar($total);
                    $downloadingProgressBar->setPlaceholderFormatterDefinition('max', function (ProgressBar $bar) {
                        return $this->formatSize($bar->getMaxSteps());
                    });
                    $downloadingProgressBar->setPlaceholderFormatterDefinition('current', function (ProgressBar $bar) {
                        return str_pad($this->formatSize($bar->getProgress()), 11, ' ', STR_PAD_LEFT);
                    });
                }
                $downloadingProgressBar->setProgress($current);
            }
        ]);

        $temporaryDownloadedFilePath = rtrim(getcwd(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'.'.uniqid(time()).'-majora.'.pathinfo($archiveFile, PATHINFO_EXTENSION);
        $filesystem = new Filesystem();
        $filesystem->dumpFile($temporaryDownloadedFilePath, $response->getBody()->getContents());

        $io->writeln(PHP_EOL . PHP_EOL . ' Preparing project...' . PHP_EOL);

        $io->note('Extracting...');
        try {
            $distill = new Distill();
            $extractionSucceeded = $distill->extractWithoutRootDirectory($temporaryDownloadedFilePath, $input->getArgument('directory'));
        } catch (FileCorruptedException $e) {
            $io->note('Cleaning...');
            $filesystem->remove($temporaryDownloadedFilePath);
            $filesystem->remove($input->getArgument('directory'));
            throw new \RuntimeException(sprintf(
                "Majora Standard Edition can't be installed because the downloaded package is corrupted"
            ));
        } catch (FileEmptyException $e) {
            $io->note('Cleaning...');
            $filesystem->remove($temporaryDownloadedFilePath);
            $filesystem->remove($input->getArgument('directory'));
            throw new \RuntimeException(sprintf(
                "Majora Standard Edition can't be installed because the downloaded package is empty"
            ));
        } catch (TargetDirectoryNotWritableException $e) {
            $io->note('Cleaning...');
            $filesystem->remove($temporaryDownloadedFilePath);
            $filesystem->remove($input->getArgument('directory'));
            throw new \RuntimeException(sprintf(
                "Majora Standard Edition can't be installed because the installer doesn't have enough\n".
                "permissions to uncompress and rename the package contents."
            ));
        } catch (\Exception $e) {
            $io->note('Cleaning...');
            $filesystem->remove($temporaryDownloadedFilePath);
            $filesystem->remove($input->getArgument('directory'));
            throw new \RuntimeException(sprintf(
                "Majora Standard Edition can't be installed because the downloaded package is corrupted\n".
                "or because the installer doesn't have enough permissions to uncompress and\n".
                "rename the package contents.\n".
                "To solve this issue, check the permissions of the %s directory",
                getcwd()
            ), null, $e);
        }

        if (!$extractionSucceeded) {
            $io->note('Cleaning...');
            $filesystem->remove($temporaryDownloadedFilePath);
            $filesystem->remove($input->getArgument('directory'));
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
            $io->note('Cleaning...');
            $filesystem->remove($temporaryDownloadedFilePath);
            throw new \RuntimeException(sprintf(
                "Majora Standard Edition can't be installed because an error occurred during the dependencies\n".
                "installation. The destination directory has not been deleted."
            ));
        }

        $io->note('Cleaning...');
        $filesystem->remove($temporaryDownloadedFilePath);

        /*
         * todo : prepare the project with Ansible, VagrantFile using a "Preparator" feature
         */

        $io->success([
            sprintf('Majora Standard Edition %s was successfully installed', $input->getArgument('version'))
        ]);
    }

    /**
     * Utility method to show the number of bytes in a readable format.
     *
     * @param int $bytes The number of bytes to format
     *
     * @return string The human readable string of bytes (e.g. 4.32MB)
     */
    protected function formatSize($bytes)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = $bytes ? floor(log($bytes, 1024)) : 0;
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return number_format($bytes, 2).' '.$units[$pow];
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