<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public function render($view, $vars = array(), $return = false)
	{
		if(isset($_POST['nolayout']))
			return $this->renderPartial($view, $vars, $return);
		else
			return parent::render($view, $vars, $return);
	}
	
	public function beforeAction($action)
	{
		$normalizedUrl = CHtml::normalizeUrl(
			array_merge(
				array("/".$this->route),
				$_GET
			)
		);
		
        if (Yii::app()->request->url != $normalizedUrl && strpos($normalizedUrl, Yii::app()->errorHandler->errorAction) === false)
		{
            //$this->redirect($normalizedUrl, true, 301);
        }
 
        return parent::beforeAction($action);
    }
}
