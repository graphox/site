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
<?php $container->start_tab('info'); ?><h2>Acl Privileges Management</h2>
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
	<label>object_id</label>
	<?=CHtml::textField('search[object_id]', (isset($_POST['search']) && isset($_POST['search']['object_id'])) ? $_POST['search']['object_id'] : '')?></fieldset>
<fieldset>
	<label>group_id</label>
	<?=CHtml::textField('search[group_id]', (isset($_POST['search']) && isset($_POST['search']['group_id'])) ? $_POST['search']['group_id'] : '')?></fieldset>
<fieldset>
	<label>read</label>
	<?=CHtml::textField('search[read]', (isset($_POST['search']) && isset($_POST['search']['read'])) ? $_POST['search']['read'] : '')?></fieldset>
<fieldset>
	<label>write</label>
	<?=CHtml::textField('search[write]', (isset($_POST['search']) && isset($_POST['search']['write'])) ? $_POST['search']['write'] : '')?></fieldset>
<fieldset>
	<label>update</label>
	<?=CHtml::textField('search[update]', (isset($_POST['search']) && isset($_POST['search']['update'])) ? $_POST['search']['update'] : '')?></fieldset>
<fieldset>
	<label>delete</label>
	<?=CHtml::textField('search[delete]', (isset($_POST['search']) && isset($_POST['search']['delete'])) ? $_POST['search']['delete'] : '')?></fieldset>
<fieldset>
	<label>order_by</label>
	<?=CHtml::textField('search[order_by]', (isset($_POST['search']) && isset($_POST['search']['order_by'])) ? $_POST['search']['order_by'] : '')?></fieldset>
<footer>
	<div class="submit_link">
		<input type="submit" name="yt0" value="submit" />
		<?=CHtml::link('reset', array('index', 'reset' => 'reset'), array('class' => 'lookalike-submit alt_btn'));?>	</div>
</footer>
</form>
<?php $container->end_tab() ?>

<?php $container->start_tab('Add Acl Privileges'); ?>
<?php $this->renderPartial('_add_form', get_defined_vars()); ?>
<?php $container->end_tab() ?>

<?php $this->endWidget(); ?>
</article>



<article class="module width_full">
	<header>
		<h3 class="tabs_involved">Acl Privileges Manager</h3>
		<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages, 'PaginatorElementView' => 'as.views.UI.admin.paginatorelement')); ?>		
	</header>
	
	<div class="module_content" style="margin:0">
		<table class="tablesorter" cellspacing="0">			<thead>
				<tr>
   					<th class="header"></th><!--selection-->
					<th class="header"><?=CHtml::link('id', array('', 'order-by' => 'id'))?></th>
					<th class="header"><?=CHtml::link('object id', array('', 'order-by' => 'object_id'))?></th>
					<th class="header"><?=CHtml::link('group id', array('', 'order-by' => 'group_id'))?></th>
					<th class="header"><?=CHtml::link('read', array('', 'order-by' => 'read'))?></th>
					<th class="header"><?=CHtml::link('write', array('', 'order-by' => 'write'))?></th>
					<th class="header"><?=CHtml::link('update', array('', 'order-by' => 'update'))?></th>
					<th class="header"><?=CHtml::link('delete', array('', 'order-by' => 'delete'))?></th>
					<th class="header"><?=CHtml::link('order by', array('', 'order-by' => 'order_by'))?></th>
   					<th class="header"></th><!--controls-->
				</tr>
			</thead>
			<tbody>
				<?php foreach($models as $row): ?>					<tr data-selectedrow="0">
						<td><input class="select-row" type="checkbox"></td>
							<td><?=$row->id?></td>
							<td><?=$row->object_id?></td>
							<td><?=$row->group_id?></td>
							<td><?=$row->read?></td>
							<td><?=$row->write?></td>
							<td><?=$row->update?></td>
							<td><?=$row->delete?></td>
							<td><?=$row->order_by?></td>
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

	<footer>
		<div class="submit_link">
			<?=CHtml::link('Order', array('order'), array('class' => 'lookalike-submit alt_btn'));?>
		</div>
	</footer>
</article>
