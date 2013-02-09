<?php

class Dashboard extends CModel
{
	public $name;
	
	public function attributeNames()
	{
		return array(
			'name'
		);
	}
	
	public function getRows()
	{
				$con = Neo4jNode::model()->connection;
		$q = new Everyman\Neo4j\Gremlin\Query($con, 'g.E');

		$nodes = array();
		$edges = array();

		$results = $q->getResultSet();
		foreach($results as $row)
		{
			try
			{

				if(strpos($row[0]->getStartNode()->modelclass, 'Comment') !== false)
						continue;
				if(strpos($row[0]->getEndNode()->modelclass, 'Comment') !== false)
						continue;
				if($row[0]->getType() == '_CREATOR_')
					continue;

				$nodes[$row[0]->getStartNode()->getId()]	= array($row[0]->getStartNode()->getId(), array('modelclass' => $row[0]->getStartNode()->modelclass));

					$nodes[$row[0]->getEndNode()->getId()]		= array($row[0]->getEndNode()->getId(), array('modelclass' => $row[0]->getEndNode()->modelclass));
				$edges[] = array(
					$row[0]->getStartNode()->getId(),
					$row[0]->getEndNode()->getId(),
					array('type' => $row[0]->getType()));

				//echo $row[0]->getStartNode()->getId(), '<=>', $row[0]->getEndNode()->getId() , '<br/>', PHP_EOL;
			}
			catch(Exception $e)
			{
				throw $e;
			}

		}

		return array(
			
			//row
			array(
				'columns' => array(
					array(
						'className' => 'ext.arbor.widgets.GraphWidget',
						'attributes' => array(
							'nodes' => (array)$nodes,
							'edges' => (array)$edges,
							'width' => 300,
							'height'=> 400,
						),
						'title' => 'Graph'
					),
				),
			)
		);
	}
}

