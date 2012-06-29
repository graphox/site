<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages, 'PaginatorElementView' => 'as.views.UI.paginatorelement')); ?>

<h3>Recent topics</h3>
<p>
<?php if(count($models) == 0): ?>
	<div class="flash-notice">
		There are no topics yet.
	</div>
<?php else: ?>

	<?php $this->beginWidget('zii.widgets.CPortlet', array('title' => CHtml::encode('recent posts'))); ?>
		<ul>
			<?php foreach($models as $row): ?>
				<li>
					<?=CHtml::link('<strong>'.CHtml::encode($row->title).'</strong> '.CHtml::encode($row->content), array('//as/forum/viewtopic/', 'topic' => $row->title, 'topicid' => $row->id)) ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php $this->endWidget(); ?>
<?php endif; ?>

<h3>Forums</h3>
<?php if(count($forums) == 0): ?>
	<div class="flash-notice">
		There are no forums yet!
	</div>
<?php endif; ?>

<?php foreach($forums as $forum): ?>
	<?php $this->beginWidget('zii.widgets.CPortlet', array('title' => CHtml::link(CHtml::encode($forum['name']), array('//as/forum/viewforum/','id' => $forum['id'],'forum' => $forum['name'])))); ?>
		<p>
			<?=CHtml::encode($forum['description'])?>
		</p>
		<h4>Sub forums</h4>
		<ul>
		<?php foreach($forum['children'] as $child): ?>
			<li><?=CHtml::link('<strong>'.CHtml::encode($child['name']).'</strong> '.CHtml::encode($child['description']), array('//as/forum/viewforum/', 'id' => $child['id'], 'forum' => $child['name']))?></li>
		<?php endforeach; ?>
		</ul>
	<?php $this->endWidget(); ?>
<?php endforeach; ?>

<?php if($can->update): ?>
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
		'id' => 'new-forum-button',
		'name' => 'new-forum-button',
		'buttonType' => 'link',
		'caption'=>'New forum',
		'url' => array('//as/forum/addforum/', 'parent-id' => -1)
	));?>
<?php endif; ?>
