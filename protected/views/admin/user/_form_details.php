<?php if($model->entity !== null): ?>

	<?php
	$gridDataProvider = new CArrayDataProvider($model->entity->metadata);
	$this->widget('bootstrap.widgets.BootGridView', array(
		'type'=>'striped bordered condensed',
		'dataProvider'=>$gridDataProvider,
		'template'=>"{items}",
		'columns'=>array(
		    array('name'=>'id', 'header'=>'#'),
		    array('name'=>'type', 'header'=>'type'),
		    array('name'=>'value', 'header'=>'value'),
		),
	)); ?>
<?php else: ?>
	<p>This user has no entity</p>
<?php endif; ?>
