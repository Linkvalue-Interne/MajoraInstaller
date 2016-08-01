<?php

namespace Majora\Installer\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * This command provides informations about MajoraInstaller.
 */
class AboutCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('about')
            ->setDescription('Majora Installer Dispatcher.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $commandTitle = '                     Majora Installer                    ';
        $commandHelper = <<<COMMAND_HELPER
    <info>This is installer for majora-standard-edition</info>
   
    Create project to <info>current directory</info>: 
    
        <comment>majora new <Project Name></comment>
        
    Create project <info>for path</info>: 
    
        <comment>majora new <Path></comment>
        
    Create project <info>based on a specific branch</info>: 
    
        <comment>majora new <Project Name> <BranchName> </comment>
        
        
        
COMMAND_HELPER;

        $io->title($commandTitle);
        $io->writeln($commandHelper);
    }
}
