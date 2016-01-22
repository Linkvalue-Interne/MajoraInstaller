<?php
require 'Prompt.php';
require 'VagrantFileGenerator.php';
require 'Downloader.php';

class App
{
    public function run()
    {
        $prompt = new Prompt();
        $vagrantGenerator = new VagrantFileGenerator();

        if(!$prompt->run()) {
            echo Prompt::ERROR_PROMPT . "\n";
            return;
        }

        if($this->createDir($prompt->getRootDir())) {
            echo '... root dir just created' . "\n";
        } else {
            echo '... root dir already exists or insufficient permissions' . "\n";
            // return;
        }

        $twigEnvironment = $vagrantGenerator->loadEnvironment();
        $vagrantGenerator->loadAndWriteTemplate($twigEnvironment, [
            'ip' => $prompt->getIpVagrant(),
            'rootDir' => $prompt->getRootDir(),
            'projectName' => $prompt->getProjectName(),
        ]);
        $downloader = new Downloader();
        $downloader->initialize($prompt->getRootDir());
    }

    private function createDir($dir)
    {
        echo '... checking if root dir exists prior creation' . "\n";
        return !is_dir($dir) && @mkdir($dir, 0777);
    }
}