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
		<h2>Comments (<span class="count"><?=(int)count($page->pageComments)?></span>)</h2>

		<div class="comments">
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
			
			<?php foreach($models as $comment): ?>
				<?php $this->beginWidget('zii.widgets.CPortlet', array(
					'title'=>'<span class="icon icon-user">'.CHtml::encode($comment->title).'</span>',
					'id' => 'comment-'.(int)$comment->id))?>
					
					<div class="showgrid container">
					
						<div class="comment-author span-3">
							<figure>
								<img alt='' src="<?=Yii::app()->theme->baseUrl?>/img/dummies/avatar.jpg" class='avatar avatar-60 photo' height='60' width='60' />
							</figure>
							<cite class="fn"><?=$comment->user ? CHtml::link($comment->user->username, array('//as/profile/', 'name' => $comment->user->username)) : 'unkown'?></cite><span class="says"> says:</span>
							
							
							<div class="comment-meta commentmetadata">
								<ul>
									<li><strong>Posted on</strong> <?=CHtml::encode($comment->posted_date)?></li>
									<?php
										/*
											$votes = $comment->commentVotes;
											if(count($votes) != 0)
												$avg = array_sum($votes) / count($votes);
											else
												$avg = 'no likes so far';*/

										$counts = array(
											-1 => 0,
											1 => 0
										);	

										foreach($comment->commentVotes as $vote)
											$counts[$vote->value]++;
									?>
							
									<li class="reaction vote">
										<strong>Votes</strong>
										<span class="info-votes" onclick="$('#comment-body-<?=(int)$comment->id?> > .chart').toggle()">
											<details>
												<summary>timeline</summary>
											</details>
										</span>
										<?=CHtml::link('+1: '.$counts[1], array('//as/page/comment',
											'action' => 'vote',
											'comment-id' => $comment->id,
											'way' => 'add'
										), array('class' => 'vote add'))?>
										<?=CHtml::link('-1: '.$counts[-1], array('//as/page/comment',
											'action' => 'vote',
											'comment-id' => $comment->id,
											'way' => 'sub'
										), array('class' => 'vote sub'))?>
									</li>
								</ul>
							</div>							
						</div>
	

						<div class="comment-body span-15" id="comment-body-<?=(int)$comment->id?>">
							<div class="chart" style="display:none">
								<?php
									$data = array();
									$total = 0;
									
									$headings = array();
													
									foreach($comment->commentVotes as $vote)
									{
										$total += $vote->value;
										$data[] = ($total / (count($data) + 2))*100;
														
										$headings[] = $total;
									}
					
									$this->widget('application.extensions.cvisualizewidget.CVisualizeWidget',array(
										'data'=>array(
											'headings'=>$headings,
											'data'=>array(
												'Rating' => $data
											)
										),
										'options' => array(
											'title'=>'Rating',
											'width' => 700,
											'height' => 300
										)
									));
								?>
								<p>total: <?=$total?> rating: <?=end($data)?></p>
							</div>
							
							<?php $this->beginWidget('as.components.UI.UIContentWidget', array('markup' => $comment->markup)); ?>
								<?=$comment->content?>
							<?php $this->endWidget(); ?>
							
							<?php if($can->update): ?>
								<div class="mod-button">
									<?=CHtml::link('edit', array('//as/page/comment', 'action' => 'edit', 'id' => $comment->id));?>
								</div>
							<?php endif; ?>
					
							<?php if($can->write): ?>
								<div class="reply">
									<?=CHtml::link('Reply', array('//as/page/comment', 'action' => 'reply', 'id' => $comment->id))?>
								</div>
							<?php endif; ?>
						</div>
						
					</div>
				<?php $this->endWidget();?>
			<?php endforeach; ?>
		</div>
		<!-- ENDS comments list -->
				
					
		<!-- Navi -->
		<div class="comments-pagination">
			<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages)); ?>
		</div>
		<!-- ENDS Navi -->
		
		<?php if($can->write): ?>
			<div class="form">
				<h2>Leave a Reply</h2>
						
				<?php $model = new PageComments; ?>
						
				<?php $form = $this->beginWidget('CActiveForm', array(
					'id'=>'commentform',
					'action' => array('//as/page/comment', 'action'=> 'add', 'page' => $page->id),
				)); ?>

				<p class="note">Fields with <span class="required">*</span> are required.</p>

				<?= $form->errorSummary($model); ?>
					
				<div class="row">
					<?php echo $form->labelEx($model,'title'); ?>
					<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50, "tabindex" => 1)); ?>
					<?php echo $form->error($model,'title'); ?>
				</div>

				<div class="row">
					<?=$form->labelEx($model,'content')?>
					<?php $this->widget(
						'ext.markitup.EMarkitupWidget',
						array(
							'model'=>$model,
							'attribute'=>'content',
							'htmlOptions'=>array('rows'=>15, 'cols'=>70),
							'options'=>array(
								 'previewParserPath' => Yii::app()->urlManager->createUrl('//as/page/editPreview')
							)
						)
					); ?>
							
					<?=$form->error($model,'content'); ?>
				</div>
					
				<div class="row">
					<?php echo $form->labelEx($model,'markup'); ?>
					<?php echo $form->dropDownList($model, 'markup', ContentMakeup::userAllowed()); ?>
					<?php echo $form->error($model,'markup'); ?>
				</div>						

				<div class="row">
					<?php echo CHtml::submitButton('Post', array('id' => 'submit')); ?>
				</div>

				<?php $this->endWidget(); ?>
			</div>
			<!-- ENDS comments form -->
		<?php endif; ?>
	<!-- ENDS Comments-block -->
	<?php endif; ?>
