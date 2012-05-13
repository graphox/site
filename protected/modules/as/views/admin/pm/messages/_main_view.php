<?php

if(!isset($can))
{
	AccessControl::init();
	$can = new Access;
	$can->read = true;
	$can->write = false;
	$can->update = false;
	$can->delete = false;
}

if(!$can->read) return;

?>

<article class="module width_full">

<?php $container = $this->beginWidget('as.components.UI.UITabContainer', array('title' => '')); ?>
<?php $container->start_tab('info'); ?><h2>Pm Messages Management</h2>
<p>
	Select the tabs in the top right corner to ether search or add a row.
</p>
<p>
	Click the icons on the right side of the rows to edit, view or delete them.
</p>
<?php $container->end_tab() ?>
<?php $container->start_tab('search'); ?>
<?=CHtml::form()?>
<p>
	Enter text in the fields to search, blank fields are ignored.
</p>
<p>
	You can use =, <, > and <> in these fields.
</p>

<fieldset>
	<label>id</label>
	<?=CHtml::textField('search[id]', (isset($_POST['search']) && isset($_POST['search']['id'])) ? $_POST['search']['id'] : '')?></fieldset>
<fieldset>
	<label>sender_id</label>
	<?=CHtml::textField('search[sender_id]', (isset($_POST['search']) && isset($_POST['search']['sender_id'])) ? $_POST['search']['sender_id'] : '')?></fieldset>
<fieldset>
	<label>receiver_id</label>
	<?=CHtml::textField('search[receiver_id]', (isset($_POST['search']) && isset($_POST['search']['receiver_id'])) ? $_POST['search']['receiver_id'] : '')?></fieldset>
<fieldset>
	<label>read</label>
	<?=CHtml::textField('search[read]', (isset($_POST['search']) && isset($_POST['search']['read'])) ? $_POST['search']['read'] : '')?></fieldset>
<fieldset>
	<label>receiver_deleted</label>
	<?=CHtml::textField('search[receiver_deleted]', (isset($_POST['search']) && isset($_POST['search']['receiver_deleted'])) ? $_POST['search']['receiver_deleted'] : '')?></fieldset>
<fieldset>
	<label>receiver_dir_id</label>
	<?=CHtml::textField('search[receiver_dir_id]', (isset($_POST['search']) && isset($_POST['search']['receiver_dir_id'])) ? $_POST['search']['receiver_dir_id'] : '')?></fieldset>
<fieldset>
	<label>title</label>
	<?=CHtml::textField('search[title]', (isset($_POST['search']) && isset($_POST['search']['title'])) ? $_POST['search']['title'] : '')?></fieldset>
<fieldset>
	<label>content</label>
	<?=CHtml::textField('search[content]', (isset($_POST['search']) && isset($_POST['search']['content'])) ? $_POST['search']['content'] : '')?></fieldset>
<fieldset>
	<label>sended_date</label>
	<?=CHtml::textField('search[sended_date]', (isset($_POST['search']) && isset($_POST['search']['sended_date'])) ? $_POST['search']['sended_date'] : '')?></fieldset>
<footer>
	<div class="submit_link">
		<input type="submit" name="yt0" value="submit" />
		<?=CHtml::link('reset', array('index', 'reset' => 'reset'), array('class' => 'lookalike-submit alt_btn'));?>	</div>
</footer>
</form>
<?php $container->end_tab() ?>

<?php $container->start_tab('Add Pm Messages'); ?>
<?php $this->renderPartial('_add_form', get_defined_vars()); ?>
<?php $container->end_tab() ?>

<?php $this->endWidget(); ?>
</article>



<article class="module width_full">
	<header>
		<h3 class="tabs_involved">Pm Messages Manager</h3>
		<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages, 'PaginatorElementView' => 'as.views.UI.admin.paginatorelement')); ?>		
	</header>
	
	<div class="module_content" style="margin:0">
		<table class="tablesorter" cellspacing="0">			<thead>
				<tr>
   					<th class="header"></th><!--selection-->
					<th class="header"><?=CHtml::link('id', array('', 'order-by' => 'id'))?></th>
					<th class="header"><?=CHtml::link('sender id', array('', 'order-by' => 'sender_id'))?></th>
					<th class="header"><?=CHtml::link('receiver id', array('', 'order-by' => 'receiver_id'))?></th>
					<th class="header"><?=CHtml::link('read', array('', 'order-by' => 'read'))?></th>
					<th class="header"><?=CHtml::link('receiver deleted', array('', 'order-by' => 'receiver_deleted'))?></th>
					<th class="header"><?=CHtml::link('receiver dir id', array('', 'order-by' => 'receiver_dir_id'))?></th>
					<th class="header"><?=CHtml::link('title', array('', 'order-by' => 'title'))?></th>
					<th class="header"><?=CHtml::link('content', array('', 'order-by' => 'content'))?></th>
					<th class="header"><?=CHtml::link('sended date', array('', 'order-by' => 'sended_date'))?></th>
   					<th class="header"></th><!--controls-->
				</tr>
			</thead>
			<tbody>
				<?php foreach($models as $row): ?>					<tr data-selectedrow="0">
						<td><input class="select-row" type="checkbox"></td>
							<td><?=$row->id?></td>
							<td><?=$row->sender_id?></td>
							<td><?=$row->receiver_id?></td>
							<td><?=$row->read?></td>
							<td><?=$row->receiver_deleted?></td>
							<td><?=$row->receiver_dir_id?></td>
							<td><?=$row->title?></td>
							<td><?=$row->content?></td>
							<td><?=$row->sended_date?></td>
						<?php #controls ?>
																					<td class="controls">
									<form method="post">
										<input type="image" src="<?=Yii::app()->theme->baseUrl?>/images/icn_search.png" name="inspect"
											value="<?=$row->id?>" title="Inspect" />
											
										<input type="image" src="<?=Yii::app()->theme->baseUrl?>/images/icn_edit.png" name="edit"
											value="<?=$row->id?>" title="Edit" />
											
										<input type="image" src="<?=Yii::app()->theme->baseUrl?>/images/icn_trash.png" name="trash"
											value="<?=$row->id?>" title="Trash" />
									</form>
								</td>
													</tr>
				<?php endforeach; ?>			</tbody>
		</table>
	</div><!-- end of tab-->
</article>
