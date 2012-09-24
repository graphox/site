<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Sauers',
	//'theme' => 'main',
	'defaultController' => 'blog',

	// preloading 'log' component
	'preload'=>array(
		'log',
		'bootstrap',
	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.forms.*',
		'application.components.*',
        'application.extensions.EActiveResource.*',
        'application.extensions.Neo4Yii.*',

	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		//*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'test123',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),

		    'generatorPaths'=>array(
		        'bootstrap.gii', // since 0.9.1
		    ),
		),
		//*/
	),

	// application components
	'components'=>array(
		'bootstrap'=>array(
		    'class'=>'ext.bootstrap.components.Bootstrap', // assuming you extracted bootstrap under extensions
		),

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl' => array('/user/login'),
		),
		// uncomment the following to enable URLs in path-format
		//*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'user/profile/<name:\w+>/<subaction:\w+>' => 'user/profile',
				'user/profile/<name:\w+>' => 'user/profile',
				
				//blogs and blogposts
				'blog/<action:(create|index|)>' => 'blog/<action>',
				'blog/<name:\w+>' => 'blog/viewBlog',
				'blog/<name:\w+>/<action:(settings)>' => 'blog/<action>',
				'blog/<name:\w+>/<action:(view|update|delete)>/<id:\d+>-<title:(\w|[-])+>' => 'blog/<action>Post',
				'blog/<name:\w+>/<action:(create)>' => 'blog/<action>Post',
				
				'admin/user/<name:\w+>/<action:(view|update|delete)>' => 'admin/user/<action>',
				
				'<controller:\w+>/<id:\d+>/<title:(\w| )+>' => '<controller>/view',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
			
		),
		//*/
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		// */
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=sauers',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '***',
			'charset' => 'utf8',
			'tablePrefix' => '',
			
			//*
			'enableProfiling'=>true,
			'enableParamLogging'=>true,
			// * /
		),
		// */
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        
        'contentMarkup' => array(
        	'class' => 'application.components.ContentMarkup',
        ),
        
		'crypto' => array(
        	'class' => 'application.components.AsCrypto',
        ),
		
		'user'=>array(
			'class' => 'application.components.WebUser',
        ),

		
        'mailer' => array(
        	'class' => 'application.components.AsMailer',
        	
        	'type' => 'smtp',
        	'host' => 'localhost',
        	'port' => 25,
        	
        	'username' => '',
        	'password' => '',
        	
        	'defaultAttributes' => array(
        		'from' => 'noreply@localhost.local',
        	),
        	
        	'sendmailPath' => '',
        ),
        
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				//*/
				
				//*
				array(
					'class'=>'ext.yii-debug-toolbar.yii-debug-toolbar.YiiDebugToolbarRoute',
					// Access is restricted by default to the localhost
					//'ipFilters'=>array('127.0.0.1','192.168.1.*', 88.23.23.0/24),
				),
				//*/
			),
		),
		
		'neo4j'=>array(
			'class'=>'ENeo4jGraphService',
			'host'=>'localhost',
			'port'=>'7474',
			'db'=>'db/data',
			'queryCacheID'=>'cache',
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'adminEmail'=>'webmaster@example.com',
	),
);