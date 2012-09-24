<?php 
	/**
	 * @var BlogEntity $model
	 * @var BlogController $this 
	 */
?>

<article>
	<header>
	<?=CHtml::link(
			'<h1>'.CHtml::encode($model->title).'</h1>',
			array(
				'/blog/viewPost',
				'name' => $this->blog->routeName,
				'id' => $model->id,
				'title' => $model->routeName
			)
	)?>
	
	<?=CHtml::link(
			'Back to '.CHtml::encode($this->blog->name),
			array(
				'/blog/viewBlog',
				'name' => $this->blog->routeName,
			)
	)?>
	<?php #$this->widget('application.components.ratingWidget', array('parent' => $model)) ?>
	</header>
	
	<div class="well">
		<?=$model->content?>	
	</div>
	
	<h2>More info</h2>
	
	<?php
	/**
	 * @todo Add author information widget
	 */
	?>
	
	<?php $this->widget('bootstrap.widgets.BootDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array('name'=>'createdDate', 'label'=>'Date Created'),
			array('name'=>'canComment', 'label'=>'can be commented on'),
			array('name'=>'tagString', 'label' => 'Tags' )
		),
	)); ?>

</article>
<?php if($this->blog->hasAccess('blog.edit') || $this->blog->hasAccess('blog.delete') /*|| created this post ... && hasAccess('blog.edit.OWN')*/): ?>
<h2>Admin actions</h2>
<nav>
	<ul>
		<?php if($this->blog->hasAccess('blog.edit')): ?>
		<li>
			<?=CHtml::link(
				'Edit post',
				array(
					'/blog/updatePost',
					'name' => $this->blog->routeName,
					'id' => $model->id,
					'title' => $model->routeName
				)
			)?>
		</li>
		<?php endif; ?>
		
		<?php if($this->blog->hasAccess('blog.delete')): ?>
		<li>
			<?=CHtml::link(
				'Delete post',
				array(
					'/blog/deletePost',
					'name' => $this->blog->routeName,
					'id' => $model->id,
					'title' => $model->routeName
				)
			)?>
		</li>
		<?php endif; ?>
	</ul>
</nav>
<?php endif; ?>

<?php if(false && $model->canComment /*&& $model->can('comment.view')*/): ?>
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
					
				</div>
				
				<div>
					<?=$comment->content?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	
	<?php if(/*$model->can('comment.add')*/Yii::app()->user->isLoggedIn): ?>
		<?php #$this->widget('application.components.commentFormWidget', array('parentEntity' => $model)); ?>
	<?php endif; ?>

<?php endif; ?>
