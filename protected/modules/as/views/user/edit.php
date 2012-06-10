<?php $this->beginClip('first'); ?>

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
				<strong>General user info</strong>
				<span style="float:right;margin-right:20px;">&gt;&gt;&gt;</span>
			</li>
			<li class="line-divider" data-i="2">
				<strong>Names</strong>
				<span style="float:right;margin-right:20px;">&gt;&gt;&gt;</span>
			</li>			
			<li class="line-divider" data-i="3">
				<strong>profile</strong>
				<span style="float:right;margin-right:20px;">&gt;&gt;&gt;</span>
			</li>
			<li class="line-divider" data-i="4">
				<strong>groups</strong>
				<span style="float:right;margin-right:20px;">&gt;&gt;&gt;</span>
			</li>
			
			<li class="line-divider" data-i="5">
				<strong>Excternal accounts</strong>
				<span style="float:right;margin-right:20px;">&gt;&gt;&gt;</span>
			</li>
			
			<?php if($model->status == User::OAUTH_ACCOUNT): ?>
				<li class="line-divider" data-i="6">
					<strong>Activate your account</strong>
					<span style="float:right;margin-right:20px;">&gt;&gt;&gt;</span>
				</li>				
			<?php endif; ?>
		</ul>

<?php $this->endClip();?>
<?php $this->beginClip('second'); ?>

	<div class="h-panes">
		<div>
			<?php $form = $this->beginWidget('CActiveForm', array(
				'id' => 'account-edit-form',
				'enableAjaxValidation' => true,
				'enableClientValidation' => true,
				'action' => isset($action) ? $action : array('//as/user/edit'),
				'clientOptions' => array(
					'validationUrl' => array('//as/user/edit'),
				),
				'htmlOptions' => array(
					'class' => 'admin-form',
				)
			)); ?>
			<h3>general user info</h3>
			<p>
				<fieldset>
					<?=$form->labelEx($model,'email'); ?>
					<?=$form->textField($model,'email')?>
					<?=$form->error($model,'email')?>
				</fieldset>

				<fieldset>
					<?=$form->labelEx($model,'username'); ?>
					<strong style="display:block">Please contact an admin to change this.</strong>
					<?=$form->textField($model,'username', array('disabled' => 'disabled'))?>
					<?=$form->error($model,'username')?>

				</fieldset>
				
				<fieldset>
					<?=$form->labelEx($model,'ingame_password'); ?>
					<?php if($model->status == User::OAUTH_ACCOUNT): ?>
						<strong>Please update your account to a full one to change this</strong>
						<?=$form->textField($model,'ingame_password', array('disabled' => 'disabled'))?>
					<?php else: ?>
						<?=$form->textField($model,'ingame_password')?>
					<?php endif; ?>
					<sub style="display:block">Due to the sauerbraten /setmaster hashing, this won't be stored encrypted in our database.</sub>
					<?=$form->error($model,'ingame_password')?>
				</fieldset>

				<fieldset>
					<?=$form->labelEx($model,'web_password'); ?>
					<?php if($model->status == User::OAUTH_ACCOUNT): ?>
						<strong>Please update your account to a full one to change this</strong>
						<?=$form->passwordField($model,'web_password', array('value' => '', 'disabled' => 'disabled'))?>
					<?php else: ?>
						<?=$form->passwordField($model,'web_password', array('value' => ''))?>
					<?php endif; ?>
					<?=$form->error($model,'ingame_password')?>
				</fieldset>

				<?php if($model->status != User::OAUTH_ACCOUNT): ?>
				<fieldset>
					<?=CHtml::label('retype password *', 'retype_password')?>
					<?=CHtml::passwordField('retype_password')?>
				</fieldset>
				<?php endif; ?>
			</p>

			<div style="margin-left:237px;">
				<?= CHtml::submitButton('save', array('id' => 'submit')); ?>
			</div>
			<?php $this->endWidget(); ?>
		</div>
		<div>
			<h3>Your ingame names</h3>
			<p>
				The names to protect on our gameserver, and wich can be used to login onto your account.
			</p>

			<ul>
				<li class="line-divider" ></li>
				<?php foreach($model->names as $name): ?>
					<li class="line-divider">
						<strong><?=$name->name?></strong>
						<?php if($name->status == Names::STATUS_CANCELLED): ?>
							<strong>Cancelled</strong>
						<?php elseif($name->status == Names::STATUS_PENDING): ?>
							<strong>pending</strong>
						<?php elseif($name->status == Names::STATUS_ACTIVE): ?>
							active
						<?php else: ?>
							Status: <?=$name->status?>
						<?php endif; ?>
						
						<div style="float:right">
							<?=CHtml::link('delete', array('//as/user/name', 'action' => 'delete', $name->name));?>
						</div>
					</li>
				<?php endforeach; ?>
				<li class="line-divider" class="admin-form">
					<input id="add_name" type="text" placeholder="Add a name" title="new name" />
					<div style="float:right" >
						<button style="margin:0;/*margin-top:8px*/" class="button-submit" onclick="var val = $(&quot;#add_name&quot;).val(); $.get(&quot;<?=$this->createUrl('//as/user/name', array('action' => 'add'))?>&quot;, {ajax: true, name: val}, function(succes) { alert(succes); $(&quot;#add_name&quot;).val(''); }); return false">Add</button>
					</div>
				</li>
			</ul>
		</div>
		<div>
			<h3>Profile information</h3>
			<p>
				<?php $profile_model = new Profile ?>
				<?php $form = $this->beginWidget('CActiveForm', array(
					'id' => 'profile-edit-form',
					'enableAjaxValidation' => true,
					'enableClientValidation' => true,
					'action' => isset($action) ? $action : array('//as/user/edit'),
					'clientOptions' => array(
						'validationUrl' => array('//as/user/edit'),
					),
					'htmlOptions' => array(
						'class' => 'admin-form',
					)
				)); ?>
					<fieldset>
						<?=$form->labelEx($profile_model,'homepage'); ?>
						<?=$form->textField($profile_model,'homepage')?>
						<?=$form->error($profile_model,'homepage')?>					
					</fieldset>
					
					select avatar [upload 1 |V]
					
					<fieldset>
						your profile page *
						<?=CHtml::textarea('profile_page'); ?>
					</fieldset>
				<?php $this->endWidget(); ?>
			</p>			
		</div>
		
		<div>
			<h3>Groups</h3>
			<ul>
			<?php foreach($model->aclGroups as $group): ?>
				<li><?=$group->name?></li>
			<?php endforeach; ?>
			<ul>
		</div>
		
		<div>
			<h3>Oauth accounts</h3>
			<?php if($model->status == User::OAUTH_ACCOUNT): ?>
				<p>
					Oauth accounts are allowed only 1 external account.
				</p>
			<?php else: ?>
				
								
			<?php endif; ?>
		</div>
		<?php if($model->status == User::OAUTH_ACCOUNT): ?>
		<div>
			<h3>Activate your account</h3>
			<p>
				Please update your account by clicking the button below,
				<strong>note that you will have to activate your email before you can use your account again.</strong>
			</p>
			<?php $form = $this->beginWidget('CActiveForm', array(
				'id' => 'account-edit-form',
				'enableAjaxValidation' => true,
				'enableClientValidation' => true,
				'action' => isset($action) ? $action : array('//as/user/oauth-activate'),
				'clientOptions' => array(
					'validationUrl' => array('//as/user/oauth-activate'),
				),
				'htmlOptions' => array(
					'class' => 'admin-form',
				)
			)); ?>

				<fieldset>
					<?=$form->labelEx($model, 'email'); ?>
					<?=$form->textField($model, 'email')?>
					<?=$form->error($model, 'email')?>					
				</fieldset>

				<fieldset>
					<?=$form->labelEx($model, 'web_password'); ?>
					<?=$form->textField($model, 'web_password')?>
					<?=$form->error($model, 'web_password')?>					
				</fieldset>
				
				<fieldset>
					<?=$form->labelEx($model, 'ingame_password'); ?>
					<?=$form->textField($model, 'ingame_password')?>
					<?=$form->error($model, 'ingame_password')?>
				</fieldset>
				
				<?=CHtml::submitButton('activate', array('class' => 'button-submit'))?>
				
			<?php $this->endWidget() ?>			
		</div>
		<?php endif; ?>
	</div>

<?php $this->endClip();?>


