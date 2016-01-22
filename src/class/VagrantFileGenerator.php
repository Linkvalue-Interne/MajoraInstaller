<?php
require_once '../vendor/twig/twig/lib/Twig/Autoloader.php';

class VagrantFileGenerator
{
    const TEMPLATE_FILE = 'Vagrantfile.twig';

    public function __construct()
    {
        Twig_Autoloader::register();
    }

    public function loadEnvironment()
    {
        $loader = new Twig_Loader_Filesystem('../templates');
        return new Twig_Environment($loader);
    }

    public function loadAndWriteTemplate($twig, $options)
    {
        $template = $twig->loadTemplate(self::TEMPLATE_FILE);
        $content = $template->render($options);

        if(file_put_contents('../templates/Vagrantfile', $content)) {
            return true;
        }

        return false;
    }
}