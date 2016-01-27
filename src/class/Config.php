<?php
require_once '../vendor/symfony/yaml/Yaml.php';

use Symfony\Component\Yaml\Yaml;

class Config
{
    const CONFIG_PATH = '../config';
    const CONFIG_FILE = 'config.yml';

    public $options;

    public function __construct()
    {
        $file = self::CONFIG_PATH . '/' . self::CONFIG_FILE;

        $config = Yaml::parse(file_get_contents($file));

        foreach($config as $option => $value) {
            $this->options[$option] = $value;
        }
    }
}