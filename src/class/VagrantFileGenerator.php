<?php
require_once '../vendor/twig/twig/lib/Twig/Autoloader.php';
require_once 'Config.php';

class VagrantFileGenerator
{
    private $config;

    public function __construct()
    {
        Twig_Autoloader::register();
        $this->config = new Config();
    }

    public function loadEnvironment()
    {
        $loader = new Twig_Loader_Filesystem($this->config->options['template_base_dir']);
        return new Twig_Environment($loader);
    }

    public function loadAndWriteTemplate($twig, $options)
    {
        $template = $twig->loadTemplate($this->config->options['vagrant_template_file']);
        $content = $template->render($options);

        if(file_put_contents($this->config->options['vagrant_file_path'], $content)) {
            return true;
        }

        return false;
    }
}