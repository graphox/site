<article>
	<header>
		<h1><?=$model->name?></h1>
	</header>
	<div>
		<?=$model->html?>
	</div>
	<footer>
		<?php if($can->edit || $can->delete): ?>
			<nav>
				<ul>
					<?php if($can->edit): ?>
						<li>Edit</li>
					<?php endif; ?>
					
					<?php if($can->delete): ?>
						<li>Delete</li>
					<?php endif; ?>
				</ul>
			</nav>
		<?php endif; ?>
		
		<?php $this->widget('as.components.widgets.AsAttachmentsWidget', array('can' => $can, 'parent' => $model)); ?>
		
		<?php if($model->can_comment): ?>
			<?php $this->widget('as.components.widgets.AsCommentWidget', array('can' => $can, 'parent' => $model)); ?>
		<?php endif; ?>
	</footer>
</article>
