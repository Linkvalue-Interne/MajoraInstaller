MajoraInstaller
-----------------
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/05ea6a83-c3b1-4851-a6d1-7d3d94a39bdf/mini.png)](https://insight.sensiolabs.com/projects/05ea6a83-c3b1-4851-a6d1-7d3d94a39bdf) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LinkValue/MajoraInstaller/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/LinkValue/MajoraInstaller/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/LinkValue/MajoraInstaller/badges/build.png?b=master)](https://scrutinizer-ci.com/g/LinkValue/MajoraInstaller/build-status/master) [![Code Coverage](https://scrutinizer-ci.com/g/LinkValue/MajoraInstaller/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/LinkValue/MajoraInstaller/?branch=master) [![Total Downloads](https://poser.pugx.org/majora/installer/downloads)](https://packagist.org/packages/majora/installer) [![Latest Stable Version](https://poser.pugx.org/majora/installer/v/stable)](https://packagist.org/packages/majora/installer) [![License](https://poser.pugx.org/majora/installer/license)](https://packagist.org/packages/majora/installer)

This simple application will let you initialize an empty [Symfony2](https://symfony.com/) project along with an operational [Vagrant](https://www.vagrantup.com/) virtual machine, proper AnsibleGalaxy [roles](https://galaxy.ansible.com/) and Majora skeletons.
With only one command, you should be able to start a new project from scratch and be ready to code !

#### Requirements

##### Mac OS/X

* VirtualBox: https://www.virtualbox.org/wiki/Downloads
* VirtualBox Extension Pack: https://www.virtualbox.org/wiki/Downloads
* Vagrant: http://www.vagrantup.com/downloads
* Ansible: https://valdhaus.co/writings/ansible-mac-osx

##### Ubuntu

* Check in computer BIOS that Virtualization/VT-d/VT-x are `Enabled/On`
* VirtualBox: https://www.virtualbox.org/wiki/Downloads
* VirtualBox Extension Pack: https://www.virtualbox.org/wiki/Downloads
* Vagrant: http://www.vagrantup.com/downloads
* Ansible: http://docs.ansible.com/ansible/intro_installation.html#latest-releases-via-apt-ubuntu
* NFS: `sudo apt-get install nfs-common nfs-kernel-server`

#### How-to? ####
* Clone the project into the directory of your choice <br/> <sub>(we will choose `/var/www/ReadyToCode` for this documentation)</sub>
* Go to the directory you've cloned the project into:<br/>`$ cd /var/www/ReadyToCode`.
* Build the `PHAR` file with PHP:<br/>`$ php make-phar.php`
* Go to the `./build` directory:<br/>`$ cd ./build`
* Execute the `PHAR` file:<br/>`$ php ready-to-code.phar`

#### What all these questions are about? ####
You may wonder why you are prompted with additional information. Don't worry, this short step will serve only one main purpose: to know what you are doing.

Therefore, you will be asked for, respectively:

1. The global project name (will be used in your `/etc/hosts` for exemple);
2. The directory where the project will be initialized (make sure PHP has the proper permissions on it);
3. The IP address of the Vagrant virtual machine (will be useful for your `/etc/hosts`);
4. The number of skeletons you plan on using in your project;
4.  The names and URLs of the skeletons you wish to use in the following format:<br/>`name=>url`. You will be prompted for these information for each of the skeletons mentioned in the earlier step;
5. The number of AnsibleGalaxy roles you plan on using in your project:
6. The name of each AnsibleGalaxy roles as mentioned in the earlier step.

#### What now? ####
If everything went well, you should see the following output:

    ... checking if root dir exists prior creation
    ... root dir just created
    ... downloading Majora
    ... Majora successfully downloaded
    ... Majora successfully copied
    ... Customize VagrantFile
    ... Download skeleton 1/1: <urlToSkeleton>
    ... Copy skeleton to project 
    ... successfully added 3 roles to Ansible Galaxy configuration file
    PROJECT SUCCESSFULLY INITIALIZED \o/
