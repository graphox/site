<?php
class AsEAuthWidget extends EAuthWidget
{
	public $view = 'as.views.widgets.authwidget';		

    public function run()
    {
		#print_r(parent::parent);
		
		$this->registerAssets();
		$this->render($this->view, array(
			'id' => $this->getId(),
			'services' => $this->services,
			'action' => $this->action,
		));
    }
}
