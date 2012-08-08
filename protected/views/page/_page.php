<?php 
	/* @var $data PageEntity */
	$data = $data->getTypeModel();
?>

<article>
	<header>
	<?=CHtml::link(
			'<h1>'.CHtml::encode($data->title).'</h1>',
			array(
				'/page/view',
				'id' => $data->id,
				'title' => $data->title
			)
	)?>
	</header>
	
	<div class="well">
		<?=$data->content?>	
	</div>
	
	<summary>More info:</summary>
			
	<?php $this->widget('bootstrap.widgets.BootDetailView', array(
		'data'=>$data,
		'attributes'=>array(
			array('name'=>'created_date', 'label'=>'Date Created'),
			array('name'=>'can_comment', 'label'=>'can be commented on'),
		),
	)); ?>
	
</article>