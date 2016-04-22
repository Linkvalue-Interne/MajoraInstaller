<?php

/**
 * Class VagrantFileGenerator
 *
 * Generates the Vagrantfile for the project
 */
class VagrantFileGenerator
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Constructor
     */
    public function __construct()
    {
        Twig_Autoloader::register();
        $this->config = new Config();
    }

    /**
     * Load Twig environment
     *
     * @return Twig_Environment
     */
    public function loadEnvironment()
    {
        $loader = new Twig_Loader_Filesystem($this->config->options['template_base_dir']);
        return new Twig_Environment($loader);
    }

    /**
     * Load and write template into its final directory
     *
     * @param $twig
     * @param $options
     * @return bool
     */
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

        return true;
    }
}
