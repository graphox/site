<div class="float-container">

<?php foreach($galleries as $gallery): ?>
	<div class="float-left">
		<?php if(($icon = $gallery->icon) == null) $icon = (count($gallery->images) == 0) ? null : $gallery->images[0]; ?>
	
		<a href="<?=$this->createUrl('//as/gallery', array('id' => $gallery->id))?>">
			<figure>
				<?php if($icon): ?>
					<img style="width:100px" src="<?=$this->createUrl('//as/gallery/rawimage', array('id' => $icon->id))?>" />
				<?php else: ?>
					no icon..
				<?php endif; ?>
				<figcaption>
					<?=CHtml::encode($gallery->page->title)?>
				</figcaption>
			</figure>
		</a>
	</div>
<?php endforeach; ?>
</div>
<div class="clear"></div>
