<?php $data = array();
	foreach ($requirements as $r)
		$data[] = array(
			
			'Name' => $r[0],
			'value' => $r[1] ? true : false,
			'Description' => isset($r[2])? $r[2] : '',
			'Installed' => $r[1] ? 'Yes' : 'No'
		)
?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'dataProvider'=>new \CArrayDataProvider($data, array('keyField' => 'Name')),
	'rowHtmlOptionsExpression' => 'array("class" => $data["value"] ? "greenrow" : "redrow")',
	'columns' => array(
		'Name', 'Description', 'Installed'
	)
));