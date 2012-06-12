<?php 
	#Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/style/highlight.css', 'screen' );
	/*
?>

<?php if( Yii::app()->user->id ): ?>

<div class='floatright'>
<?php if( $subscribed ): ?>
		<a href="<?php echo $this->createUrl('unsubscribe', array('id' => $model->id ) ); ?>" class="linkcomment" title='<?php echo Yii::t('forum', 'Un-Subscribe for topic updates.'); ?>'><strong><?php echo Yii::t('forum', 'Unsubscribe'); ?></strong></a>
<?php else: ?>
		<a href="<?php echo $this->createUrl('subscribe', array('id' => $model->id ) ); ?>" class="linkcomment" title='<?php echo Yii::t('forum', 'Subscribe for topic updates.'); ?>'><strong><?php echo Yii::t('forum', 'Subscribe'); ?></strong></a>
<?php endif; ?>	
</div>
<div class='floatleft'><!-- None --></div>
<div style='clear:both;'></div>
<br style='clear:both;' />

<?php endif; ?>

<?php $this->widget('ext.VGGravatarWidget', array( 'size' => 50, 'email'=>$model->user ? $model->user->email : '','htmlOptions'=>array('class'=>'imgavatar','alt'=>'avatar'))); ?>
*/ ?>
<div class='forumtopicpost'><?php echo $markdown->safeTransform($model->description); ?></div>

<div class="clear"></div><br />
<h3 id="titlecomment"><?php echo Yii::t('forum', 'Posts'); ?> (<?php echo $count; ?>)</h3>
<ul id="listcomment">
	<?php if( count( $posts ) ): ?>
		<?php foreach($posts as $post): ?>
			<li id="post<?php echo $post->id; ?>">
				<span class='commentspan'>
					<?php echo CHtml::link( '#' . $post->id, array('/as/forum/viewtopic/', 'topic' => $model->id, '#' => 'post' . $post->id, 'page' => $pages->getCurrentPage())); ?>
				</span>
				<?php /*$this->widget('ext.VGGravatarWidget', array( 'size' => 50, 'email'=>$post->user ? $post->user->email : '','htmlOptions'=>array('class'=>'imgavatar','alt'=>'avatar')));*/ ?>
				<h4><?php echo $post->user ? $post->user->username : Yii::t('global', 'Unknown'); ?></h4>
				<span class="datecomment"><?php echo Yii::app()->dateFormatter->formatDateTime($post->date_added, 'short', 'short'); ?></span>
				<div class="clear"></div>
				<p><?php echo $markdown->safeTransform($post->content); ?></p>
			    <?php if($can->update): ?>
					<?php /* echo CHtml::link( CHtml::image( Yii::app()->themeManager->baseUrl . '/images/'. ('cross_circle') . '.png' ), array('forum/togglepost', 'id' => $post->id), array( 'class' => 'tooltip', 'title' => Yii::t('forum', 'Toggle post status!') ) );*/ ?>
				<?php endif; ?>
			</li>
			<hr />
		<?php endforeach; ?>	
	<?php else: ?>	
		<li><?php echo Yii::t('forum', 'No posted posted yet. Be the first!'); ?></li>
	<?php endif; ?>	
</ul>
<?php $this->widget('CLinkPager', array('pages'=>$pages)); ?>
<?php if($can->write == 1): ?>
	
<?php echo CHtml::form('', 'post', array('id'=>'frmcomment')); ?>
<?php echo CHtml::hiddenField('lastpage', $pages->pageCount); ?>
	<div>
		<?php echo CHtml::label(Yii::t('forum', 'title'), ''); ?>
		<?php echo CHtml::activeTextField($newPost, 'title'); ?>
		<?php echo CHtml::error($newPost, 'title'); ?>
		
		<?php echo CHtml::label(Yii::t('forum', 'Post'), ''); ?>
		<?php echo CHtml::activeTextArea($newPost, 'content'); ?>
		<?php echo CHtml::error($newPost, 'comment'); ?>
		<?php echo CHtml::submitButton(Yii::t('forum', 'Post Reply'), array( 'class' => 'submitcomment' )); ?>
	</div>
<?php echo CHtml::endForm(); ?>

<?php else: ?>
	<?php if(Yii::app()->user->isGuest): ?>
		<div><?php echo Yii::t('global', 'You must be logged in to post.'); ?></div>
	<?php else: ?>
		<div><?php echo Yii::t('global', 'You don\'t have the permission to post.'); ?></div>
	<?php endif; ?>
<?php endif; ?>
