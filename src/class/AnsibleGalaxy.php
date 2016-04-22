<?php

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Dumper;

/**
 * Class AnsibleGalaxy
 */
class AnsibleGalaxy
{
    /**
     * Ansible Galaxy filename
     */
    const APP_YAML = 'app.yml';

    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $roles = [];

    /**
     * Class constructor
     *
     * @param array $roles
     */
    public function __construct(array $roles = [])
    {
        $this->roles = $roles;
        $this->config = new Config();
    }


    public function install($directory)
    {
        $ansiblePath = sprintf('%s/%s', $directory, $this->config->options['ansible_path']);

        $appYaml = $this->retrieveAppYaml($ansiblePath);

        if (empty($this->roles)) {
            return $this->useAndInstallDefaultRoles($appYaml, $ansiblePath);
        }

        $appYaml = $this->addRolesToYaml($appYaml);

        return $this->saveAppYaml($appYaml, $ansiblePath);
    }

    private function useAndInstallDefaultRoles($yaml, $directory)
    {
        return 5;
    }

    /**
     * Retrieves the contents of the Ansible Galaxy main file
     *
     * @param $directory
     * @return array
     */
    private function retrieveAppYaml($directory)
    {

        return Yaml::parse(file_get_contents(sprintf('%s/%s', $directory, self::APP_YAML)));
    }

    /**
     * Merge the initial roles array to the one passed via CLI
     * and returns the full content of the YAML file
     *
     * @param $yml
     * @return mixed
     */
    private function addRolesToYaml($yml)
    {
        $yml[0]['roles'] = array_merge($yml[0]['roles'], $this->roles);

        return $yml;
    }

    private function saveAppYaml($yml, $directory)
    {
        $yaml = (new Dumper)
            ->dump($yml, 3)
        ;

        if (file_put_contents(sprintf('%s/%s', $directory, self::APP_YAML), $yaml)) {
            return count($yml[0]['roles']);
        }

        return false;

    }
}