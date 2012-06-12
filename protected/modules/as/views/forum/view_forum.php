<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages, 'PaginatorElementView' => 'as.views.UI.paginatorelement')); ?>

<h3>topics</h3>
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

<?php if($can->write): ?>
	<h3>Add a topic</h3>
	<?=CHtml::link('go', array('//as/forum/addtopic/', 'forum' => $forum->name, 'forumid' => $forum->id)) ?>
<?php endif; ?>



