<?php
/**
 * Created by Roman Revin <xgismox@gmail.com>.
 * Date: 02.02.12 14:13
 */

/**
 * @property $height
 * @property $useCookie
 * @property $initiallyOpen
 */
class QtzPanelWidget extends CWidget
{
	public $skin = 'light';
	/**
	 * @var string business rule to check the visibility of the panel
	 */
	public $visibleRule = 'true';

	/**
	 * @var string height panel (in pixels)
	 */
	private $height = 100;

	/**
	 * @var bool use cookies to maintain state bar
	 */
	private $useCookie = true;

	/**
	 * @var bool indicates whether the panel is opened if the cookie is not set
	 */
	private $initiallyOpen = false;

	/**
	 * @var null result visible check
	 */
	private $_visibleResult = null;

	public function run()
	{
		/**
		 * @var $owner QtzPanel
		 */
		if($this->visible()){
			$owner = $this->getOwner();
			$owner->prepareBars();

			Yii::app()->clientScript->registerScriptFile(CHtml::asset(Yii::getPathOfAlias('QtzPanelSource').'/scripts.js'));

			$assets = Yii::app()->assetManager->publish(str_replace('.php','',$this->getViewFile($this->skin)));
			$this->render($this->skin, array(
				'assets' => $assets,
				'panel' => $owner,
				'items' => $owner->elements,
				'bar' => $owner->bar
			));
		}
	}

	public function visible($force = false)
	{
		if (is_null($this->_visibleResult) or CPropertyValue::ensureBoolean($force)) {
			eval('$this->_visibleResult = ' . $this->visibleRule . ';');
		}
		return $this->_visibleResult;
	}

	public function routeEnable(){
		$result = false;
		$routes = Yii::app()->log->routes;
		foreach($routes as $route){
			if($route instanceof QtzPanelRoute){
				$result = true;
			}
		}
		return $result;
	}

	public function getHeight()
	{
		return CPropertyValue::ensureInteger($this->height);
	}

	public function setHeight($value)
	{
		$this->height = CPropertyValue::ensureInteger($value);
	}

	public function getUseCookie()
	{
		return CPropertyValue::ensureBoolean($this->useCookie);
	}

	public function setUseCookie($value)
	{
		$this->useCookie = CPropertyValue::ensureBoolean($value);
	}

	public function getInitiallyOpen()
	{
		return CPropertyValue::ensureBoolean($this->initiallyOpen);
	}

	public function setInitiallyOpen($value)
	{
		$this->initiallyOpen = CPropertyValue::ensureBoolean($value);
	}
}