# MajoraInstaller

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bb31d357-0d5b-4704-a4e0-928b95987583/mini.png)](https://insight.sensiolabs.com/projects/bb31d357-0d5b-4704-a4e0-928b95987583) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LinkValue/MajoraInstaller/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/LinkValue/MajoraInstaller/?branch=master) [![Build Status](https://travis-ci.org/LinkValue/MajoraInstaller.svg?branch=master)](https://travis-ci.org/LinkValue/MajoraInstaller) [![Code Coverage](https://scrutinizer-ci.com/g/LinkValue/MajoraInstaller/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/LinkValue/MajoraInstaller/?branch=master) [![Total Downloads](https://poser.pugx.org/majora/installer/downloads)](https://packagist.org/packages/majora/installer) [![Latest Stable Version](https://poser.pugx.org/majora/installer/v/stable)](https://packagist.org/packages/majora/installer) [![License](https://poser.pugx.org/majora/installer/license)](https://packagist.org/packages/majora/installer)

A tool to create Symfony projects using Majora skeletons

## Install the installer

This step is only needed for the first time you use the installer.

### Download the binary

You will download a binary ready to use in your system.

#### Linux / Mac OS X

```bash
$ sudo curl -LsS https://github.com/LinkValue/MajoraInstaller/releases/download/2.0.0/majora.phar -o /usr/local/bin/majora
$ sudo chmod a+x /usr/local/bin/majora
```

#### Windows

```bash
c:\> php -r "file_put_contents('majora', file_get_contents('https://github.com/LinkValue/MajoraInstaller/releases/download/2.0.0/majora.phar'));"
```

Move the downloaded `majora` file to your projects directory and execute
it as follows:

```bash
c:\> php majora
```

If you prefer to create a global `majora` command, execute the following:

```bash
c:\> (echo @ECHO OFF & echo php "%~majora" %*) > majora.bat
```

Then, move both files (`majora` and `majora.bat`) to any location included
in your execution path. Now you can run the `majora` command anywhere on your
system.

### Building from source

You will build the binary from the source code to use in your system after cloning the repository.

#### 1. Install the dependencies

The project use [Composer](https://getcomposer.org) as package manager.

Execute the following command to download Composer binary: 

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

Then, execute the following command to install the packages (dev required):

```
$ php composer.phar install -o
```

#### 2. Build the binary

The project use [Box](https://github.com/box-project/box2) as PHAR builder.

You must enable PHAR in your `php.ini` first:

```
phar.readonly = Off
```

Execute the following command to download the Box binary

```
$ curl -LSs https://box-project.github.io/box2/installer.php | php
```

Then, execute the following command ton build the application:

```
$ php box.phar build
```

#### 3. Install in your system

To have the `majora` command, execute the following command:

```
# cp build/majora.phar /usr/local/bin/majora
```

## Using the installer

### 1. Start a new project with the latest stable Majora Standard Edition version

Execute the `new` command and provide the name of your project as the only
argument:

```bash
# Linux, Mac OS X
$ majora new my_project

# Windows
c:\> php majora new my_project
```

### 2. Start a new project based on a specific Majora Standard Edition branch

Execute the `new` command and provide the name of your project as the first
argument and the branch number as the second argument. The installer will
automatically select the most recent version available for the given branch:

```bash
# Linux, Mac OS X
$ majora new my_project 2.8

# Windows
c:\> php majora new my_project 2.8
```

### 3. Start a new project based on a specific Majora Standard Edition version

Execute the `new` command and provide the name of your project as the first
argument and the exact Majora Standard Edition version as the second argument:

```bash
# Linux, Mac OS X
$ majora new my_project 2.8.1

# Windows
c:\> php majora new my_project 2.8.1
```

## References

- [symfony/symfony-installer](https://github.com/symfony/symfony-installer)
