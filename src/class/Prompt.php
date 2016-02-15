<?php

/**
 * Class Prompt
 */
class Prompt
{
    const PROMPT_PROJECT_NAME = 'What is the global project name ?';
    const PROMPT_ROOT_DIR = 'What would be the root dir of the installation ?';
    const PROMPT_IP_VAGRANT = 'What would be the IP address of the VM ?';
    const PROMPT_HOW_MANY_SKELETONS = 'How many skeletons will be in use ?';
    const PROMPT_SKELETONS = '- Input skeleton\'s name & URL n°%s (ex: name=>URL)';
    const PROMPT_HOW_MANY_ROLES = 'How many Ansible Galaxy roles do you want to use?';
    const PROMPT_ROLES = 'Input Ansible role\'s name n°%s';

    const ERROR_PROMPT = '/!\ You made an error in providing the required information.';

    /**
     * @var string
     */
    private $projectName;

    /**
     * @var string
     */
    private $rootDir = '.';

    /**
     * @var string
     */
    private $ipVagrant;

    /**
     * @var array
     */
    private $skeletons = [];
    private $roles = [];

    /**
     * @var bool
     */
    private $debug;

    /*
     * Define if the prompt will wait for user inputs or not.
     */
    private $interaction;

    /**
     * Constructor
     *
     * @param bool|false $debug
     */
    public function __construct($debug = false) {
        $this->debug = $debug;
        $this->interaction = true;
    }

    /**
     * Run the prompt command
     *
     * @return $this|bool
     */
    public function run()
    {
        if(!$prompt = $this->prompt()) {
            return false;
        }

        $infos = (new \ReflectionClass(self::class))->getProperties();
        foreach($infos as $property) {
            if(array_key_exists($property->getName(), $prompt)) {
                $this->{$property->getName()} = $prompt[$property->getName()];
            }
        }

        return $this;
    }

    /**
     * Prompts user for information
     *
     * @return array|bool
     */
    private function prompt()
    {
        if(!$this->interaction) {
            return [
                'projectName' => 'readyToCode',
                'rootDir' => './test',
                'ipVagrant' => '127.0.0.1',
                'skeletons' => [
                    'basic' => 'https://github.com/stevedavid/basic-skeleton/archive/master.zip',
                ],
                'roles' => ['drush'],
            ];
        }

        if(!$name = trim(readline(self::PROMPT_PROJECT_NAME."\n"))) {
            return false;
        }

        if(!$dir = trim(readline(self::PROMPT_ROOT_DIR."\n"))) {
            return false;
        }

        if(!$ip = trim(readline(self::PROMPT_IP_VAGRANT."\n"))) {
            return false;
        }

        if(filter_var($ip, FILTER_VALIDATE_IP) === false) {
            return false;
        }

        if(filter_var($nbSkeletons = trim(readline(self::PROMPT_HOW_MANY_SKELETONS."\n")), FILTER_VALIDATE_INT) === false) {
            return false;
        }

        if(is_null($nbSkeletons)) {
            return false;
        }

        $skeletons = [];
        for ($i = 0; $i < $nbSkeletons; $i++) {
            $skeleton = trim(readline(sprintf(self::PROMPT_SKELETONS, $i)."\n"));
            $data = explode("=>", $skeleton);
            $skeletons[$data[0]] = count($data) == 2 ? $data[1] : $data[0];
        }

        if(filter_var($nbRoles = trim(readline(self::PROMPT_HOW_MANY_ROLES . "\n")), FILTER_VALIDATE_INT) === false) {
            return false;
        }

        if(is_null($nbRoles)) {
            return false;
        }

        $roles = [];
        for ($i = 0; $i < $nbRoles; $i++) {
            $roles[] = trim(readline(sprintf(self::PROMPT_ROLES . "\n", $i)));
        }

        return [
            'projectName'   => $name,
            'rootDir'       => $dir,
            'ipVagrant'     => $ip,
            'skeletons'     => $skeletons,
            'roles'         => $roles,
        ];
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * @param $rootDir
     * @return $this
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIpVagrant()
    {
        return $this->ipVagrant;
    }

    /**
     * @param $ipVagrant
     * @return $this
     */
    public function setIpVagrant($ipVagrant)
    {
        $this->ipVagrant = $ipVagrant;

        return $this;
    }

    /**
     * @return array
     */
    public function getSkeletons()
    {
        return $this->skeletons;
    }

    /**
     * @param $skeletons
     * @return $this
     */
    public function setSkeletons($skeletons)
    {
        $this->skeletons = $skeletons;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @param $projectName
     * @return $this
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isInteraction()
    {
        return !empty($this->interaction);
    }

    /**
     * @param boolean $interaction
     * @return $this
     */
    public function setInteraction($interaction)
    {
        $this->interaction = !empty($interaction);

        return $this;
    }

    /*
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $roles
     * @return $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

}
