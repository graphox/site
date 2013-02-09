<?php

$config = CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'preload' => array('log'),
		'components'=>array(
			/*'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			/* uncomment the following to provide test database connection
			'db'=>array(
				'connectionString'=>'DSN for test database',
			),
			*/
		),
	)
);

$config['preload'] = array('log');

return $config;