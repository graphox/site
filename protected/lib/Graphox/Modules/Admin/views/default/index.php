<?php
$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1>Dashboard</h1>

<?php
	$dashboard	= new Dashboard;
?>

<?php $this->widget('ext.dashboard.widgets.AsDashboardWidget', array(
	'dashboard' => $dashboard
)); ?>

<?php
/*
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

$this->widget('ext.arbor.widgets.GraphWidget', array(
	'nodes' => (array)$nodes,
	'edges' => (array)$edges
));

		//var_dump(Yii::app()->neo4j->getServerInfo());
		//var_dump(Yii::app()->neo4j->getRelationshipTypes());

		
*/