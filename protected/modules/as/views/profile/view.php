<?php
!isset($p) && $p = new CHtmlPurifier();
$p->options = Yii::app()->params['purifier.settings'];
?>
<div id="content">
	<!-- title -->
	<div id="page-title">
		<h1 class="title"><?=CHtml::encode($user->username)?>&#39;s profile</h1>
	</div>
	<!-- ENDS title -->

	<div id="posts" class="single">	

		<p>
			<ul>
				<li><strong>Username</strong> <?=CHtml::encode($user->username)?></li>
				<li>
					<strong>Ingame Names</strong>
					<?php if(count($user->names) > 0): ?>
					<ul>
						<?php foreach($user->names as $name): ?>
							<?php if($name->status == Names::active): ?>
								<li><?=CHtml::encode($name->name)?></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
					<?php else: ?>
						no names yet
					<?php endif; ?>
				</li>
				<?php if($user->profile): ?>
					<li>
						<strong>homepage</strong> <?=CHtml::link($user->profile->homepage, $user->profile->homepage)?>
					</li>
				<?php endif; ?>
			</ul>
		</p>

<?php if($user->profile && $user->profile->page): ?>
	<?php if($user->profile->page->module == 'profile'): ?>
		<h2>Profile page</h2>
		<hr />
		<h3><?=CHtml::encode($user->profile->page->title);?></h3>
		<span class="subtitle"><?=CHtml::encode($user->profile->page->description);?></span>
		
		<?php $this->renderPartial('as.views.page.page', array('no_header' => true, 'show_meta' => false, 'page' => $user->profile->page, 'can' => AccessControl::getUserAccess($user->profile->page->aclObject))) ?>
	<?php else: ?>
		<p>
			Invalid page.
		</p>
	</div>
</div>
	<?php endif; ?>
<?php endif; ?>
