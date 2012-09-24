<?php
$this->breadcrumbs=array(
	'Users',
);

?>
<h1>Users</h1>

<h2>Waiting for activation</h2>

<?php $this->widget('bootstrap.widgets.BootGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$notActivatedProvider,
    'template'=>"{items}",
    'columns'=>array(
        array('name'=>'id', 'header'=>'#'),
        array('name'=>'username', 'header'=>'username'),
        array('name'=>'email', 'header'=>'email'),
        array('name'=>'registeredHost', 'header'=>'registeredHost'),
        array(
            'class'=>'bootstrap.widgets.BootButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
			'viewButtonUrl' => 'Yii::app()->controller->createUrl("/admin/user/view", array("name"=>$data->username))',
			'updateButtonUrl'=>'Yii::app()->controller->createUrl("/admin/user/update",array("name"=>$data->username))',
			'deleteButtonUrl'=>'Yii::app()->controller->createUrl("/admin/user/delete",array("name"=>$data->username))',
        ),
    ),
)); ?>

<h2>All users</h2>

<?php $this->widget('bootstrap.widgets.BootGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$dataProvider,
    'template'=>"{items}",
    'columns'=>array(
        array('name'=>'id', 'header'=>'#'),
        array('name'=>'username', 'header'=>'username'),
        array('name'=>'email', 'header'=>'email'),
        array('name'=>'registeredHost', 'header'=>'registeredHost'),
        array(
            'class'=>'bootstrap.widgets.BootButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
			'viewButtonUrl' => 'Yii::app()->controller->createUrl("/admin/user/view", array("name"=>$data->username))',
			'updateButtonUrl'=>'Yii::app()->controller->createUrl("/admin/user/update",array("name"=>$data->username))',
			'deleteButtonUrl'=>'Yii::app()->controller->createUrl("/admin/user/delete",array("name"=>$data->username))',
        ),
    ),
)); ?>