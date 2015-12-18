<?php
// dir définitions
$srcRoot = __DIR__ . '/src';
$buildRoot = __DIR__ . '/build';

// unlink previous phar file
if(file_exists($buildRoot . '/ready-to-code.phar')) {
    unlink($buildRoot . '/ready-to-code.phar');
}

// building phar
$params = FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME;

$phar = new Phar($buildRoot . '/ready-to-code.phar', $params, 'ready-to-code.phar');

$phar['index.php'] = file_get_contents($srcRoot . '/index.php');
$phar['common.php'] = file_get_contents($srcRoot . '/common.php');
$phar->setStub($phar->createDefaultStub('index.php'));

copy($srcRoot . '/config.ini', $buildRoot . '/config.ini');