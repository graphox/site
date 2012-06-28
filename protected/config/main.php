<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return CMap::mergeArray(
	array(
		'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
		'name'=>'Alphaserv',
		'theme'=>'shadow_dancer',
		'defaultController' => 'as/page',

		// preloading 'log' component
		'preload'=>array('log'),

		// autoloading model and component classes
		'import'=>array(
			'application.models.*',
			'application.modules.as.models.*',
			'application.components.*',
			'application.modules.as.components.*',
		
			'ext.eauth.*',
			'ext.eauth.custom_services.*',
			'ext.eauth.services.*',
			'ext.eoauth.*',
			'ext.lightopenid.*',
			#'ext.statemachine.*',
		),

		'modules'=>array(

			// uncomment the following to enable the Gii tool
			//*
			'gii'=>array(
				'class'=>'system.gii.GiiModule',
				'password'=>'alphaserv123',
			 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
				'ipFilters'=>array('127.0.0.1','::1'),
				'generatorPaths' => array(
					'application.gii'
				),
			),
			//*/
		
			'as',
			#'repo'
		),

		'behaviors' => array(
			'ext.qtzpanel.QtzPanelBehavior',
		),

		// application components
		'components'=>array(
			'widgetFactory'=>array(
				'class'=>'CWidgetFactory',

				'widgets'=>array(
					'CGridView'=>array(
						'htmlOptions'=>array('cellspacing'=>'0','cellpadding'=>'0'),
						'itemsCssClass'=>'item-class',
						'pagerCssClass'=>'pager-class'
					),
					
					'CJuiTabs'=>array(
						'cssFile' => false,
						'htmlOptions'=>array('class'=>'shadowtabs'),
					),
						'CJuiAccordion'=>array(
							'htmlOptions'=>array('class'=>'shadowaccordion'),
						),
						'CJuiProgressBar'=>array(
						   'htmlOptions'=>array('class'=>'shadowprogressbar'),
						),
						'CJuiSlider'=>array(
							'htmlOptions'=>array('class'=>'shadowslider'),
						),
						'CJuiSliderInput'=>array(
							'htmlOptions'=>array('class'=>'shadowslider'),
						),
						'CJuiButton'=>array(
							'htmlOptions'=>array('class'=>'shadowbutton'),
						),
						'CJuiButton'=>array(
							'htmlOptions'=>array('class'=>'shadowbutton'),
						),
						'CJuiButton'=>array(
							'htmlOptions'=>array('class'=>'button green'),
						),
					),
				),		
		
			'swiftMailer' => array(
				'class' => 'ext.swiftMailer.SwiftMailer',
			),
		
			'user'=>array(
				'class'=> 'as.components.webUser'
				// enable cookie-based authentication
				//'allowAutoLogin'=>true,
			),
		
			#'statemachine' => array(),
		
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
					'<controller:\w+>/id/<id:\d+>'=>'<controller>/index/',
					'<controller:\w+>/<id:\d+>'=>'<controller>/index/',
					'<controller:\w+>/name/<name:\w+>'=>'<controller>/index/',

					'<module:as>/<controller:(page)>/<action:(edit|comment)>/<id:\d+>'=>'<module>/<controller>/<action>',
					'<module:as>/<controller:(page)>/<name:[^\/]+>/<id:\d+>'=>'<module>/<controller>',

					'as/profile/<name:(\w|[.])+>' => 'as/profile',

					'<controller:\w+>/<id:\d+>'=>'<controller>/view',
					'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
					'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',				
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
			
				'enableProfiling'=>true,
				'enableParamLogging'=>true,
			),
			//*/
			'errorHandler'=>array(
				// use 'site/error' action to display errors
				//'errorAction'=>'as/error/error', //TODO: make this controller
			),
			'log'=>array(
				'class'=>'CLogRouter',
				'routes'=>array(
					array(
						'class'=>'CFileLogRoute',
						'levels'=>'error, warning',
					),
				
					array(
						'class'=>'CWebLogRoute',
						'categories'=>'system.db.CDbCommand',
						'showInFireBug'=>true,
					),
				
					#'ext.qtzpanel.QtzPanelRoute',
				
					// uncomment the following to show log messages on web pages
					/*
					array(
						'class'=>'CWebLogRoute',
					),
					//*/
				),
			),
		
			'cache'=>array(
				'class'=>'system.caching.CFileCache',
	#			'servers'=>array(
	#				 'localhost'
	#			),
			)
		),
	
		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params'=>array(
			// this is used in contact page
			'adminEmail'=>'webmaster@example.com',
		
			'email.settings' => array(
				'host' => 'localhost',
				'port' => 25,
			
				'from' => array( 'doNotReply', 'doNotReply@localhost' ),
			
			),
		
			'purifier.settings' => array(
				'AutoFormat.AutoParagraph' => true,
				'AutoFormat.RemoveEmpty.RemoveNbsp' => true,
				'AutoFormat.RemoveEmpty' => true,
				'AutoFormat.Linkify' => true,
				'Core.EscapeInvalidTags' => true,
				'Core.NormalizeNewlines' => true,
				'HTML.Nofollow' => true,
				'HTML.TargetBlank' => true,
				#'URI.Base' => ,
				#
				'URI.MakeAbsolute' => true,
				'URI.AllowedSchemes' => array(
					'http' => true,
					'https' => true,
					'mailto' => true,
					'ftp' => true,
					'nntp' => true,
					'news' => true,
				)	
			)
		),
	),
	file_exists(FCPATH.'config.php')
		? require(FCPATH.'config.php')
		: array());
