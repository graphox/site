<?php
/*
	Alphaserv Admin Crud Template

*/

$label = $this->pluralize(
	$this->class2name(
		$this->modelClass
	)
);

echo '<?php

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

<?php $container = $this->beginWidget(\'as.components.UI.UITabContainer\', array(\'title\' => \'\')); ?>'?>

<?='<?php $container->start_tab(\'info\'); ?>'?>
<h2><?=$label?> Management</h2>
<p>
	Select the tabs in the top right corner to ether search or add a row.
</p>
<p>
	Click the icons on the right side of the rows to edit, view or delete them.
</p>
<?='<?php $container->end_tab() ?>'?>

<?='<?php $container->start_tab(\'search\'); ?>'?>

<?='<?=CHtml::form()?>'?>

<p>
	Enter text in the fields to search, blank fields are ignored.
</p>
<p>
	You can use =, <, > and <> in these fields.
</p>

<?php foreach($this->tableSchema->columns as $column): ?>
<fieldset>
	<label><?=CHtml::encode($column->name)?></label>
	<?='<?=CHtml::textField(\'search['.$column->name.']\', (isset($_POST[\'search\']) && isset($_POST[\'search\'][\''.$column->name.'\'])) ? $_POST[\'search\'][\''.$column->name.'\'] : \'\')?>'?>
</fieldset>
<?php endforeach; ?>
<footer>
	<div class="submit_link">
		<?=CHtml::submitButton()?>

		<?='<?=CHtml::link(\'reset\', array(\'index\', \'reset\' => \'reset\'), array(\'class\' => \'lookalike-submit alt_btn\'));?>'?>
	</div>
</footer>
</form>
<?='<?php $container->end_tab() ?>

<?php $container->start_tab(\'Add '.$label.'\'); ?>
<?php $this->renderPartial(\'_add_form\', get_defined_vars()); ?>
<?php $container->end_tab() ?>

<?php $this->endWidget(); ?>
</article>

'?>


<article class="module width_full">
	<header>
		<h3 class="tabs_involved"><?=$label?> Manager</h3>
		<?='<?php $this->widget(\'as.components.UI.pager.UIPager\', array(\'currentpage\' => $pages->getCurrentPage(), \'pages\' => $pages, \'PaginatorElementView\' => \'as.views.UI.admin.paginatorelement\')); ?>'?>		
	</header>
	
	<div class="module_content" style="margin:0">
		<table class="tablesorter" cellspacing="0"><?php #TODO: use extended gridview ?>
			<thead>
				<tr>
   					<th class="header"></th><!--selection-->
<?php foreach($this->tableSchema->columns as $column): ?>
					<th class="header"><?='<?=CHtml::link(\''.str_replace('_', ' ', str_replace('-', ' ', $column->name)).'\', array(\'\', \'order-by\' => \''.$column->name.'\'))?>'?></th>
<?php endforeach; ?>
   					<th class="header"></th><!--controls-->
				</tr>
			</thead>
			<tbody>
				<?='<?php foreach($models as $row): ?>'?>
					<tr data-selectedrow="0">
						<td><input class="select-row" type="checkbox"></td>
<?php foreach($this->tableSchema->columns as $column): ?>
							<td><?='<?=$row->'.$column->name.'?>'?></td>
<?php endforeach; ?>
						<?='<?php #controls ?>'?>

						<?php foreach($this->tableSchema->columns as $column): ?>
							<?php if($column->isPrimaryKey): ?>
								<td class="controls">
									<form method="post">
										<input type="image" src="<?='<?=Yii::app()->theme->baseUrl?>'?>/images/icn_search.png" name="inspect"
											value="<?='<?=$row->'.$column->name.'?>'?>" title="Inspect" />
											
										<input type="image" src="<?='<?=Yii::app()->theme->baseUrl?>'?>/images/icn_edit.png" name="edit"
											value="<?='<?=$row->'.$column->name.'?>'?>" title="Edit" />
											
										<input type="image" src="<?='<?=Yii::app()->theme->baseUrl?>'?>/images/icn_trash.png" name="trash"
											value="<?='<?=$row->'.$column->name.'?>'?>" title="Trash" />
									</form>
								</td>
								<?php break;?>
							<?php endif; ?>
						<?php endforeach; ?>
					</tr>
				<?='<?php endforeach; ?>'?>
			</tbody>
		</table>
	</div><!-- end of tab-->
</article>
