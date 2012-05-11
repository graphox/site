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

$this->breadcrumbs = array(
	array('Clans', 'index'),
	array('Manage', '')
);

Yii::app()->clientScript->registerScript('search', '
	$(".search-button").click(function(){
		$(".search-form").toggle();
		return false;
	});
	
	var controller_url = "'.$this->createUrl('Clans').'";
	
	$(".search-form form").submit(function(){
		$.fn.yiiGridView.update("clans-grid", {
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
');
?>

<article class="module width_full">

<?php $container = $this->beginWidget('as.components.UI.UITabContainer', array('title' => '')); ?>

<?php $container->start_tab('search'); ?>
	TODO: search fields
<?php $container->end_tab() ?>

<?php $container->start_tab('Add Clans'); ?>
<?php $this->renderPartial('_add_form', get_defined_vars()); ?>
<?php $container->end_tab() ?>

<?php $this->endWidget(); ?>
</article>



<article class="module width_full">
	<header>
		<h3 class="tabs_involved">Clans Manager</h3>
		<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages)); ?>		
	</header>
	
	<div class="module_content" style="margin:0">
		<table class="tablesorter" cellspacing="0">			<thead>
				<tr>
   					<th class="header"></th><!--selection-->
					<th class="header"><?=CHtml::link('id', array('', 'order-by' => 'id'))?></th>
					<th class="header"><?=CHtml::link('name', array('', 'order-by' => 'name'))?></th>
					<th class="header"><?=CHtml::link('description', array('', 'order-by' => 'description'))?></th>
					<th class="header"><?=CHtml::link('acl group id', array('', 'order-by' => 'acl_group_id'))?></th>
					<th class="header"><?=CHtml::link('status', array('', 'order-by' => 'status'))?></th>
					<th class="header"><?=CHtml::link('page id', array('', 'order-by' => 'page_id'))?></th>
					<th class="header"><?=CHtml::link('forum id', array('', 'order-by' => 'forum_id'))?></th>
   					<th class="header"></th><!--controls-->
				</tr>
			</thead>
			<tbody>
				<?php foreach($models as $row): ?>					<tr data-selectedrow="0">
						<td><input class="select-row" type="checkbox"></td>
							<td><?=$row->id?></td>
							<td><?=$row->name?></td>
							<td><?=$row->description?></td>
							<td><?=$row->acl_group_id?></td>
							<td><?=$row->status?></td>
							<td><?=$row->page_id?></td>
							<td><?=$row->forum_id?></td>
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
