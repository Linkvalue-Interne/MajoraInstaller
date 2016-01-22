<?php
require_once '../vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Filesystem\Filesystem;


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
     * Downloader constructor.
     */
    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    /**
     * get Zip majora-standard-edition from repository
     * @return \GuzzleHttp\Psr7\Response
     */
    public function initialize($dir)
    {
        $client = new Client();

        try{
            $request = $client->request('GET', self::MAJORA_PATH);
            $response = $request->getBody();
            $this->fs->dumpFile($dir.'/test.zip',$response);
        }
        catch (ClientException $e) {
            throw new \RuntimeException(sprintf(
                "There was an error downloading :  %s",
                $e->getMessage()
            ), $e);
        }
    }
}