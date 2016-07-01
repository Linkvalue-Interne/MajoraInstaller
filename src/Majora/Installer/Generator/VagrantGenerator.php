<?php

namespace Majora\Installer\Generator;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class VagrantGenerator
{
    /**
     * Load Twig environment
     *
     * @return Twig_Environment
     */
    public function loadEnvironment()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__.DIRECTORY_SEPARATOR.'../Templates');

        return new \Twig_Environment($loader);
    }


    /**
     * Load and write template into its final directory
     *
     * @param $twig
     * @param $options
     * @return bool
     * @throws FileException
     */
    public function loadAndWriteTemplate($twig, $options)
    {
        $template = $twig->loadTemplate('Vagrantfile.twig');
        $content = $template->render($options);
        $path = sprintf("%s/%s", getcwd().DIRECTORY_SEPARATOR.$options['projectName'], 'Vagrantfile');
        @unlink($path);

        if(!file_put_contents($path, $content)) {
            throw new FileException(sprintf('%s', 'Error when creating the VagrantFile'));
        }

        return true;
    }
}