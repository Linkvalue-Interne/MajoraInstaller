<?php
class Prompt
{
    const PROMPT_ROOT_DIR = 'What would be the root dir of the installation ?';
    const PROMPT_IP_VAGRANT = 'What would be the IP address of the VM ?';
    const PROMPT_HOW_MANY_SKELETONS = 'How many skeletons will be in use ?';
    const PROMPT_SKELETONS = '- Input skeleton\'s name n°%s';

    const ERROR_PROMPT = '/!\ You made an error in providing the required information.';

    private $rootDir = '.';
    private $ipVagrant;
    private $skeletons = [];

    public function __construct() {}

    public function run()
    {

        umask(0222);

        if(!$prompt = $this->prompt()) {
            return false;
        }

        $infos = ['rootDir', 'ipVagrant', 'skeletons'];
        foreach($infos as $name) {
            $this->$name = $prompt[$name];
        }

        return $this;
    }

    private function prompt()
    {
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


}