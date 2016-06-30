<?php

namespace Majora\Installer\Tests;



use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    private $fs;

    private $rootDir;

    public function setUp()
    {
        $this->rootDir = realpath(__DIR__ . '/../../../../');
        $this->fs = new Filesystem();
    }


    public function testBasicInstallation()
    {
        if(PHP_VERSION_ID < 50600)
        {
            $this->markTestSkipped('Majora requires PHP 5.6 or higher.');
        }

        $this->fs->remove(sprintf('%s/TestProject', $this->rootDir));

        $process = new Process('php bin/majora new TestProject');
        $process->setWorkingDirectory($this->rootDir);
        $process->mustRun();

        $output = $process->getOutput();

        $this->assertContains('Downloading Majora Standard Edition...', $output);
        $this->assertContains('[OK] Majora Standard Edition master was successfully installed', $output);

    }
}