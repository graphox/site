<?php

\Yii::import('bootstrap.widgets.TbForm');
 
$form = \TbForm::createForm($formBuilderModel->getFormConfig(),$formBuilderModel);

if(!$noBox)
	echo '<div class="kbox">';

echo $form->render();

if(!$noBox)
	echo '</div>';

