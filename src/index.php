<?php
require_once 'phar://ready-to-code.phar/common.php';
$config = parse_ini_file('config.ini');
AppManager::run($config);