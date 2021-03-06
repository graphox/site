<?php
 error_reporting(E_ALL);
 ini_set("display_errors", 1);
// change the following paths if necessary
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

define('YII_ENABLE_ERROR_HANDLER', true);
define('YII_ENABLE_EXCEPTION_HANDLER', true);

require_once ('protected/bootstrap.php');

Yii::createApplication('\Graphox\Yii\WebApplication', $config)->run();
