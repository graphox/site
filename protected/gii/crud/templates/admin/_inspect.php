
<ul>
<?php foreach($this->tableSchema->columns as $column): ?>
	<li>
		<strong><?=$column->name?>:</strong>
		<span><?='<?=CHtml::encode($model->'.$column->name.')?>'?></span>
	</li>
<?php endforeach; ?>
</ul>
