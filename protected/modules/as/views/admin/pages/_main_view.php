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
<?php $container->start_tab('info'); ?><h2>Pages Management</h2>
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
	<label>module</label>
	<?=CHtml::textField('search[module]', (isset($_POST['search']) && isset($_POST['search']['module'])) ? $_POST['search']['module'] : '')?></fieldset>
<fieldset>
	<label>uri</label>
	<?=CHtml::textField('search[uri]', (isset($_POST['search']) && isset($_POST['search']['uri'])) ? $_POST['search']['uri'] : '')?></fieldset>
<fieldset>
	<label>parent_id</label>
	<?=CHtml::textField('search[parent_id]', (isset($_POST['search']) && isset($_POST['search']['parent_id'])) ? $_POST['search']['parent_id'] : '')?></fieldset>
<fieldset>
	<label>editor_id</label>
	<?=CHtml::textField('search[editor_id]', (isset($_POST['search']) && isset($_POST['search']['editor_id'])) ? $_POST['search']['editor_id'] : '')?></fieldset>
<fieldset>
	<label>title</label>
	<?=CHtml::textField('search[title]', (isset($_POST['search']) && isset($_POST['search']['title'])) ? $_POST['search']['title'] : '')?></fieldset>
<fieldset>
	<label>description</label>
	<?=CHtml::textField('search[description]', (isset($_POST['search']) && isset($_POST['search']['description'])) ? $_POST['search']['description'] : '')?></fieldset>
<fieldset>
	<label>allow_comments</label>
	<?=CHtml::textField('search[allow_comments]', (isset($_POST['search']) && isset($_POST['search']['allow_comments'])) ? $_POST['search']['allow_comments'] : '')?></fieldset>
<fieldset>
	<label>layout</label>
	<?=CHtml::textField('search[layout]', (isset($_POST['search']) && isset($_POST['search']['layout'])) ? $_POST['search']['layout'] : '')?></fieldset>
<fieldset>
	<label>content</label>
	<?=CHtml::textField('search[content]', (isset($_POST['search']) && isset($_POST['search']['content'])) ? $_POST['search']['content'] : '')?></fieldset>
<fieldset>
	<label>change_time</label>
	<?=CHtml::textField('search[change_time]', (isset($_POST['search']) && isset($_POST['search']['change_time'])) ? $_POST['search']['change_time'] : '')?></fieldset>
<fieldset>
	<label>acl_object_id</label>
	<?=CHtml::textField('search[acl_object_id]', (isset($_POST['search']) && isset($_POST['search']['acl_object_id'])) ? $_POST['search']['acl_object_id'] : '')?></fieldset>
<footer>
	<div class="submit_link">
		<input type="submit" name="yt0" value="submit" />
		<?=CHtml::link('reset', array('index', 'reset' => 'reset'), array('class' => 'lookalike-submit alt_btn'));?>	</div>
</footer>
</form>
<?php $container->end_tab() ?>

<?php $container->start_tab('Add Pages'); ?>
<?php $this->renderPartial('_add_form', get_defined_vars()); ?>
<?php $container->end_tab() ?>

<?php $this->endWidget(); ?>
</article>



<article class="module width_full">
	<header>
		<h3 class="tabs_involved">Pages Manager</h3>
		<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages, 'PaginatorElementView' => 'as.views.UI.admin.paginatorelement')); ?>		
	</header>
	
	<div class="module_content" style="margin:0">
		<table class="tablesorter" cellspacing="0">			<thead>
				<tr>
   					<th class="header"></th><!--selection-->
					<th class="header"><?=CHtml::link('id', array('', 'order-by' => 'id'))?></th>
					<th class="header"><?=CHtml::link('module', array('', 'order-by' => 'module'))?></th>
					<th class="header"><?=CHtml::link('uri', array('', 'order-by' => 'uri'))?></th>
					<th class="header"><?=CHtml::link('parent id', array('', 'order-by' => 'parent_id'))?></th>
					<th class="header"><?=CHtml::link('editor id', array('', 'order-by' => 'editor_id'))?></th>
					<th class="header"><?=CHtml::link('title', array('', 'order-by' => 'title'))?></th>
					<th class="header"><?=CHtml::link('description', array('', 'order-by' => 'description'))?></th>
					<th class="header"><?=CHtml::link('allow comments', array('', 'order-by' => 'allow_comments'))?></th>
					<th class="header"><?=CHtml::link('layout', array('', 'order-by' => 'layout'))?></th>
					<th class="header"><?=CHtml::link('content', array('', 'order-by' => 'content'))?></th>
					<th class="header"><?=CHtml::link('change time', array('', 'order-by' => 'change_time'))?></th>
					<th class="header"><?=CHtml::link('acl object id', array('', 'order-by' => 'acl_object_id'))?></th>
   					<th class="header"></th><!--controls-->
				</tr>
			</thead>
			<tbody>
				<?php foreach($models as $row): ?>					<tr data-selectedrow="0">
						<td><input class="select-row" type="checkbox"></td>
							<td><?=$row->id?></td>
							<td><?=$row->module?></td>
							<td><?=$row->uri?></td>
							<td><?=$row->parent_id?></td>
							<td><?=$row->editor_id?></td>
							<td><?=$row->title?></td>
							<td><?=$row->description?></td>
							<td><?=$row->allow_comments?></td>
							<td><?=$row->layout?></td>
							<td><?=$row->content?></td>
							<td><?=$row->change_time?></td>
							<td><?=$row->acl_object_id?></td>
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
