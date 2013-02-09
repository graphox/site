<?php

class GraphWidget extends CWidget
{
	public $nodes = array();
	public $edges = array();
	
	public $options = array(
		'gravity'	=> false,
		'friction'	=> 1.0,
		'stiffness' => 512,
		'repulsion'	=> 2600
	);
	
	public $width	=800;
	public $height	=600;
	
	public function run()
	{
		$assets = Yii::app()->getAssetManager()->publish(dirname(__DIR__).DIRECTORY_SEPARATOR.'assets');
		
		Yii::app()->getClientScript()->registerScriptFile($assets.'/arbor.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile($assets.'/graph.js', CClientScript::POS_HEAD);
		
		$script = '
var sys = arbor.ParticleSystem('.  json_encode($this->options).')
sys.parameters('.  json_encode($this->options).')
sys.renderer = Renderer("#'.$this->id.'", sys)
';
		
		foreach($this->nodes as $node)
		{
			$name = array_shift($node);
			$data = array_shift($node);
			
			$script .= 'sys.addNode('.json_encode($name).', '.json_encode($data).');'.PHP_EOL;
		}

		foreach($this->edges as $edge)
		{
			$from	= array_shift($edge);
			$to		= array_shift($edge);
			$data	= array_shift($edge);
			$script .= 'sys.addEdge('.json_encode($from).', '.json_encode($to).', '.json_encode($data).');'.PHP_EOL;
		}
	
		Yii::app()->getClientScript()->registerScript('GRAPH'.$this->id, $script, CClientScript::POS_READY);
		
		echo '<canvas id="',CHtml::encode($this->id), '" width="', $this->width, '" height="', $this->height, '"></canvas>';
	}
}

