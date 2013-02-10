<?php

namespace Graphox\Modules\Server\Controllers;

class DefaultController extends \Controller
{
	public function actionIndex()
	{
		$a = $this->getModule()->getFetcher();
		
		$this->render('index', array(
			'servers' => array(
				array('109.73.51.58', 10041, 'Nooblounge 4')
			)
		));
	}
	
	public function actionUpdate($ip, $port = \AsSauerServer::defaultPort)
	{
		echo \CJSON::encode(
			$this->getModule()->getFetcher()->query($ip, $port)
		);
	}
}
