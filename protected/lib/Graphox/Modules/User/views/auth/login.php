<?php
$this->breadcrumbs=array(
	'Auth', 'Log in'
);?>

<div class="span4" >
<?php
	$this->widget('user.widgets.LoginWidget', array('form' => $form));
?>
</div>
<div class="span4">
	<div  class="kbox" >
		<fieldset>
			<legend><?=Yii::t('user.oauth', 'Oauth login')?></legend>
		<?php $this->widget('user.widgets.OAuthWidget'); ?>
		</fieldset>
	</div>
	<?php if(false): ?>
	<div class="kbox">
		<?php
			Yii::import('user.forms.OpenIDForm');
			$formBuilderModel = new OpenIDForm;
			$form = TbForm::createForm($formBuilderModel->getFormConfig(),$formBuilderModel);
			
			echo $form->render();
		?>
	</div>
	<?php endif; ?>
</div>