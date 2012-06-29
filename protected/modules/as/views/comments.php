		<?=isset($comment_header) && $comment_header == false ? '' : '<h2>Comments (<span class="count">'.(int)$count.'</span>)</h2>'?>

		<div class="comments">
	
			<?php foreach($models as $comment): ?>
				<?php $this->beginWidget('zii.widgets.CPortlet', array(
					'title'=>'<span class="icon icon-user">'.CHtml::encode($comment->title).'</span>',
					'id' => 'comment-'.(int)$comment->id))?>
					
					<div class="showgrid container">
					
						<div class="comment-author span-3">
							<figure>
								<img alt='' src="<?=Yii::app()->theme->baseUrl?>/img/dummies/avatar.jpg" class='avatar avatar-60 photo' height='60' width='60' />
							</figure>
							<cite class="fn"><?=$comment->user ? CHtml::link($comment->user->username, array('//as/user/profile/', 'id' => $comment->user->id, 'name' => $comment->user->username)) : 'unkown'?></cite><span class="says"> says:</span>
							
							
							<div class="comment-meta commentmetadata">
								<ul>
									<li><strong>Posted on</strong> <?=CHtml::encode($comment->posted_date)?></li>
									<?php if(isset($comment->commentVotes)): ?>
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
									<?php endif; ?>
								</ul>
							</div>							
						</div>
	

						<div class="comment-body span-15" id="comment-body-<?=(int)$comment->id?>">
							<?php if(isset($comment->commentVotes)): ?>
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
							<?php endif;?>
							
							<?php $this->beginWidget('as.components.UI.UIContentWidget', array('markup' => $comment->markup)); ?>
								<?=$comment->content?>
							<?php $this->endWidget(); ?>
							
							<?php if($can->update && !isset($no_show_edit)): ?>
								<div class="mod-button">
									<?=CHtml::link('edit', array('//as/page/comment', 'action' => 'edit', 'id' => $comment->id));?>
								</div>
							<?php endif; ?>
					
							<?php if($can->write && !isset($no_show_reply)): ?>
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

			<?php $this->renderPartial('as.views.edit', get_defined_vars()); ?>
			<!-- ENDS comments form -->
		<?php endif; ?>
	<!-- ENDS Comments-block -->
