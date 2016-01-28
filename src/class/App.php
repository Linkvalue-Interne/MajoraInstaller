<?php
require 'Prompt.php';
require 'VagrantFileGenerator.php';
require 'Downloader.php';

class App
{
    public function run()
    {
        // Retrieving debug mode if needed
        $debugOpt = getopt(null, ['no-interaction::']);
        if (array_key_exists('no-interaction', $debugOpt)) {
            $debug = $debugOpt['no-interaction'];
        }
        // Intanciate Prompt class with debug mode if needed
        $prompt = new Prompt(isset($debug) ? $debug : null);

        // Prompt questions
        if(!$prompt->run()) {
            echo Prompt::ERROR_PROMPT . "\n";
            return;
        }

        // Create root dir if not exists
        if($this->createDir($prompt->getRootDir())) {
            echo '... root dir just created' . "\n";
        } else {
            echo '... root dir already exists or insufficient permissions' . "\n";
            // return;
        }

        // Create Vagrantfile
        $this->createTemplate($prompt);

        // Download Symfony by Majora
        $downloader = new Downloader();
        $downloader->initialize($prompt->getRootDir());
    }

    private function createDir($dir)
    {
        echo '... checking if root dir exists prior creation' . "\n";
        return !is_dir($dir) && @mkdir($dir, 0777);
    }

    private function createTemplate($prompt)
    {
        $vagrantGenerator = new VagrantFileGenerator();

        $twigEnvironment = $vagrantGenerator->loadEnvironment();

        $vagrantGenerator->loadAndWriteTemplate($twigEnvironment, [
            'ip' => $prompt->getIpVagrant(),
            'rootDir' => $prompt->getRootDir(),
            'projectName' => $prompt->getProjectName(),
        ]);
    }
}