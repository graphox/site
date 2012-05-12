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

Yii::app()->clientScript->registerScript(\'search\', \'
	$(".search-button").click(function(){
		$(".search-form").toggle();
		return false;
	});
	
	var controller_url = "\'.$this->createUrl(\''.str_replace('Controller', '', $this->controllerClass).'\').\'";
	
	$(".search-form form").submit(function(){
		$.fn.yiiGridView.update("'.$this->class2id($this->modelClass).'-grid", {
			data: $(this).serialize()
		});

		return false;
	});
	
	$(".relation-iframe").click(function(){
		
		var id = "relation-"+relation_name;
		var self = $(this);

		//clean up:
			$("#"+id).display("none");
			$("a[href=#"+id+"]").parent().display("none");
		//end
		
		$("header .tabs").append("<li class=\"active relation\"><a href=\"#"+id+"\">relation</a></li>");
		$(".tab-container").append("<div class=\"tab_content\" style=\"display:block;\" id=\""+id+"\"></div>");
		$("#"+id).load(self.attr("href")+"/ajax/1");
		
		return false;
	})
\');
?>

<article class="module width_full">

<?php $container = $this->beginWidget(\'as.components.UI.UITabContainer\', array(\'title\' => \'\')); ?>

<?php $container->start_tab(\'search\'); ?>
	TODO: search fields
<?php $container->end_tab() ?>

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
