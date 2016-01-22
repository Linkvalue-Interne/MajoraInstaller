<?php
require 'Prompt.php';

class App
{
    public function run()
    {
        $prompt = new Prompt();

        if(!$prompt->run()) {
            echo Prompt::ERROR_PROMPT . "\n";
            return;
        }

        if($this->createDir($prompt->getRootDir())) {
            echo '... root dir just created' . "\n";
        } else {
            echo '... root dir already exists or insufficient permissions' . "\n";
        }
    }

    private function createDir($dir)
    {
        echo '... checking if root dir exists prior creation' . "\n";
        return !is_dir($dir) && @mkdir($dir);
    }
}