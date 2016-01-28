<?php

require_once '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class SkeletonInstall
 */
class SkeletonInstall
{
    /**
     * @var array
     */
    protected $skeletons;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var FileSystem
     */
    protected $fs;

    /**
     * Constructor
     *
     * @param $skeletons
     * @param $path
     */
    public function __construct($skeletons, $path)
    {
        $this->skeletons = $skeletons;
        $this->path = $path;
        $this->client = new Client();
        $this->fs = new Filesystem();
    }

    /**
     * Download all skeletons user has input
     *
     * @return $this
     */
    public function run()
    {
        $this->createDirectory();
        foreach ($this->skeletons as $skeletonName => $skeletonUrl) {
            var_dump($skeletonUrl, filter_var($skeletonUrl, FILTER_VALIDATE_URL));
            switch (true) {
                case filter_var($skeletonUrl, FILTER_VALIDATE_URL):
                    $this->addSkeletonByUrl($skeletonName, $skeletonUrl);
                    break;

                default:
                    $this->addSkeletonByMajoraGenerator();
                    break;
            }
        }

        return $this;
    }

    /**
     * Add skeleton in the project from an URL
     *
     * @param $skeletonName
     * @param $skeletonUrl
     * @return bool
     */
    protected function addSkeletonByUrl($skeletonName, $skeletonUrl)
    {
        $downloadedFileName = pathinfo($skeletonName, PATHINFO_EXTENSION);

        try {
            echo sprintf("... Download skeleton: %s\n", $skeletonUrl);
            $request = $this->client->request('GET', $skeletonUrl);
            $response = $this->client->send($request);
        } catch (ClientException $e) {
            throw new \RuntimeException(sprintf(
                "There was an error downloading :  %s",
                $e->getMessage()
            ), $e);
        }

        echo "... Copy skeleton in project \n";
        $this->fs->dumpFile($this->path . '/' . $downloadedFileName, $response->getBody());

        return true;
    }

    /**
     * Use MajoraGenerator to add skeleton to project
     */
    protected function addSkeletonByMajoraGenerator()
    {
        /**
         * TODO : Add when skeleton generator is functional in LinkValue/MajoraGeneratorBundle
         */
    }

    /**
     * Create new directory
     *
     * @return bool
     */
    protected function createDirectory()
    {
        return mkdir($this->path);
    }
}
