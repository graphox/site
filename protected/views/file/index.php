<?php
echo CHtml::link('Upload files', array('/file/upload'));
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
			'type'=>'striped bordered',
			'dataProvider' => new CArrayDataProvider(Yii::app()->user->node->attachments),
			'template' => "{items}",
			'columns' => array(
				array(
					'class'=>'bootstrap.widgets.TbImageColumn',
					'imagePathExpression' => '$data->thumbUrl'
				),
				'id',
				'name',
				
				'routeName',
				'url',
				
				
				 array(
					'class'=>'bootstrap.widgets.TbRelationalColumn',
					 'url' => $this->createUrl('file/edit', array('ajax' => true)),
					 'value'=> '"edit"',
				)
			)
		));