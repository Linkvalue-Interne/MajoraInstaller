<?php
class Prompt
{
    const PROMPT_PROJECT_NAME = 'What is the global project name ?';
    const PROMPT_ROOT_DIR = 'What would be the root dir of the installation ?';
    const PROMPT_IP_VAGRANT = 'What would be the IP address of the VM ?';
    const PROMPT_HOW_MANY_SKELETONS = 'How many skeletons will be in use ?';
    const PROMPT_SKELETONS = '- Input skeleton\'s name n°%s';

    const ERROR_PROMPT = '/!\ You made an error in providing the required information.';

    private $projectName;
    private $rootDir = '.';
    private $ipVagrant;
    private $skeletons = [];

    /**
     * @var bool
     *
     * Define if the prompt will wait for user inputs or not.
     */
    private $interaction;

    /**
     * Constructor
     */
    public function __construct() {
        $this->interaction = true;
    }

    /**
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
     * @return array|bool
     */
    private function prompt()
    {
        if(!$this->interaction) {
            return [
                'projectName' => 'readyToCode',
                'rootDir' => './test',
                'ipVagrant' => '127.0.0.1',
                'skeletons' => ['test'],
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

        if(!$nbSkeletons = trim(readline(self::PROMPT_HOW_MANY_SKELETONS."\n"))) {
            return false;
        }

        if(filter_var($nbSkeletons, FILTER_VALIDATE_INT) === false) {
            return false;
        }

        $i = 0;
        $skeletons = [];
        while(true) {
            ++$i;
            $skeletons[] = trim(readline(sprintf(self::PROMPT_SKELETONS, $i)."\n"));
            if($i == $nbSkeletons) {
                break;
            }
        }

        return [
            'projectName' => $name,
            'rootDir' => $dir,
            'ipVagrant' => $ip,
            'skeletons' => $skeletons,
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
}