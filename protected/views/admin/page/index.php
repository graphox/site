<?php
$this->breadcrumbs=array(
	'admin' => array('/admin/index'),
	'page'
);

?>
<h1>Pages</h1>
<?=CHtml::link('Create page', array('/admin/page/create'))?>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$dataProvider,
    'template'=>"{items}",
    'columns'=>array(
        array('name'=>'id', 'header'=>'#'),
        array('name'=>'name', 'header'=>'name'),
        array('name'=>'contentSource', 'header'=>'source'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
			'viewButtonUrl' => 'Yii::app()->controller->createUrl("/admin/page/view", array("name"=>$data->routeName))',
			'updateButtonUrl'=>'Yii::app()->controller->createUrl("/admin/page/update",array("name"=>$data->routeName))',
			'deleteButtonUrl'=>'Yii::app()->controller->createUrl("/admin/page/delete",array("name"=>$data->routeName))',
        ),
    ),
)); ?>
