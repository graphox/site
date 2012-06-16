<?php
	!isset($p) && $p = new CHtmlPurifier();
	$p->options = Yii::app()->params['purifier.settings'];
?>

<h2><?=$gallery->page->title?></h2>
<p>
	<strong><?=$gallery->page->description?></strong>
</p>

<div>
<?=$p->purify($gallery->page->content);?>
</div>


<h3>images</h3>
<div class="floatgallery">
	<?php foreach($gallery->images as $image): ?>
		<div class="galleryelement">
			<a href="<?=CHtml::encode($this->createUrl('//as/gallery/image', array('id' => $image->id)))?>" title="<?=CHtml::encode($image->description)?>" >
				<h4><?=CHtml::encode($image->name)?></h4>
				<div>
					<strong><?=CHtml::encode($image->description)?></strong>
				</div>
				<img style="width:150px;" src="<?=CHtml::encode($this->createUrl('//as/gallery/rawimage', array('id' => $image->id)))?>" title="<?=CHtml::encode($image->description)?>" alt="<?=CHtml::encode($image->name)?>" />
			</a>
		</div>
	<?php endforeach; ?>
</div>
