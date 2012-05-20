<?php $this->beginContent('//layouts/page'); ?>
	<div id="posts">
		<?=$content?>
	</div>

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
<?php $this->endContent(); ?>
