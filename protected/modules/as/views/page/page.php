<?php
	if(!isset($no_header) || $no_header == false)
	{
		isset($this->title) || $this->title = $page->title;
		isset($this->description) || $this->description = $page->description;
	}
?>
		<!-- post -->
		<div class="post">
			<h1><?=CHtml::encode($page->title);?></h1>
		
			<?php if(isset($page->thumb) && $page->thumb !== false): ?>
			<!-- post-thumb -->
			<div class="post-thumbnail">
				<img src="<?=CHtml::encode($page->thumb)?>"  alt="" />
			</div>
			<!-- ENDS post-thumb -->
			<?php endif; ?>
			
			<article id="web-page-<?=(int)$page->id?>">
				<?php $this->beginWidget('as.components.UI.UIContentWidget', array('markup' => $page->markup)); ?>
					<?=$page->content?>
				<?php $this->endWidget(); ?>
			</article>
			
			<?php if($can->update): ?>
				<div class="admin-bar">
					<?=CHtml::link('edit', array('//as/page/edit', 'id' => (int)$page->id), array('class' => 'button grey', 'style' => 'margin:0'))?>
				</div>				
			<?php endif; ?>
		</div>
		<!-- ENDS shadow -->
						
		<?php if($page->module !== 'web' || isset($show_meta) && $show_meta): ?>
			<?php $this->beginWidget('zii.widgets.CPortlet', array(
					'title'=>'<span class="icon icon-user">Details</span>',
			)); ?>
			<ul>
				<li><strong>Posted on</strong> <?=CHtml::encode($page->change_time)?></li>
				<li><strong>By</strong> <?=CHtml::link($page->editor->username, array(
																			'//as/user/profile',
																			'name' => $page->editor->username,
																			'id' => $page->editor->id)) ?></li> 

				<?php if(isset($page->tags)): ?>
					<li>
						<strong>Tags</strong> 
						<div class="meta-tags">
							<?php foreach($page->tags as $tag): ?>
								<?=CHtml::link($tag->tag, array('//as/page/find', 'tag' => $tag->tag, 'tag-id' => $tag->id))?>
							<?php endforeach; ?>
						</div>
					</li>
				<?php elseif($can->update): ?>
					<li>
						<strong>Tags</strong>
						<div class="meta-tags">
							<?=CHtml::link('Add tag', array('//as/page/tag', 'action' => 'add', 'page-id' => $page->id))?>
						</div>
					</li>
				<?php else: ?>
					<li>
						<strong>Tags</strong>
						No tags yet
					</li>
				<?php endif; ?>																		
			
			<?php $this->endWidget(); ?>
		<?php endif; #/if($page->module !== 'web' || isset($show_meta) && $show_meta) ?>
	
	
	
<?php if($page->allow_comments): ?>
	<?php $count = $page->pageComments; ?>
	<?php $model = new PageComments; ?>
			<?php
				$criteria = new CDbCriteria();
				$criteria->select = '*';
				$criteria->condition = 'page_id='.(int)$page->id;

				$count=	PageComments::model()->count($criteria);
				$pages=new CPagination($count);

				// results per page
				$pages->pageSize = isset($_GET['per-page']) ? (int)$_GET['per-page'] : 10;
				$pages->applyLimit($criteria);
				$models = PageComments::model()->with('user')->findAll($criteria);
			
			?>
	<?php $this->renderPartial('as.views.comments', CMap::mergeArray(array('action' => array('//as/page/comment', 'action'=> 'add', 'page' => $page->id)), get_defined_vars()));?>
<?php endif; ?>
