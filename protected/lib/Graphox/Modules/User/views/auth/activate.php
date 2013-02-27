<?php
$this->breadcrumbs=array(
	'Auth'=>array('/user/auth'),
	'Activate',
);

\Yii::import('bootstrap.widgets.TbForm');
 
$form = \TbForm::createForm(
	$form->getFormConfig(),
	$form
);

echo $form->render();

