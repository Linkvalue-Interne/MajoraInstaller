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
        echo '... Customize VagrantFile' . "\n";
        $template = $twig->loadTemplate($this->config->options['vagrant_template_file']);
        $content = $template->render($options);

        $path = sprintf("%s/%s", $options["rootDir"], $this->config->options['vagrant_file_name']);

        @unlink($path);

        if(!file_put_contents($path, $content)) {
            echo "Erreur creation VagrantFile";
            return false;
        }

        return false;
    }
}
