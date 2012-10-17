<div class="commentContainer" id="comment-<?=(int)$comment->id?>">
	<div class="comment span7 last">
		<div class="commentInner">
			<h4><?=$comment->title?></h4>
			<?=$comment->content?>
			<div class="commentActions">
				<ul>
					<li>
						<?=CHtml::link('reply', array('/comment/create', 'parentId' => $comment->id))?>
					</li>
					<li>
						<?=CHtml::link('link', '#comment-'.$comment->id)?>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="commentCreator span2">
		<?php if($comment->creator): #prevent errors on db corruption ?>
			<h5><?=CHtml::link(CHtml::encode($comment->creator->displayName), array('/user/profile', 'name' => $comment->creator->username))?></h5>
			<?php if(isset($comment->creator->badges) && is_array($comment->creator->badges)): ?>
				<?php foreach($comment->creator->badges as $badge): ?>
					<?php $this->widget('bootstrap.widgets.BootBadge', array(
						'htmlOptions' => array('class' => 'smallBadge'),
						'type'=>$badge->type,
						'label'=>$badge->label,
					)); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endif; ?>
		
		<div>Created on: <date><?=\Yii::app()->dateFormatter->formatDateTime($comment->createdTime) ?></date></div>
	</div>
	<div class="clear"></div>
</div>