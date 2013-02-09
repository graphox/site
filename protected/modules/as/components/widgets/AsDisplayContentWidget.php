<?php
class AsDisplayContentWidget extends AsWidget
{
	public $data;
	public $can;

    public function run()
    {
		if(!isset($this->data))
			throw new CException('data attribute missing!');
		
		$this->data->render($this->can);
    }
}
