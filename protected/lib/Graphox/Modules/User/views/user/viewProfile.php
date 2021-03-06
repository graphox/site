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
	
	
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			'registeredDate:datetime',
			'country',
			'city',
			
			($model->publicEmail)
				? 'email:email'
				: array(
					'label' => Yii::t('as.models.user', 'Email'),
					'value' => Yii::t('as.models.user', 'Email address is not public.')
				),
			
			($model->publicName)
				? array(
					'label' => Yii::t('as.models.user', 'Name'),
					'value' => $model->firstName.' '.$model->lastName
				)
				: array(
					'label' => Yii::t('as.models.user', 'Name'),
					'value' => Yii::t('as.models.user', 'Name address is not public.')
				),
			
			'homepage:url',
			'canComment:boolean',
		),
	)); ?>
	
	<h2>Blogs</h2>
	<?php
		$blogs = $model->blogs;
	?>
	
	<?php if(count($blogs) > 0): ?>
	<ul>
		<?php foreach($blogs as $blog): ?>
		<?php /** @var $blog Blog */ ?>
		<li>
			<?=CHtml::link(
				CHtml::encode($blog->endNode->name),
				array(
					'blog/viewBlog',
					 'name' => $blog->endNode->name
				));
			?>
		</li>
		<?php endforeach; ?>
	</ul>
	
	<?php endif; ?>
	
	<?php if(count($blogs) === 0 && Yii::app()->user->id === $model->id): ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array( 
				'buttonType'=>'anchor', 
				'type'=>'primary', 
				'label'=>'Create blog',
				'url'=>array('/blog/create'),
		)); ?>
	<?php endif; ?>

</article>

<?php $this->widget('application\components\widgets\CommentWidget', array('parent' => $model)); ?>