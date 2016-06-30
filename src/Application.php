<?php
namespace Majora\Installer;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('MajoraInstaller', '{{version}}');
    }
}