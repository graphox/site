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
<?php $container->start_tab('info'); ?><h2>Images Management</h2>
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
	<label>name</label>
	<?=CHtml::textField('search[name]', (isset($_POST['search']) && isset($_POST['search']['name'])) ? $_POST['search']['name'] : '')?></fieldset>
<fieldset>
	<label>description</label>
	<?=CHtml::textField('search[description]', (isset($_POST['search']) && isset($_POST['search']['description'])) ? $_POST['search']['description'] : '')?></fieldset>
<fieldset>
	<label>added_date</label>
	<?=CHtml::textField('search[added_date]', (isset($_POST['search']) && isset($_POST['search']['added_date'])) ? $_POST['search']['added_date'] : '')?></fieldset>
<fieldset>
	<label>owned_by</label>
	<?=CHtml::textField('search[owned_by]', (isset($_POST['search']) && isset($_POST['search']['owned_by'])) ? $_POST['search']['owned_by'] : '')?></fieldset>
<footer>
	<div class="submit_link">
		<input type="submit" name="yt0" value="submit" />
		<?=CHtml::link('reset', array('index', 'reset' => 'reset'), array('class' => 'lookalike-submit alt_btn'));?>	</div>
</footer>
</form>
<?php $container->end_tab() ?>

<?php $container->start_tab('Add Images'); ?>
<?php $this->renderPartial('_add_form', get_defined_vars()); ?>
<?php $container->end_tab() ?>

<?php $this->endWidget(); ?>
</article>



<article class="module width_full">
	<header>
		<h3 class="tabs_involved">Images Manager</h3>
		<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages, 'PaginatorElementView' => 'as.views.UI.admin.paginatorelement')); ?>		
	</header>
	
	<div class="module_content" style="margin:0">
		<table class="tablesorter" cellspacing="0">			<thead>
				<tr>
   					<th class="header"></th><!--selection-->
					<th class="header"><?=CHtml::link('id', array('', 'order-by' => 'id'))?></th>
					<th class="header"><?=CHtml::link('name', array('', 'order-by' => 'name'))?></th>
					<th class="header"><?=CHtml::link('description', array('', 'order-by' => 'description'))?></th>
					<th class="header"><?=CHtml::link('added date', array('', 'order-by' => 'added_date'))?></th>
					<th class="header"><?=CHtml::link('owned by', array('', 'order-by' => 'owned_by'))?></th>
   					<th class="header"></th><!--controls-->
				</tr>
			</thead>
			<tbody>
				<?php foreach($models as $row): ?>					<tr data-selectedrow="0">
						<td><input class="select-row" type="checkbox"></td>
							<td><?=$row->id?></td>
							<td><?=$row->name?></td>
							<td><?=$row->description?></td>
							<td><?=$row->added_date?></td>
							<td><?=$row->owned_by?></td>
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