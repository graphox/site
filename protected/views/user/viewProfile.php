<?php
	/* @var $model ProfileEntity */
	/* @var $this UserController */
?>
<article>
	<header>
	<?=CHtml::link(
			'<h1>'.CHtml::encode($model->name.'\'s profile').'</h1>',
			array(
				'/user/profile',
				'id' => $model->id,
				'name' => $model->name
			)
		)?>
		</header>
	
	<div class="well">
		<?=$model->content?>	
	</div>
	
	<h2>More info:</h2>
	
	<?php $this->widget('bootstrap.widgets.BootDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array('name'=>'created_date', 'label'=>'Date Created'),
			array('name' => 'country', 'label' => 'Country'),
			array('name' => 'homepage', 'label' => 'Homepage'),
			array('name'=>'can_comment', 'label'=>'can be commented on'),
		),
	)); ?>

</article>

<?php if($model->can_comment && $model->can('comment.view')): ?>
	<h2>Comments: <span class="count"><?=count($model->comments)?></span></h2>
	<div class="comments">
		<?php foreach($model->comments as $comment): ?>
			<div class="comment row">
				<div class="span2">
					Name
					image
					like
					everywhere
					else
					on
					the
					site
					<?php /* @todo widget */ ?>
					<?php $this->widget('application.components.ratingWidget', array('parent' => $comment->entity)) ?>
				</div>
				
				<div class="span3">
					<?=$comment->content?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	
	<?php if($model->can('comment.add')): ?>
		<?php $this->widget('application.components.commentFormWidget', array('parentEntity' => $model)); ?>
	<?php endif; ?>

<?php endif; ?>