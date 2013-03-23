<?php

print 'Starting unit tests'.PHP_EOL;
// change the following paths if necessary
$bootstrap=dirname(__DIR__).'/bootstrap.php';
$config=dirname(__DIR__).'/config/test.php';

//clear proxies
foreach (glob(dirname(__DIR__) . '/runtime/proxy/*') as $file)
        if (is_file($file)) unlink($file);

require_once($bootstrap);

Yii::createConsoleApplication($config);
