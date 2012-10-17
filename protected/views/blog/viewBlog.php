<h1><?=CHtml::encode($model->name)?></h1>
<?=$model->description?>

<h2>Creators</h2>
<ul>
	<?php foreach($model->owners as $owner): ?>
		<li><?=$owner->username?></li>
	<?php endforeach;?>
</ul>

<h2>Latest posts</h2>
<?php if($model->hasAccess('blog.create')): ?>
	<?=CHtml::link('Create a new post', array('/blog/createPost', 'name' => $this->blog->routeName))?>
<?php endif; ?>
<?php
$dataProvider = new CArrayDataProvider($model->posts);
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_recent_blogpost',
    'sortableAttributes'=>array(
        'created_date'=>'Created',
    ),
));
?>

<?php $this->widget('application\components\widgets\CommentWidget', array('parent' => $model)); ?>