<?php

print 'Starting unit tests'.PHP_EOL;
// change the following paths if necessary
$bootstrap=dirname(__DIR__).'/bootstrap.php';
$config=dirname(__DIR__).'/config/test.php';

require_once($bootstrap);

Yii::createConsoleApplication($config);
