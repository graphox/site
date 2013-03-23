<?php

//\Yii::app()->getContainer()->get('widget.timelineAction')->set($title, $body)->render();
?>
<div class="timeline-action">
	<?php if(isset($title)): ?>
	<header>
		<h3><?=$title?></h3>
	</header>
	<?php endif; ?>

	<?php if(isset($user) && $user instanceof \Graphox\Modules\User\User): ?>
	<aside>
		<h4><?=\CHtml::encode($user->getName())?></h4>
		<p>
			IMG
		</p>
	</aside>
	<?php endif; ?>
	<article>
		<?=$body?>
	</article>
</div>
