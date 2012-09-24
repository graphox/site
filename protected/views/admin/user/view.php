<?php
$this->breadcrumbs=array(
	'Admin'=>array('/admin/index'),
	'Users'=>array('index'),
	$model->username,
);

$this->menu=array(
	array('icon' => 'list', 'label'=>'List User', 'url'=>array('index')),
	array('icon' => 'pencil', 'label'=>'Update User', 'url'=>array('update', 'name'=>$model->username)),
	array('icon' => 'trash', 'label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','name'=>$model->username),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View User <?=CHtml::encode($model->displayName); ?></h1>
<?php new CFormatter; ?>
<?php $this->widget('bootstrap.widgets.BootDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'password',
		'isAdminActivated:boolean',
		'isEmailActivated:boolean',
		
		'registeredDate:dateTime',
		'registeredHost:url',
		
		'lastLoggedIn:dateTime',
		'lastLoggedInHost',
		
		'publicEmail:boolean',
		'publicName:boolean',
		
		'isBanned:boolean',
		'bannedReason',
		
		'isAdmin:boolean',
	),
)); ?>

<h2>Profile</h2>
<?php $this->widget('bootstrap.widgets.BootDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'country',
		'city',
		'email:email',
		'firstName',
		'lastName',
		'homepage:url',
		'canComment:boolean:Allow comments'
	),
)); ?>

<h2>Admin actions</h2>
<nav>
	   <?php $this->widget('bootstrap.widgets.BootButtonGroup', array(
			'type'=>'primary',
			'buttons'=>array(
				array('label'=>'Activate (admin).', 'url'=>array('/admin/user/activate', 'name' => $model->username)),
				array('items' => array(
					array('label'=>'Force email activation.', 'url'=>array('/admin/user/emailActivate', 'name' => $model->username), 'type' => 'secondary'),
					array('label'=>'Resend email (TODO)', 'url'=>'#'),	
				)),
				array('label'=>'Ban', 'url'=>array('/admin/user/ban', 'name' => $model->username)),
				array('label'=>'Update', 'url'=>array('/admin/user/update', 'name' => $model->username))
				
			),
		)); ?>
	
</nav>
