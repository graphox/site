<article>
	<header>
		<h1><?=$data->name?></h1>
	</header>
	<div>
		<?=$data->html?>
	</div>
	<footer>
		<?=CHtml::link('read full ...', array('//as/content/view', 'type' => $data->type->name, 'id' => $data->id, 'name' => $data->name));?>
	</footer>
</article>
