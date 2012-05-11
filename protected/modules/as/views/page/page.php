<!-- title -->
<div id="page-title">
	<span class="title"><?=CHtml::encode($page->title);?></span>
	<span class="subtitle"><?=CHtml::encode($page->description);?></span>
</div>
<!-- ENDS title -->

<div id="posts" class="single">	
	<!-- post -->
	<div class="post">
		<h1><a href="..."><?=CHtml::encode($page->title);?></a></h1>
		<?php if($can_moderate): ?>
			<div class="mod-button" style="float:right;margin-top:-50px">
				<?=CHtml::link('more', array('page/moderate', 'page-id' => (int)$page->id))?>
				<?=CHtml::link('edit', array('page/moderate', 'id' => (int)$page->id), array('onclick' => 'editor(this, "#web-page-'.(int)$page->id.'", "'.$this->createUrl('page/moderate', array('id' => $page->id, 'ajaxsave' => 1)).'", "#web-page-'.(int)$page->id.'");return false'))?>
			</div>
			<div style="clear:both"></div>
		<?php endif; ?>
		
		<!-- shadow -->
		<div class="thumb-shadow">
		
			<?php if(isset($page->thumb) && $page->thumb !== false): ?>
			<!-- post-thumb -->
			<div class="post-thumbnail">
				<img src=".."  alt="Feature image" />
			</div>
			<!-- ENDS post-thumb -->
			<?php endif; ?>
			
			<div id="web-page-<?=(int)$page->id?>">
				<?=$content?>
			</div>
		</div>
		<!-- ENDS shadow -->
													
		<!-- meta -->
		<div class="meta">
			<div style="width:33%;float:left">
				<h5>details:</h5>
				<ul>
					<li><strong>Posted on</strong> <?=CHtml::encode($page->change_time)?></li>
					<li><strong>By</strong> <a href="#"><?=CHtml::encode($editor->username)?></a></li> 

					<?php if(isset($page->tags)): ?>
					<li><strong>Tags</strong> 
						<div class="meta-tags">
							<?php foreach($page->tags as $tag): ?>
								<?=CHtml::link($tag->name, array('tag/tag', 'name' => $tag->name, 'id' => $tag->od))?>
							<?php endforeach; ?>
						</div>
					</li>
					<?php else: ?>
					<li>
						<strong>Tags</strong>
						<div class="meta-tags">
							<?=CHtml::link('No tags Yet', array('tag/add', 'name' => $page->title, 'id' => $page->id))?>
						</div>
					</li>
					<?php endif; ?>
				</ul>
			</div>
			<!-- ENDS meta -->	

			<?php # if($editor->profile && $editor->profile->avatarImg): ?>
				<div class="comment-author vcard" style="float:right;width:120px;height:120px">
					<p></p>
					<img alt='' src="<?=Yii::app()->theme->baseUrl?>/img/dummies/avatar.jpg" class="avatar avatar-120 photo" height='120' width='120' />
				</div>
			<?php # endif; ?>
		
			<div class="articleditor" style="float:right;margin-right:5px;width:35%">
				<h5>About the Editor:</h5>

				<p class="profile-info large">
					<ul>
						<li><strong>Name</strong> <?=CHtml::encode($editor->username)?></li>
						<?php if($editor->profile): ?>
						<li><strong>Profile</strong>
							<div>
								<ul>
									<li><strong>hompage:</strong>
										<a class="untrusted-link" href="<?=htmlentities($editor->profile->homepage)?>" >
											<?=htmlentities(preg_replace('#http://#', '', $editor->profile->homepage))?>
										</a>
									</li>
									<li>
										<a class="popup-link" href="<?=$this->createUrl('profile/show', array('id' => $editor->profile->id))?>" >
											display profile
										</a>
									</li>
								</ul>
							</div>
						</ul>
						<?php else: ?>
							<li>No profile information available</li>
						<?php endif;?>
					</ul>		

		
				</p>
			</div>
		
			<div style="clear:both"></div>
		</div>
								
	</div>
	<!-- ENDS post -->
	
	<!-- Comments-Block -->
	<div id="comments-block">
		<div class="n-comments"><?=(int)count($comments) -1?></div><h3 class="n-comments-text">comments</h3>
				
		<!-- comments list -->
		<ul class="commentlist">
			<?php foreach($comments[0] as $comment): ?>
				<li class="comment" id="comment-<?=(int)$comment->id?>">
					<div  class="comment-body">
						<h4><?=CHtml::encode($comment->title)?></h4>
						<div class="comment-author vcard">
							<img alt='' src="<?=Yii::app()->theme->baseUrl?>/img/dummies/avatar.jpg" class='avatar avatar-60 photo' height='60' width='60' />
							<cite class="fn"><?=CHtml::encode($comment->user->username)?></cite><span class="says"> says:</span>
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
									<?=CHtml::link('+1: '.$counts[1], array('page/action',
										'id' => $page_id,
										'reaction' => $comment->id,
										'action' => 'vote',
										'add' => 1
									), array('class' => 'vote add'))?>
									<?=CHtml::link('-1: '.$counts[-1], array('page/action',
										'id' => $page_id,
										'reaction' => $comment->id,
										'action' => 'vote',
										'sub' => 1
									), array('class' => 'vote sub'))?>
								</li>
							</ul>
							<div style="clear:both"></div>
						</div>
						<?php if($can_moderate): ?>
							<div id="comment-mod-toolbar-<?=(int)$comment->id?>"></div>
						<?php endif;?>
					
						<div class="comment-real-body" id="real-body-comment-<?=(int)$comment->id?>">
							<?=$comment->content?>
						</div>

						<?php if($can_moderate): ?>
							<div class="mod-button">
								<?=CHtml::link('edit', array('reaction/edit', 'id' => $comment->id), array('onclick' => 'editor(this, "#real-body-comment-'.(int)$comment->id.'", "'.$this->createUrl('comment/moderate', array('id' => $comment->id, 'ajaxsave' => 1)).'", "#comment-mod-toolbar-'.(int)$comment->id.'");return false'))?>
							</div>
						<?php endif; ?>
					
						<div class="reply">
							<?=CHtml::link('Reply', array('comment/reply/', 'id' => $comment->id), array('class' => 'comment-reply-link', 'data-respond-field-id' => 'comment-form'))?>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<!-- ENDS comments list -->
				
					
		<!-- Navi -->
		<div class="comments-pagination">
			<!--TODO: move-->
			<style>
				.comments-pagination .page-numbers li a {
					display: block;
					float: left;
					margin: 2px 4px 2px 0;
					padding: 6px 9px 5px 9px;
					text-decoration: none;
					width: auto;
					color: white;
					background: #555;
					border-radius: 10px;
					-moz-border-radius: 10px;
					-webkit-border-radius: 10px;
				}
				.comments-pagination .page-numbers .hidden { display: none; }
				.comments-pagination .page-numbers .page.selected {
					background: black;
					color: white;
				}
			
			</style>
			<?php $this->widget('CLinkPager', array(
				'maxButtonCount'=>5,
				'header'=>'',
				'currentPage'=>$comments[1]->getCurrentPage(),
				'pages' => $comments[1],
				'htmlOptions' => array('class'=> 'page-numbers')
			)) ?>
		</div>
		<!-- ENDS Navi -->
		
		<?php if($can_comment): ?>
			<!-- comments form -->
			<div id="respond">
				<div class="leave-comment">
					<h2>Leave a Reply</h2>
					<?php $model = PageComments::model(); ?>
					<?php $form = $this->beginWidget('CActiveForm', array(
						'id'=>'commentform',
						'enableAjaxValidation'=>true,
						'enableClientValidation' => true,
						'action' => array('comment/add', 'page-id' => $page->id),
						'clientOptions' => array( 'validationUrl' => array('comment/add', array('page-id' => $page->id)))
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
							
</div>
<!-- ENDS Posts -->	

<!--begin sidebar -->
<ul id="sidebar">
	<li>
		<?php if(Yii::app()->user->isGuest): ?>
			<h6>User</h6>
			<ul>
				<li><?=CHtml::link('login', array('auth/index', 'return-url' => rawurlencode($this->createurl($this->getId().'/'.$this->getAction()->getId(), $_GET))))?></li>
				<li><?=CHtml::link('register', array('auth/register'))?></li>
			</ul>
		<?php else: ?>
			<h6>your profile</h6>
			<ul>
				<li>logged in as: <strong><?=CHtml::encode(Yii::app()->user->name)?></strong></li>
				<li><?=Chtml::link('logout', array('auth/logout', 'return-url' => rawurlencode($this->createurl($this->getId().'/'.$this->getAction()->getId(), $_GET))))?></li>
			</ul>
		<?php endif; ?>
		<h6>test bieb bieb bieb</h6>
		<ul>
			<li class="cat-item">
			 	<a>item</a>
			</li>
		</ul>
	</li>
</ul>
<!-- END Sidebar -->
