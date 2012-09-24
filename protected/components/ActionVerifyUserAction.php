<?php
/**
 * Verifies user actions with verificaition page
 *
 * @author killme
 */
class ActionVerifyUserAction extends CAction
{
	public $allowAjax = true;
	
	public $onRun;
	
	public $shortDescr;
	public $longDescr;
	
	public $returnUrl;
	
	
	public function __construct($controller, $id)
	{
		$this->onRun = function(&$it) {};
		
		parent::__construct($controller, $id);
	}
	
	
	public function run()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$this->runAction();
			$this->controller->redirect($this->returnUrl);
		}
		
		$this->controller->render('/verify/action', array('it' => $this));
	}
	
	public function runAction()
	{
		$a = $this->onRun;$a($this);
	}
}

?>
