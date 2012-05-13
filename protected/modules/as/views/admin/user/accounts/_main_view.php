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
<?php $container->start_tab('info'); ?><h2>Users Management</h2>
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
	<label>username</label>
	<?=CHtml::textField('search[username]', (isset($_POST['search']) && isset($_POST['search']['username'])) ? $_POST['search']['username'] : '')?></fieldset>
<fieldset>
	<label>ingame_password</label>
	<?=CHtml::textField('search[ingame_password]', (isset($_POST['search']) && isset($_POST['search']['ingame_password'])) ? $_POST['search']['ingame_password'] : '')?></fieldset>
<fieldset>
	<label>email</label>
	<?=CHtml::textField('search[email]', (isset($_POST['search']) && isset($_POST['search']['email'])) ? $_POST['search']['email'] : '')?></fieldset>
<fieldset>
	<label>hashing_method</label>
	<?=CHtml::textField('search[hashing_method]', (isset($_POST['search']) && isset($_POST['search']['hashing_method'])) ? $_POST['search']['hashing_method'] : '')?></fieldset>
<fieldset>
	<label>web_password</label>
	<?=CHtml::textField('search[web_password]', (isset($_POST['search']) && isset($_POST['search']['web_password'])) ? $_POST['search']['web_password'] : '')?></fieldset>
<fieldset>
	<label>salt</label>
	<?=CHtml::textField('search[salt]', (isset($_POST['search']) && isset($_POST['search']['salt'])) ? $_POST['search']['salt'] : '')?></fieldset>
<fieldset>
	<label>status</label>
	<?=CHtml::textField('search[status]', (isset($_POST['search']) && isset($_POST['search']['status'])) ? $_POST['search']['status'] : '')?></fieldset>
<footer>
	<div class="submit_link">
		<input type="submit" name="yt0" value="submit" />
		<?=CHtml::link('reset', array('index', 'reset' => 'reset'), array('class' => 'lookalike-submit alt_btn'));?>	</div>
</footer>
</form>
<?php $container->end_tab() ?>

<?php $container->start_tab('Add Users'); ?>
<?php $this->renderPartial('_add_form', get_defined_vars()); ?>
<?php $container->end_tab() ?>

<?php $this->endWidget(); ?>
</article>



<article class="module width_full">
	<header>
		<h3 class="tabs_involved">Users Manager</h3>
		<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages, 'PaginatorElementView' => 'as.views.UI.admin.paginatorelement')); ?>		
	</header>
	
	<div class="module_content" style="margin:0">
		<table class="tablesorter" cellspacing="0">			<thead>
				<tr>
   					<th class="header"></th><!--selection-->
					<th class="header"><?=CHtml::link('id', array('', 'order-by' => 'id'))?></th>
					<th class="header"><?=CHtml::link('username', array('', 'order-by' => 'username'))?></th>
					<th class="header"><?=CHtml::link('ingame password', array('', 'order-by' => 'ingame_password'))?></th>
					<th class="header"><?=CHtml::link('email', array('', 'order-by' => 'email'))?></th>
					<th class="header"><?=CHtml::link('hashing method', array('', 'order-by' => 'hashing_method'))?></th>
					<th class="header"><?=CHtml::link('web password', array('', 'order-by' => 'web_password'))?></th>
					<th class="header"><?=CHtml::link('salt', array('', 'order-by' => 'salt'))?></th>
					<th class="header"><?=CHtml::link('status', array('', 'order-by' => 'status'))?></th>
   					<th class="header"></th><!--controls-->
				</tr>
			</thead>
			<tbody>
				<?php foreach($models as $row): ?>					<tr data-selectedrow="0">
						<td><input class="select-row" type="checkbox"></td>
							<td><?=$row->id?></td>
							<td><?=$row->username?></td>
							<td><?=$row->ingame_password?></td>
							<td><?=$row->email?></td>
							<td><?=$row->hashing_method?></td>
							<td><?=$row->web_password?></td>
							<td><?=$row->salt?></td>
							<td><?=$row->status?></td>
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
