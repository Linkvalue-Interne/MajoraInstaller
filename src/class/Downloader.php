<?php
require_once '../vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Filesystem\Filesystem;
use Distill\Distill;


/**
 * Class Downloader
 */
class Downloader {

    const MAJORA_PATH = 'https://github.com/LinkValue/majora-standard-edition/archive/master.zip';

    /**
     * @var Filesystem
     */
    private $fs;

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
        $this->downloadZip = 'project.zip';
    }

    /**
     * get Zip majora-standard-edition from repository
     * @return \GuzzleHttp\Psr7\Response
     */
    public function initialize($dir)
    {
        $client = new Client();
        $archive = $dir.DIRECTORY_SEPARATOR.$this->downloadZip;

        try{
            $request = $client->request('GET', self::MAJORA_PATH);
            $response = $request->getBody();

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