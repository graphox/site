<?php

$cfg = require (__DIR__.'/cfg.php');
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

//Yii::setPathOfAlias('Graphox', dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Graphox');

//Yii::setPathOfAlias('Everyman', dirname(__DIR__).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'Neo4Php'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Everyman');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'		=> isset($cfg['site.name']) ? $cfg['site.name'] : 'Unnamed site',
	'theme'		=> isset($cfg['site.theme']) ? $cfg['site.theme'] : 'sauers',
	//'theme' => 'main',
	'defaultController' => 'site',

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
		'application.components.Neo4j.*',
        //'application.extensions.EActiveResource.*',
        //'application.extensions.Neo4Yii.*',
		
		'ext.eoauth.*',
        'ext.eoauth.lib.*',
        'ext.lightopenid.*',
        'ext.eauth.*',
        'ext.eauth.services.*'
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
		
		'forum'=>array(
			'class'=>'application.modules.yii-forum.YiiForumModule',
		),
		
		'blog',
		
		'page',
		'admin' => array(
			'class' => 'Graphox\Modules\Admin\AdminModule',
		),
		
		'user' => array(
			'class' => 'Graphox\Modules\User\UserModule',
			'popup' => true, // Use the popup window instead of redirecting.
            'services' => array( // You can change the providers and their classes.

            ),
		),
		'analytics',
		'site'
		
		
	    //'api'=>array(
		//    'allowMasterConnection' => true,
		//	'salt' => 'abc',
		//),
		//*/
	),

	// application components
	'components'=>array(
		'request'=>array(
            'enableCsrfValidation'=>true,
			'enableCookieValidation'=>true,
        ),
		
		'bootstrap'=>array(
		    'class'=>'ext.bootstrap.components.Bootstrap', // assuming you extracted bootstrap under extensions
		),

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl' => array('/user/login'),
		),
		
		'less' => array(
			'class' => 'ext.less.ELessCompiler'
		),
		// uncomment the following to enable URLs in path-format
		//*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				//user profiles
				'user/profile/<name:\w+>/<subaction:\w+>' => 'user/profile',
				'user/profile/<name:\w+>' => 'user/profile',
				
				//blogs and blogposts
				/*'blog/<action:(create|index|)>' => 'blog/default/<action>',
				'blog/<name:\w+>' => 'blog/default/viewBlog',
				'blog/<name:\w+>/<action:(settings)>' => 'blog/default/<action>',
				'blog/<name:\w+>/<action:(view|update|delete)>/<id:\d+>-<title:(\w|[-])+>' => 'blog/default/<action>Post',
				'blog/<name:\w+>/<action:(create)>' => 'blog/default/<action>Post',
				*/
				'admin/user/<name:\w+>/<action:(view|update|delete)>' => 'admin/user/<action>',
				'admin/page/<action:(create)>' => 'admin/page/<action>',
				'admin/page/<name:\w+>/<action:(view|update|delete)>' => 'page/<action>',
				
				//pages
				'page/<name:\w+>' => 'page/view',
				
				//comments
				'comment/create/<parentId:\d+>' => 'comment/create',
				
				//uploads
				'file/raw/<name:([a-zA-Z0-9.-]+)>' => 'file/raw',
				'file/<download:download>/<name:([a-zA-Z0-9.-]+)>' => 'file/raw',
				'file/<thumb:thumb>/<name:([a-zA-Z0-9.-]+)>' => 'file/raw',
				
				'<controller:\w+>/<id:\d+>/<title:(\w| )+>' => '<controller>/view',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				
				//slow
				//array(
				//	'class' => 'application\components\CustomRoutes'
				//),
			),
			
		),
		
		'phpThumb'=>array(
				'class'=>'ext.EPhpThumb.EPhpThumb',
		),
		
		'cache' => array(
			'class' => 'CApcCache'
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
            #'errorAction'=>'site/error',
        ),
        
        'contentMarkup' => array(
        	'class' => 'application.components.ContentMarkup',
        ),
        
		'crypto' => array(
        	'class' => 'Graphox\Crypto',
        ),
		
		'user'=>array(
			'class' => 'application.components.WebUser',
        ),
		
		'timeline'=>array(
			'class' => 'Spy\Timeline\ServiceLocator'
		),
		
        'mailer' => array(
        	'class' => '\Graphox\Mail\Mailer',
        	
			'transport' => array(
				'type' => 'smtp',
				'host' => 'localhost',
				'port' => 25,

				'username' => '',
				'password' => '',
        	),
			
        	/*'defaultAttributes' => array(
        		'from' => 'noreply@localhost.local',
        	),
        	
        	'sendmailPath' => '',*/
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
			'class'=>'\\Graphox\\Neo4j\\EntityManager',
			'host'=>'localhost',
			'port'=>'7474',
			'proxyDir' => 'application.runtime.proxy',
		),
		
		'class' => array(
			'class' => 'ext.dashboard.AsDashboard',
			'widgets' => array(
				
			)
		),
		
		'site' => array(
			'class' => 'site.models.Site',
		),
		
		        'loid' => array(
            'class' => 'ext.lightopenid.loid',
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'adminEmail'=>'webmaster@example.com',
		
		'fileUpload' => array(
			'allowedExtensions' => array("jpg", "png", "zip", "gz", "7z", "ogg", "wpt", "ogz", "bak", "cfg", "txt", "gif", "mov", "avi", 'dmo'),
			'sizeLimit'	=> 10 * 1024 * 1024 * 1024, // 10 GB
		)
	),
);
