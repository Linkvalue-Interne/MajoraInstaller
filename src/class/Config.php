<?php

use Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 *
 * Extract the configuration parameters from a yaml file
 */
class Config
{
    const CONFIG_PATH = '../config';
    const CONFIG_FILE = 'config.yml';

    /**
     * @var array
     */
    public $options;

    /**
     * Constructor: parse the yaml file and set up $options property
     */
    public function __construct()
    {
        $file = self::CONFIG_PATH . '/' . self::CONFIG_FILE;

        $config = Yaml::parse(file_get_contents($file));

        foreach($config as $option => $value) {
            $this->options[$option] = $value;
        }
    }
}