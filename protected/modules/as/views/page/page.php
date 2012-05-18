<?php
!isset($p) && $p = new CHtmlPurifier();
$p->options = Yii::app()->params['purifier.settings'];

 ?>
 
<?php if(!isset($no_header) || $no_header == false): ?>
<div id="content">
	<!-- title -->
	<div id="page-title">
		<h1 class="title"><?=CHtml::encode($page->title);?></h1>
		<span class="subtitle"><?=CHtml::encode($page->description);?></span>
	</div>
	<!-- ENDS title -->

	<div id="posts" class="single">	
<?php endif; ?>	
		<!-- post -->
		<div class="post">
			<!--<h1><a href="..."><?=CHtml::encode($page->title);?></a></h1>-->
			<?php if($can->update): ?>
				<div class="mod-button" style="float:right;margin-top:-91.5px;margin-right:-141px">
					<?=CHtml::link('edit', array('//as/admin/pages/edit', 'id' => (int)$page->id))?>
				</div>
				<div style="clear:both"></div>
			<?php endif; ?>
		
			<!-- shadow -->
			<div class="thumb-shadow">
		
				<?php if(isset($page->thumb) && $page->thumb !== false): ?>
				<!-- post-thumb -->
				<div class="post-thumbnail">
					<img src="<?=CHtml::encode($page->thumb)?>"  alt="" />
				</div>
				<!-- ENDS post-thumb -->
				<?php endif; ?>
			
				<div id="web-page-<?=(int)$page->id?>">
					<?=$p->purify($page->content)?>
				</div>
			</div>
			<!-- ENDS shadow -->
						
			<?php if($page->module !== 'web' || isset($show_meta) && $show_meta): ?>
				<!-- meta -->
				<div class="meta">
					<h5>details:</h5>
					<p>
						<ul>
							<li><strong>Posted on</strong> <?=CHtml::encode($page->change_time)?></li>
							<li><strong>By</strong> <?=CHtml::link($page->editor->username, array('//as/user/profile', 'name' => $page->editor->username, 'id' => $page->editor->id))?></a></li> 

							<?php if(isset($page->tags)): ?>
							<li><strong>Tags</strong> 
								<div class="meta-tags">
									<?php foreach($page->tags as $tag): ?>
										<?=CHtml::link($tag->tag, array('//as/tag/find', 'name' => $tag->tag, 'id' => $tag->id))?>
									<?php endforeach; ?>
								</div>
							</li>
							<?php elseif($can->update): ?>
							<li>
								<strong>Tags</strong>
								<div class="meta-tags">
									<?=CHtml::link('Add tag', array('//as/tag/add', 'page-id' => $page->id))?>
								</div>
							</li>
							<?php else: ?>
							<li>
								<strong>Tags</strong>
								No tags yet
							</li>
							<?php endif; ?>
						</ul>
					</p>
				</div>
				<!-- ENDS meta -->									
			<?php endif; ?>
		</div>
		<!-- ENDS post -->
	
		<?php if($page->allow_comments): ?>
			<!-- Comments-Block -->
			<div id="comments-block">
				<div class="n-comments"><?=(int)count($page->pageComments)?></div> <h3 class="n-comments-text">comments</h3>
			
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
			
				<!-- comments list -->
				<ul class="commentlist">
					<?php foreach($models as $comment): ?>
						<li class="comment" id="comment-<?=(int)$comment->id?>">
							<div  class="comment-body">
								<h4><?=CHtml::encode($comment->title)?></h4>
								<div class="comment-author vcard">
									<img alt='' src="<?=Yii::app()->theme->baseUrl?>/img/dummies/avatar.jpg" class='avatar avatar-60 photo' height='60' width='60' />
									<cite class="fn"><?=$comment->user ? CHtml::link($comment->user->username, array('//as/profile/', 'name' => $comment->user->username)) : 'unkown'?></cite><span class="says"> says:</span>
								</div>
	
								<div class="comment-meta commentmetadata">
									<ul>
										<li style="float:left"><strong>Date</strong> <?=CHtml::encode($comment->posted_date)?></li>
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
										<li class="reaction vote" style="float:right">
											<strong>Votes</strong>
											<?=CHtml::link('+1: '.$counts[1], array('//as/comment/vote',
												'comment-id' => $comment->id,
												'way' => 'add'
											), array('class' => 'vote add'))?>
											<?=CHtml::link('-1: '.$counts[-1], array('//as/comment/vote',
												'comment-id' => $comment->id,
												'way' => 'sub'
											), array('class' => 'vote sub'))?>
										</li>
									</ul>
									<div style="clear:both"></div>
								</div>
								<?php if($can->update): ?>
									<div id="comment-mod-toolbar-<?=(int)$comment->id?>"></div>
								<?php endif;?>
					
								<div class="comment-real-body" id="real-body-comment-<?=(int)$comment->id?>">
									<?=$p->purify($comment->content)?>
								</div>

								<?php if($can->update): ?>
									<div class="mod-button">
										<?=CHtml::link('edit', array('//as/admin/comments/edit', 'id' => $comment->id), array('onclick' => 'editor(this, "#real-body-comment-'.(int)$comment->id.'", "'.$this->createUrl('//as/admin/comments/edit', array('id' => $comment->id, 'ajax' => 1)).'", "#comment-mod-toolbar-'.(int)$comment->id.'");return false'))?>
									</div>
								<?php endif; ?>
					
								<div class="reply">
									<?=CHtml::link('Reply', array('//as/comment/reply/', 'id' => $comment->id), array('class' => 'comment-reply-link', 'data-respond-field-id' => 'comment-form'))?>
								</div>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
				<!-- ENDS comments list -->
				
					
				<!-- Navi -->
				<div class="comments-pagination">
					<?php $this->widget('as.components.UI.pager.UIPager', array('currentpage' => $pages->getCurrentPage(), 'pages' => $pages)); ?>
				</div>
				<!-- ENDS Navi -->
		
				<?php if($can->write): ?>
					<!-- comments form -->
					<div id="respond">
						<div class="leave-comment">
							<h2>Leave a Reply</h2>
							<?php $model = PageComments::model(); ?>
							<?php $form = $this->beginWidget('CActiveForm', array(
								'id'=>'commentform',
								'enableAjaxValidation'=>true,
								'enableClientValidation' => true,
								'action' => array('//as/comment/add', 'page-id' => $page->id),
								'clientOptions' => array( 'validationUrl' => array('//as/comment/add', array('page-id' => $page->id)))
							)); ?>

								<p class="note">Fields with <span class="required">*</span> are required.</p>

								<?php echo $form->errorSummary($model); ?>
					
								<fieldset>
								<div>
									<?php echo $form->labelEx($model,'title'); ?>
									<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50, "tabindex" => 1)); ?>
									<?php echo $form->error($model,'title'); ?>
								</div>
						
								<div id="wysiwyg-comment-toolbar" style="visibility:hidden">

								</div>
						
								<div>
									<?php echo $form->labelEx($model,'content'); ?>
									<?php echo $form->textArea($model,'content', array("tabindex" => 2, 'class' => 'wysiwyg', 'onfocus' => '$("#wysiwyg-comment-toolbar").css("visibility", "visible");', 'onblur' => '$("#wysiwyg-comment-toolbar").css("visibility", "hidden");')); ?>
									<?php echo $form->error($model,'content'); ?>
								</div>

								<div>
									<?php echo CHtml::submitButton('Post', array('id' => 'submit')); ?>
								</div>
								</fieldset>

							<?php $this->endWidget(); ?>
						</div>
					</div>
					<!-- ENDS comments form -->
				<?php endif; ?>
			</div>
			<!-- ENDS Comments-block -->
		<?php endif; ?>
							
	</div>
	<!-- ENDS Posts -->	

	<!--begin sidebar -->
	<ul id="sidebar">
		<?php #TODO dbmenu widget ?>
		<li>
			<?php if(Yii::app()->user->isGuest): ?>
				<h6>User</h6>
				<ul>
					<li><?=CHtml::link('login', array('//as/auth', 'return-url' => rawurlencode($this->createurl($this->getId().'/'.$this->getAction()->getId(), $_GET))))?></li>
					<li><?=CHtml::link('register', array('//as/auth/register'))?></li>
				</ul>
			<?php else: ?>
				<h6>your profile</h6>
				<ul>
					<li>logged in as: <strong><?=CHtml::encode(Yii::app()->user->name)?></strong></li>
					<li><?=Chtml::link('logout', array('//as/auth/logout', 'return-url' => rawurlencode($this->createurl($this->getId().'/'.$this->getAction()->getId(), $_GET))))?></li>
				</ul>
			<?php endif; ?>
			<?php if(isset($sidebar)): ?>
				<?php foreach($sidebar as $element): ?>
					<h6><?=CHtml::encode($element['title'])?></h6>
					<ul>
						<?php foreach($element['sub'] as $item): ?>
						<li class="cat-item">
						 	<?=CHtml::link($item['text'], $item['url'], isset($item['htmloptions']) ? $item['htmloptions'] : array())?>
						</li>
						<?php endforeach; ?>
					</ul>
				<?php endforeach; ?>
			<?php endif; ?>
		</li>
	</ul>
	<!-- END Sidebar -->
</div>
