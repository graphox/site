<?php
	$this->title = 'join or start a clan';
	$this->layout = '//layouts/column2'; #force matching layout
 ?>

<?php $this->beginClip('first'); ?>
		<h2 class="line-divider">Create a new clan</h2>

		<p>
			<strong>Please fill out the following form with clan information:</strong>
		</p>
		
		<p>
			clantag and such comes later.
		</p>

		<p>
			Fields with <span class="required">*</span> are required.
		</p>

		<p>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'clan-form',
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
			'htmlOptions' => array('class' => 'admin-form'),
			'action' => array('//as/clan/new'),
			
		)); ?>

			<fieldset>
				<div>
					<?php echo $form->labelEx($model,'name'); ?>
					<?php echo $form->textField($model, 'name', array('class' => 'form-poshytip', 'title' => 'The name of your clan.')); ?>
					<?php echo $form->error($model,'name'); ?>
				</div>

				<div>
					<?php echo $form->labelEx($model,'description'); ?>
					<?php echo $form->textArea($model,'description', array('class' => 'form-poshytip', 'title' => 'Tell something about your clan.')); ?>
					<?php echo $form->error($model,'description'); ?>
				</div>

				<div class="row buttons">
					<?php echo CHtml::submitButton('add clan', array('id' => 'submit')); ?>
				</div>
			</fieldset>
		<?php $this->endWidget(); ?>
		</p>
<?php $this->endClip(); ?>
<?php $this->beginClip('second'); ?>
		<h2 class="line-divider">Join a clan</h2>
		<p>
			<ul>
			<?php $clans = Clans::model()->findAllByAttributes(array('status' => Clans::ACTIVE)); ?>
			<?php if(count($clans) < 1): ?>
				<li>Sorry but there are no clans active at the moment.</li>
			<?php endif;?>
			<?php foreach($clans as $clan): ?>
				<li><strong><?=CHtml::encode($clan->name)?></strong> <?=CHtml::encode($clan->description)?> <strong><?=CHtml::link('more info', array('//as/clan/view/', 'name' => $clan->name, 'id' => $clan->id))?></strong></li>
			<?php endforeach; ?>
			</ul>
		</p>		
<?php $this->endClip(); ?>

