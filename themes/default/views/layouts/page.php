<?php $this->beginContent('//layouts/main'); ?>
<div id="content">
	<?php if(isset($this->title)): ?>
	<!-- title -->
	<div id="page-title">
		<h1 class="title"><?=CHtml::encode($this->title);?></h1>
		<?php if(isset($this->description)): ?>
			<span class="subtitle"><?=CHtml::encode($this->description);?></span>
		<?php endif; ?>
	</div>
	<!-- ENDS title -->
	<?php endif; ?>
	
	<?=$content?>

<?php $this->endContent(); ?>
