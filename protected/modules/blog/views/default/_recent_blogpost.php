<?php 
	/* @var $data BlogEntity */
?>

<article>
	<header>
	<?=CHtml::link(
			'<h3>'.CHtml::encode($data->title).'</h3>',
			array(
				'/blog/viewPost',
				'id' => $data->id,
				'title' => $data->routeName,
				'name' => $data->blog->routeName
			)
	)?>
	</header>
	
	<div class="well">
		<?=$data->content?>	
	</div>
</article>