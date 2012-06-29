<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages, 'PaginatorElementView' => 'as.views.UI.paginatorelement')); ?>

<h3>topics</h3>

<?php $this->widget('CLinkPager', array(
    'pages' => $pages,
)) ?>

<?php if(count($models) == 0): ?>
	<div class="flash-notice">
		There are no topics yet.
	</div>
<?php else: ?>
	<?php foreach($models as $row): ?>
		<?php $this->beginWidget('zii.widgets.CPortlet', array('title' => CHtml::link(CHtml::encode($row->title), array('//as/forum/viewtopic/', 'topic' => $row->title, 'topicid' => $row->id)))); ?>
			<?=CHtml::link('<strong>'.CHtml::encode($row->title).'</strong> '.CHtml::encode(substr($row->content, 0, 50)), array('//as/forum/viewtopic/', 'topic' => $row->title, 'topicid' => $row->id)) ?>
		<?php $this->endWidget(); ?>
	<?php endforeach; ?>
<?php endif; ?>

<?php $this->widget('CLinkPager', array(
    'pages' => $pages,
)) ?>

<?php if($can->write): ?>
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
		'id' => 'new-topic-button',
		'name' => 'new-topic-button',
		'buttonType' => 'link',
		'caption'=>'New topic',
		'url' => array('//as/forum/addtopic/', 'forum' => $forum->name, 'forumid' => $forum->id)
	));?>
<?php endif; ?>

<?php if($can->update): ?>
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
		'id' => 'new-forum-button',
		'name' => 'new-forum-button',
		'buttonType' => 'link',
		'caption'=>'New forum',
		'url' => array('//as/forum/addforum/', 'parent-id' => $forum->id)
	));?>
<?php endif; ?>

