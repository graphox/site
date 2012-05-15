<?php !isset($p) && $p = new CHtmlPurifier(); ?>
<div id="content">
	<!-- title -->
	<div id="page-title">
		<h1 class="title"><?=CHtml::encode($clan->name);?> clan</h1>
	</div>

	<div id="page-content">

		<div>
			<h2>About this clan:</h2>
			<p>
				<?=CHtml::encode($clan->description)?>
			</p>
		</div>
		
		<div style="width:966px;height:30px;background: url(<?=Yii::app()->theme->baseUrl?>/img/tabs-divider.png) repeat-x bottom left">
			<h2 style="float:left">details</h2>
			<ul class="tabs" style="float:left;background:none;">
				<li><a href="#"><span>Pages</span></a></li>
				<li><a href="#"><span>Forum</span></a></li>
				<li><a href="#"><span>Members</span></a></li>
				<li><a href="#"><span>Tags</span></a></li>
			</ul>
		</div>
		
		<div class="clear"></div>
		
		<div class="panes">
			<div>
				<?php if($clan->page && ($clan->page->module == 'clan' || $clan->page->module == 'clans')): ?>
					<?php $can = AccessControl::GetUserAccess($clan->page->aclObject); ?>
					<?php if($can->read): ?>	
						<div id="posts" class="single" >
							<!-- post -->
							<div class="post">
								<h2><?=CHtml::encode($clan->page->title);?></h2>
								<?php if($can->update): ?>
									<div class="mod-button" style="float:right;margin-top:-91.5px;margin-right:-141px;background:none;">
										<?=CHtml::link('edit', array('//as/admin/pages/edit', 'id' => (int)$clan->page->id))?>
									</div>
									<div style="clear:both"></div>
								<?php endif; ?>
							
								<!-- shadow -->
								<div class="thumb-shadow">
									<?php if(isset($clan->page->thumb) && $clan->page->thumb !== false): ?>
										<!-- post-thumb -->
										<div class="post-thumbnail">
											<img src="<?=CHtml::encode($clan->page->thumb)?>"  alt="" />
										</div>
										<!-- ENDS post-thumb -->
									<?php endif; ?>
			
									<div id="web-page-<?=(int)$clan->page->id?>">
										<?=CHtml::encode($clan->page->content)?>
									</div>
								</div>
								<!-- ENDS shadow -->
						
								<?php if(count($clan->page->pages) > 0): ?>
									<!-- meta -->
									<div class="meta">
										<h3>Sub Pages</h3>
										<p>
											<ul>
												<?php foreach($clan->page->pages as $page): ?>
													<?php $_can = AccessControl::GetUserAccess($page->aclObject); ?>
													<?php if($_can->read): ?>
														<li><strong><?=CHtml::link($page->title, array('//as/clan/page', 'name' => $clan->name, 'id' => $clan->id, 'page' => $page->title))?></strong> <?=CHtml::encode(substr($page->description, 0, 50))?></li>
													<?php endif; ?>
												<?php endforeach; ?>
											</ul>
										</p>
									</div>
									<!-- ENDS meta -->
								<?php endif; ?>
							</div>
							<!-- ENDS post -->
						</div>
					<?php else: ?>
						<p>
							You don't have the permission to view this page!
						</p>
					<?php endif; ?>
				<?php elseif($clan->page): ?>
					<p>
						This clan has an invalid page.
					</p>
				<?php else: ?>
					<p>
						This clan does not have a page (yet?)
					</p>
				<?php endif; ?>
			</div>
			
			<div>
				<?php if($clan->forum): ?>
					<?php $this->renderPartial('as.views.forum.topics', array('forum' => $clan->forum, 'p' => $p)); ?>
				<?php else: ?>
					<p>
						This clan does not have a forum (yet?)
					</p>
				<?php endif; ?>
			</div>
			
			<div>

				<table>
					<thead>
						<td>Title</td>
						<td>user</td>
					</thead>
					<tbody>
				<?php foreach($clan->clanMembers as $member): ?>
						<tr>
							<td><?=$member->rank->name?></td>
							<td><?=CHtml::link($member->user->username, array('//as/profile/view', 'name' => $member->user->username))?></td>
						</tr>
				<?php endforeach;?>
					</tbody>
				</table>
			</div>
			
			<div>
				<p>
					<?php if(count($clan->clanTags) < 1): ?>
						This clan does not have any clantags (yet?)
					<?php endif;?>
					<ul>
					<?php foreach($clan->clanTags as $tag): ?>
						<?php if($tag->status == ClanTag::STATUS_ACTIVE): ?>
							<li><?=$tag->tag?></li>
						<?php endif; ?>
					<?php endforeach;?>
					</ul>
				</p>
			</div>
		</div>
</div>
