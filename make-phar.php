<?php
// dir definitions
$srcRoot = __DIR__ . '/src';
$buildRoot = __DIR__ . '/build';

// unlink previous phar file
if(file_exists($buildRoot . '/ready-to-code.phar')) {
    unlink($buildRoot . '/ready-to-code.phar');
}

// building phar
$params = FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME;

$phar = new Phar($buildRoot . '/ready-to-code.phar', $params, 'ready-to-code.phar');

$phar->buildFromDirectory($srcRoot);
$phar->setStub($phar->createDefaultStub('index.php'));
