<?php if($data->attachmentData === null): ?>
	<div class="flash-notice">
		Something went wrong while uploading this file:
		<h3><?=$data->name?></h3>
		<?=$data->html?>
	</div>
<?php else: ?>
	<article>
		<header>
			<h1><?=$data->name?></h1>
		</header>
		<div>
			<?=$data->html?>
		</div>
		<footer>
			<?php if(preg_match('#^image/.*$#', $data->attachmentData->type)): ?>
				<img src="<?=$this->createUrl('//as/content/view', array('type' => $data->type->name, 'id' => $data->id, 'name' => $data->name))?>" style="width:250px" />
			<?php endif; ?>
			<?=CHtml::link('download', array('//as/content/view', 'type' => $data->type->name, 'id' => $data->id, 'name' => $data->name));?>
		</footer>
	</article>
<?php endif; ?>
