<?php
/* @var $dataProvider CArrayDataProvider */
?>
<h1>Blogs</h1>
<h2>Latest posts</h2>
<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_recent_blogpost',
    'sortableAttributes'=>array(
        'created_date'=>'Created',
    ),
));
?>
<h2>Browse blogs</h2>
<?=CHtml::link(
	'Create blog.',
	array('/blog/create'))?>
<?php
	$model = Blog::model()->findAllByAttributes(array('modelclass' => 'Blog'));
?>

	<ul>
	<?php foreach($model as $blog): ?>
		<li><?=CHtml::link(
			CHtml::encode($blog->name),
			array(
				'/blog/viewBlog',
				'name' => $blog->routeName
			))?></li>
	<?php endforeach; ?>
	</ul>