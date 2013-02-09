<?php
	/**
	 * @var $parent Entity
	 * @var $model RatingForm
	 */
	 $relations = $parent->findRelations('like');
	 
	 $total = 0;
	 foreach($relations as $relation)
		 if($relation->data == 1)
			 $total += 1;
?>
<div>
	<h3>Rating</h3>
	<fieldset>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'enableAjaxValidation'=>false,
			'action' => array('/rating'),
			'clientOptions' => array(
				'validateOnSubmit'	=>	true,
				'validateOnChange'	=>	true,
				'validateOnType'	=>	false,
			 ),
		)); ?>
		
		<?=CHtml::activeHiddenField($model, 'parentId')?>
		<?=CHtml::activeHiddenField($model, 'vote')?>

		<?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
			'toggle' => 'radio',
			'buttons'=>array(
				array('label'=>'Like ('.$total.')', 'htmlOptions' => array('onClick' => 'js:jQuery(jQuery("#'.$form->id.' input")[1]).val(1);jQuery("#'.$form->id.'").submit()')),
				array('label'=>'Dislike ('.(count($relations)-$total).')', 'htmlOptions' => array('onClick' => 'js:jQuery(jQuery("#'.$form->id.' input")[1]).val(0);jQuery("#'.$form->id.'").submit()')),
			),
		)); ?>

		<?php $this->endWidget(); ?>
	</fieldset>
</div>