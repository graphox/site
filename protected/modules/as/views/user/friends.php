<?php
	$pending = Friends::model()->findAllbyAttributes(array('friend_id' => Yii::app()->user->id, 'status' => Friends::STATUS_PENDING));
	$this->beginClip('first'); ?>
<script type="text/javascript">
jQuery(function($) {
	//vertical tabs
	$('.h-panes > div').css('display', 'none');
	$('.h-panes > div:first').css('display', 'block');
	
	$('ul.h-tabs li').click(function()	{
		$('.h-panes > div').css('display', 'none');
		$('.h-panes > div:nth-child(' + $(this).attr('data-i') + ')').fadeIn('slow');
	});
});

</script>
		<ul class="h-tabs">
			<li class="line-divider" ></li>
			<li class="line-divider" data-i="1">
				<strong>add friend</strong>
				<span style="float:right;margin-right:20px;">&gt;&gt;&gt;</span>
			</li>
			<?php $i = 1; ?>
			
			<?php
				foreach($model as $friend):
				$i++;
			?>
				<li class="line-divider" data-i="<?=$i?>" >
					<strong><?=CHtml::encode($friend->with('friend', 'profile')->friend->username)?></strong>
					<span class="status online">online</span>
					
					<?php switch($friend->status): 
						case Friends::STATUS_ACTIVE:?>
							<span>active</span>
						<?php break;
						
						case Friends::STATUS_PENDING: ?>
							<span><strong>pending</strong></span>
						<?php break;
						
						case Friends::STATUS_IGNORE: ?>
							<span>blocked</span>
						<?php break;
						
					endswitch; ?>
					
					<span style="float:right;margin-right:20px;">&gt;&gt;&gt;</span>
				</li>
			<?php endforeach; ?>
			<?php if(count($pending) > 0): ?>
				<li class="line-divider" data-i="<?=++$i?>" >
					friend requests <strong>(<?=count($pending)?>)</strong>
				</li>
			<?php endif; ?>
			
		</ul>
<?php 
	$this->endClip(); 
	$this->beginClip('second'); ?>
	<h3 class="line-divider" style="display:none">More info</h3>
	<div class="h-panes">
		<div>
			<h4>Send friend request</h4>
			<?php if(!empty($error)): ?>
				<hr />
				<h5>Could not save</h5>
				<p>
					<?=$error?>
				</p>
			<?php endif; ?>
			<hr />			
				<?=CHtml::form('', 'POST', array('id' => 'friend-form', 'class' => 'admin-form'))?>
				<fieldset>
					<div>
						<?=CHtml::label('username', 'username'); ?>
						<?=CHtml::textField('friend-form[username]', isset($_POST['friend-form']) && isset($_POST['friend-form']['username']) ? $_POST['friend-form']['username'] : '' , array('class' => 'form-poshytip', 'title' => 'The username of the friend.')); ?>
						</div>
				</fieldset>

				<hr />
				<div>
					<noscript>
						<?=CHtml::submitButton()?>
					</noscript>
					<div style="margin:0 5px;float:right">
						<img src="<?=Yii::app()->theme->baseUrl?>/img/mono-icons/userplus32.png" alt="accept friendship" title="accept friendship" onclick="$(&quot;#friend-form&quot;).submit()"/>
					</div>
				</div>	
				<?=CHtml::closeTag('post')?> 	
		</div>
		
		<?php foreach($model as $friend): ?>
			<div>
				<h4>about <?=$friend->friend->username?></h4>
				<hr />
				<?php
					$friend->friend->profile && $friend->friend->profile->page && $friend->friend->profile->page = false;
					$this->renderPartial('as.views.user.profile', array('user' => $friend->friend));
				?>
				<hr />
				<div>
					<div style="margin:0 5px;float:right">
						<img src="<?=Yii::app()->theme->baseUrl?>/img/mono-icons/user32.png" alt="view profile" title="view profile" />
					</div>

					<div style="margin:0 5px;float:right">
						<a href="<?=$this->createUrl('//as/user/friends', array('friend' => $friend->friend->username, 'action' => 'remove'))?>" >
							<img src="<?=Yii::app()->theme->baseUrl?>/img/mono-icons/userminus32.png" alt="remove from friends list" title="remove from friends list" />
						</a>
					</div>
					<div style="margin:0 5px;float:right">
						<a href="<?=$this->createUrl('//as/user/friends', array('friend' => $friend->friend->username, 'action' => 'block'))?>" >
							<img src="<?=Yii::app()->theme->baseUrl?>/img/mono-icons/userblock32.png" alt="block user" title="block user" />
						</a>
					</div>
					<div style="margin:0 5px;float:right">
						<a href="<?=$this->createUrl('//as/user/friends', array('friend' => $friend->friend->username, 'action' => 'accept'))?>" >
							<img src="<?=Yii::app()->theme->baseUrl?>/img/mono-icons/userplus32.png" alt="accept friendship" title="accept friendship" />
						</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		
		<div>
			<h4>Friend requests</h4>
			<ul>
				<li class="line-divider" ></li>
			<?php foreach($pending as $request): ?>
				<li class="line-divider" >
					<strong><?=$request->friend->username?></strong>
					<div style="margin:0 5px;float:right">
						<a href="<?=$this->createUrl('//as/user/profile', array('name' => $request->owner->username))?>" >
							<img src="<?=Yii::app()->theme->baseUrl?>/img/mono-icons/user32.png" alt="view profile" title="view profile" />
						</a>
					</div>
					<div style="margin:0 5px;float:right">
						<a href="<?=$this->createUrl('//as/user/friends', array('friend' => $request->owner->username, 'action' => 'block'))?>" >
							<img src="<?=Yii::app()->theme->baseUrl?>/img/mono-icons/userblock32.png" alt="ignore" title="ignore user" />
						</a>
					</div>
					<div style="margin:0 5px;float:right">
						<a href="<?=$this->createUrl('//as/user/friends', array('friend' => $request->owner->username, 'action' => 'remove'))?>" >
							<img src="<?=Yii::app()->theme->baseUrl?>/img/mono-icons/userminus32.png" alt="deny" title="deny" />
						</a>
					</div>
					<div style="margin:0 5px;float:right">
						<a href="<?=$this->createUrl('//as/user/friends', array('friend' => $request->owner->username, 'action' => 'accept'))?>" >
							<img src="<?=Yii::app()->theme->baseUrl?>/img/mono-icons/userplus32.png" alt="accept friendship" title="accept friendship" />
						</a>					
					</div>
				</li>
			<?php endforeach; ?>
		</div>
	</div>
<?php $this->endClip(); ?>
