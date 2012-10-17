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

<?php $this->widget('application\components\widgets\CommentWidget', array('parent' => $model)); ?>