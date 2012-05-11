<?php
/**
 * Created by Roman Revin <xgismox@gmail.com>.
 * Date: 02.02.12 13:42
 */

class QtzPanelBehavior extends CBehavior
{
	private $panels = array();

	public function getPanel($id=false, $config=array())
	{
		if(!Yii::getPathOfAlias('QtzPanelSource')){
			// set alias
			Yii::setPathOfAlias('QtzPanelSource', dirname(__FILE__));
			// import default panels
			Yii::import('QtzPanelSource.*');
		}
		$id = empty($id) ? 'qtzPanelMain' : $id;
		if (!isset($this->panels[$id])) {
			$config['class'] = 'QtzPanelSource.QtzPanel';
			$config['id'] = $id;
			$this->panels[$id] = Yii::createComponent($config);
		}
		return $this->panels[$id];
	}
}