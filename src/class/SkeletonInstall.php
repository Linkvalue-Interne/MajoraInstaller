<?php

require_once '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Filesystem\Filesystem;
use Distill\Distill;

class SkeletonInstall
{
    protected $skeletons;

    protected $path;

    protected $client;

    protected $fs;

    protected $distill;

    public function __construct($skeletons, $path)
    {
        $this->skeletons = $skeletons;
        $this->path = $path;
        $this->client = new Client();
        $this->fs = new Filesystem();
        $this->distill = new Distill();
    }

    public function run()
    {
        $this->createDirectory();
        foreach ($this->skeletons as $skeletonName => $skeletonUrl) {
            var_dump($skeletonUrl, filter_var($skeletonUrl, FILTER_VALIDATE_URL));
            switch (true) {
                case filter_var($skeletonUrl, FILTER_VALIDATE_URL):
                    $zipPath = $this->addSkeletonByUrl($skeletonName, $skeletonUrl);
                    break;

                default:
                    $zipPath = $this->addSkeletonByMajoraGenerator();
                    break;
            }

            // checking if directory with same name exists:
            $zip = new ZipArchive();
            $zip->open($zipPath);
            $rootDirectory = rtrim($zip->statIndex(0)['name'], '/');
            var_dump(is_dir(sprintf('%s/%s', $this->path, $rootDirectory)));
            echo "\n";
            print_r($this->path);
            if($this->fs->exists(sprintf('%s/%s', $this->path, $rootDirectory))) {
                throw new \RuntimeException(
                    sprintf('The skeleton %s already exists in project', $rootDirectory)
                );
            }


            $this->distill->extract($zipPath, $this->path);
            $this->fs->remove($zipPath);
        }

        return $this;
    }

    /**
     * Add skeleton in new project since an URL
     */
    protected function addSkeletonByUrl($skeletonName, $skeletonUrl)
    {
        $downloadedFileName = pathinfo($skeletonName, PATHINFO_FILENAME);
        $zipName = explode('/', $skeletonUrl);
        $zipName = array_pop($zipName);
        $extension = explode('.', $zipName);
        $extension = array_pop($extension);
        unset($zipName);


        try {
            echo sprintf('... Download skeleton: %s' . "\n", $skeletonUrl);
            $request = $this->client->request('GET', $skeletonUrl);
        } catch (ClientException $e) {
            throw new \RuntimeException(sprintf(
                'There was an error downloading :  %s',
                $e->getMessage()
            ), $e);
        }

        echo '... Copy skeleton to project ' . "\n";
        // TODO: throw Exception if skeleton already exists
        $filename = $this->path . '/' . $downloadedFileName . '.' . $extension;




        try {
            $this->fs->dumpFile($filename, $request->getBody());
        } catch(\Exception $e) {
            throw new \RuntimeException(
                sprintf('An error occured while copying zipfile into project : %s', $e->getMessage())
            );
        }

        return $filename;
    }

    protected function addSkeletonByMajoraGenerator()
    {
        /**
         * TODO : Add when skeleton generator is functional in LinkValue/MajoraGeneratorBundle
         */
    }

    protected function createDirectory()
    {
        return mkdir($this->path);
    }
}
