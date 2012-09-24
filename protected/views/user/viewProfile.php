<?php
	/* @var $model User */
	/* @var $this UserController */
?>
<article>
	<header>
	<?=CHtml::link(
			'<h1>'.CHtml::encode($model->displayName.'\'s profile').'</h1>',
			array(
				'/user/profile',
				'name' => $model->username
			)
		)?>
	<?php if(!Yii::app()->user->isGuest && $model->id === Yii::app()->user->id): ?>
		<?=CHtml::link(
				'Edit profile.',
				array(
					'/user/profile',
					'name' => $model->username,
					'subaction' => 'edit',
				)
			)?>	
	<?php endif; ?>
	</header>
	
	<div class="well">
		<?=$model->content?>	
	</div>
	
	<h2>More info:</h2>
	
	
	<?php $this->widget('bootstrap.widgets.BootDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array('label'=>'Date Created', 'value' => Yii::app()->dateFormatter->formatDateTime($model->registeredDate)),
			array('name' => 'country', 'label' => 'Country'),
			array('name' => 'city', 'label' => 'City'),
			
			($model->publicEmail) ? array('name' => 'email', 'label' => 'email') : array('label' => 'email', 'value' => 'Not Public.'),
			($model->publicName) ? array('name' => 'name', 'label' => 'name', 'value' => $model->firstName.' '.$model->lastName) : array('label' => 'name', 'value' => 'Not Public.'),
			
			array('name' => 'homepage', 'label' => 'Homepage'),
			array('name'=>'canComment', 'label'=>'can be commented on'),
		),
	)); ?>
	
	<h2>Blogs</h2>
	<?php
		$blogs = $model->outRelationships('BLOG_OWNER_');
	?>
	
	<?php if(count($blogs) > 0): ?>
	<ul>
		<?php foreach($blogs as $blog): ?>
		<?php /** @var $blog Blog */ ?>
		<li><?=$blog->name?></li>
		<?php endforeach; ?>
	</ul>
	
	<?php endif; ?>
	
	<?php if(count($blogs) === 0 && Yii::app()->user->id === $model->id): ?>
		<div class="form-actions"> 
			<?php $this->widget('bootstrap.widgets.BootButton', array( 
				'buttonType'=>'anchor', 
				'type'=>'primary', 
				'label'=>'Create blog',
				'url'=>array('/blog/create'),
			)); ?>
		</div>
	<?php endif; ?>

</article>

<?php if(false && $model->canComment /**&& $model->can('comment.view')*/): ?>
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
	
	<?php if(!Yii::app()->user->isGuest /*$model->can('comment.add')*/): ?>
		<?php $this->widget('application.components.commentFormWidget', array('parentEntity' => $model)); ?>
	<?php endif; ?>

<?php endif; ?>