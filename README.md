# MajoraInstaller

A tool to create Symfony projects using Majora skeletons

## Install the installer

This step is only needed for the first time you use the installer.

### Download the binary

You will download a binary ready to use in your system.

#### Linux / Mac OS X

*todo: create a built binary downloadable*

#### Windows

*todo: create a built binary downloadable*

### Building from source

You will build the binary from the source code to use in your system after cloning the repository.

#### 1. Install the dependencies

The project use Composer as package manager.

Execute the following command to install the packages (dev required):

```
$ composer install -o
```

#### 2. Build the binary

The project use Box as PHAR builder.

Execute the following command ton build the application:

```
$ vendor/bin/box build
```

#### 3. Install in your system

To have the `majora` command, execute the following command:

```
# cp build/majora /usr/local/bin/majora
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
