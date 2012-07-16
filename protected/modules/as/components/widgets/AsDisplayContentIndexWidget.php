<?php
class AsDisplayContentIndexWidget extends AsWidget
{
	public $data;

    public function run()
    {
		if(!isset($this->data))
			throw new CException('data attribute missing!');
		
		if(count($this->data->data) < 1)
			print 'nothing to display ...';
		else		
			$this->data->data[0]->renderIndex($this->data);
    }
}
