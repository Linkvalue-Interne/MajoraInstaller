<?php
require_once '../vendor/autoload.php';
require_once 'Config.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Filesystem\Filesystem;
use Distill\Distill;

/**
 * Class Downloader
 */
class Downloader
{

    /**
     * @var Filesystem
     */
    private $fs;

    private $config;

    /**
     * @var string
     */
    private $downloadZip;

    /**
     * Downloader constructor.
     */
    public function __construct()
    {
        $this->fs = new Filesystem();
        $this->config = new Config();
        $this->downloadZip = 'project.zip';
    }

    /**
     * get Zip majora-standard-edition from repository
     * @return \GuzzleHttp\Psr7\Response
     */
    public function initialize($dir)
    {
        $client = new Client();
        echo '... downloading Majora' . "\n";
        $archive = $dir.DIRECTORY_SEPARATOR.$this->downloadZip;

        try{
            $request = $client->request('GET', $this->config->majora_path);
            $response = $request->getBody();
            echo '... Majora succesfully downloaded' . "\n";
            $this->fs->dumpFile($dir . '/' . $this->config->local_zip_name, $response);
            echo '... Majora succesfully copied' . "\n";

            $this->fs->dumpFile($archive,$response);
            $distill = new Distill();
            $extractionSucceeded = $distill->extractWithoutRootDirectory($archive, $dir);
        }
        catch (ClientException $e) {
            throw new \RuntimeException(sprintf(
                "There was an error downloading :  %s",
                $e->getMessage()
            ), $e);
        }
    }
}