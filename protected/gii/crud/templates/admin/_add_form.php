<?php
$fields = array();

?>
<?='<?php $form = $this->beginWidget(\'CActiveForm\', array(
	\'id\' => \''.$this->class2id($this->modelClass).'-form\',
	\'enableAjaxValidation\' => true,
	\'enableClientValidation\' => true,
	\'action\' => isset($action) ? $action : array(\''.$this->getControllerID().'/add\'),
	\'clientOptions\' => array( \'validationUrl\' => array(\''.$this->getControllerID().'/add\'))
	
));
?>' ?>

<p class="note">Fields with <span class="required">*</span> are required.</p>

<?='<?php
	echo $form->errorSummary($form_model);
?>'?>


<?php foreach($this->tableSchema->columns as $column): 

	#we assume that this automatically numbered
	if($column->isPrimaryKey)
		continue;
		
	$fields[] = $column->name;

?>

<?php if(!$column->isForeignKey): ?>
<fieldset>
		<label><?='<?=$form->labelEx($form_model,\''.$column->name.'\'); ?>'?></label>
		<?php
			if($column->size && $column->size <= 50)
				echo '<?=$form->textField($form_model,\''.$column->name.'\')?>';
				
			else
				echo '<?=$form->textArea($form_model,\''.$column->name.'\')?>';
		?>

		<?='<?=$form->error($form_model,\''.$column->name.'\')?>'?>
</fieldset>
<?php /* else: ?>
<fieldset>
		<label><?='<?=$form->labelEx($form_model,\''.$column->name.'\'); ?>'?></label>
		<p>Note this is a relational field, you did better use the dropdown boxes on the bottom!</p>
		<?php
			if($column->size && $column->size <= 50)
				echo '<?=$form->textField($form_model,\''.$column->name.'\')?>';
				
			else
				echo '<?=$form->textArea($form_model,\''.$column->name.'\')?>';
		?>

		<?='<?=$form->error($form_model,\''.$column->name.'\')?>'?>
</fieldset>
<?php */ endif;?>

<?php endforeach; ?>

<?php

foreach(CActiveRecord::model($this->modelClass)->relations() as $key => $relation)
{
	if($relation[0] == 'CBelongsToRelation'
		or $relation[0] == 'CHasOneRelation'
		or $relation[0] == 'CManyManyRelation')
	{
		#not needed?
		if(!in_array($relation[2], $fields))
			continue;
			
		printf('<label for="%s">Belonging %s</label>', $relation[1], $relation[1]);
		// Use the second attribute of the model, since the first is the id in
		// most cases
		if($columns = CActiveRecord::model($relation[1])->tableSchema->columns)
		{
			$j = 0;
			foreach($columns as $column) 
			{
				if(!$column->isForeignKey && ! $column->isPrimaryKey) {
					$num = $j;
					break;
				}
				$j++;
			}

			for($i = 0; $i < $j; $i++)
				next($columns);

			$field = current($columns);
			$is_empty = $field->allowNull ? 'true' : 'false';
			$style = $relation[0] == 'CManyManyRelation' ? 'checkbox' : 'dropdownlist';

			echo "\n<?php
					\$this->widget('application.components.Relation', array(
							'model' => \$form_model,
							'relation' => '{$key}',
							'fields' => '{$field->name}',
							'allowEmpty' => $is_empty,
							'style' => '{$style}',
							)
						); ?>";
		}
	}
}

?>

<footer>
	<div class="submit_link">
		<?='<?php echo CHtml::submitButton(\'Submit\'); ?>'?>
	</div>
</footer>

<?='<?php $this->endWidget(); ?>'?>

