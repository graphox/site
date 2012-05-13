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
<?php $container->start_tab('info'); ?><h2>Page Comments Management</h2>
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
	<label>user_id</label>
	<?=CHtml::textField('search[user_id]', (isset($_POST['search']) && isset($_POST['search']['user_id'])) ? $_POST['search']['user_id'] : '')?></fieldset>
<fieldset>
	<label>page_id</label>
	<?=CHtml::textField('search[page_id]', (isset($_POST['search']) && isset($_POST['search']['page_id'])) ? $_POST['search']['page_id'] : '')?></fieldset>
<fieldset>
	<label>title</label>
	<?=CHtml::textField('search[title]', (isset($_POST['search']) && isset($_POST['search']['title'])) ? $_POST['search']['title'] : '')?></fieldset>
<fieldset>
	<label>content</label>
	<?=CHtml::textField('search[content]', (isset($_POST['search']) && isset($_POST['search']['content'])) ? $_POST['search']['content'] : '')?></fieldset>
<fieldset>
	<label>posted_date</label>
	<?=CHtml::textField('search[posted_date]', (isset($_POST['search']) && isset($_POST['search']['posted_date'])) ? $_POST['search']['posted_date'] : '')?></fieldset>
<footer>
	<div class="submit_link">
		<input type="submit" name="yt0" value="submit" />
		<?=CHtml::link('reset', array('index', 'reset' => 'reset'), array('class' => 'lookalike-submit alt_btn'));?>	</div>
</footer>
</form>
<?php $container->end_tab() ?>

<?php $container->start_tab('Add Page Comments'); ?>
<?php $this->renderPartial('_add_form', get_defined_vars()); ?>
<?php $container->end_tab() ?>

<?php $this->endWidget(); ?>
</article>



<article class="module width_full">
	<header>
		<h3 class="tabs_involved">Page Comments Manager</h3>
		<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages, 'PaginatorElementView' => 'as.views.UI.admin.paginatorelement')); ?>		
	</header>
	
	<div class="module_content" style="margin:0">
		<table class="tablesorter" cellspacing="0">			<thead>
				<tr>
   					<th class="header"></th><!--selection-->
					<th class="header"><?=CHtml::link('id', array('', 'order-by' => 'id'))?></th>
					<th class="header"><?=CHtml::link('user id', array('', 'order-by' => 'user_id'))?></th>
					<th class="header"><?=CHtml::link('page id', array('', 'order-by' => 'page_id'))?></th>
					<th class="header"><?=CHtml::link('title', array('', 'order-by' => 'title'))?></th>
					<th class="header"><?=CHtml::link('content', array('', 'order-by' => 'content'))?></th>
					<th class="header"><?=CHtml::link('posted date', array('', 'order-by' => 'posted_date'))?></th>
   					<th class="header"></th><!--controls-->
				</tr>
			</thead>
			<tbody>
				<?php foreach($models as $row): ?>					<tr data-selectedrow="0">
						<td><input class="select-row" type="checkbox"></td>
							<td><?=$row->id?></td>
							<td><?=$row->user_id?></td>
							<td><?=$row->page_id?></td>
							<td><?=$row->title?></td>
							<td><?=$row->content?></td>
							<td><?=$row->posted_date?></td>
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
