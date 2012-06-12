<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages, 'PaginatorElementView' => 'as.views.UI.paginatorelement')); ?>

<h3>Recent topics</h3>
<p>
<?php if(count($models) == 0): ?>
	There are no topics yet.
<?php else: ?>
	<?php foreach($models as $row): ?>
		<div>
			<ul>
				<li><strong>name</strong> <?=$row->name?></li>
				<li><strong>description</strong> <?=$row->description?></li>
				<li><?=CHtml::link('go', array('//as/forum/viewtopic/', 'topic' => $row->name, 'topicid' => $row->id)) ?></li>
			</ul>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<h3>Forums</h3>
	<?php foreach($forums as $forum): ?>
		<h4><?=$forum['name'] ?></h4><span><?=CHtml::link('go', array('//as/forum/viewforum/', 'forum' => $forum['name'])) ?>
		<?php foreach($forum['children'] as $child): ?>
			<h5><?=$child['name']?></h5>
		<?php endforeach; ?>
	<?php endforeach; ?>


