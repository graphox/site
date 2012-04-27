<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Alphaserv',

	// preloading 'log' component
	'preload'=>array('log', 'authManager'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.as.*',
		'application.components.*',
		'application.components.as.*',
		
		'ext.eauth.*',
		'ext.eauth.custom_services.*',
		'ext.eauth.services.*',
		'ext.eoauth.*',
		'ext.lightopenid.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		//*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'alphaserv123',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		//*/
		
		'as',
		'repo'
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			//'allowAutoLogin'=>true,
		),
		
		'authManager' => array(
			'class'=> 'as.components.authmanager'
		),
		
		'loid' => array(
            'class' => 'ext.lightopenid.loid',
        ),

        'eauth' => array(
            'class' => 'ext.eauth.EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'services' => array( // You can change the providers and their classes.
                //*
                'google' => array(
                    'class' => 'GoogleOpenIDService',
                ),
                /*
                'yandex' => array(
                    'class' => 'YandexOpenIDService',
                ),
                'twitter' => array(
                    // register your app here: https://dev.twitter.com/apps/new
                    'class' => 'TwitterOAuthService',
                    'key' => '...',
                    'secret' => '...',
                ),
                //*/
                'google_oauth' => array(
                    // register your app here: https://code.google.com/apis/console/
                    'class' => 'GoogleOAuthService',
                    'client_id' => '...',
                    'client_secret' => '...',
                    'title' => 'Google (OAuth)',
                ),
                'facebook' => array(
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'FacebookOAuthService',
                    'client_id' => '...',
                    'client_secret' => '...',
                ),
                'linkedin' => array(
                    // register your app here: https://www.linkedin.com/secure/developer
                    'class' => 'LinkedinOAuthService',
                    'key' => '...',
                    'secret' => '...',
                ),
                'github' => array(
                    // register your app here: https://github.com/settings/applications
                    'class' => 'GitHubOAuthService',
                    'client_id' => '...',
                    'client_secret' => '...',
                ),
                /*
                'vkontakte' => array(
                    // register your app here: http://vkontakte.ru/editapp?act=create&site=1
                    'class' => 'VKontakteOAuthService',
                    'client_id' => '...',
                    'client_secret' => '...',
                ),
                'mailru' => array(
                    // register your app here: http://api.mail.ru/sites/my/add
                    'class' => 'MailruOAuthService',
                    'client_id' => '...',
                    'client_secret' => '...',
                ),
                'moikrug' => array(
                    // register your app here: https://oauth.yandex.ru/client/my
                    'class' => 'MoikrugOAuthService',
                    'client_id' => '...',
                    'client_secret' => '...',
                ),
                'odnoklassniki' => array(
                    // register your app here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
                    'class' => 'OdnoklassnikiOAuthService',
                    'client_id' => '...',
                    'client_public' => '...',
                    'client_secret' => '...',
                    'title' => 'Odnokl.',
                ),
                //*/
            ),
        ),        
		
		// uncomment the following to enable URLs in path-format
		//*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				#'repo/(.*)' => 'repo/default/',

				'page/<path:(.*)>' => 'as/page',
				'page' => 'as/page',
				
				//custom paths for databse-stored pages
				#array(
				#	'class' => 'application.components.DbPage',
				#	'connectionID' => 'db',
				#),
				
				#'<url:.*>' => 'pages/page/<url>'
			),
		),
		//*/
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),//*/
		// uncomment the following to use a MySQL database
		//*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=alphaserv2',
			'emulatePrepare' => true,
			'username' => 'alphaserv',
			'password' => 'alphaserv',
			'charset' => 'utf8',
		),
		//*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
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
			),
		),
		
#		'cache'=>array(
#			'class'=>'system.caching.CDummyCache',
#			'servers'=>array(
#				 'localhost'
#			),
#		)
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);
