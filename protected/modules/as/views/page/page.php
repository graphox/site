<?php
	$parser = new CMarkdownParser;
?>

<article>
	<div class="main">
		<?=$parser->safeTransform($content)?>
	</div>
	
	<div class="articleditor" >
		<h2>editor:</h2>
		<p class="profiledetails large">
			<details open="open">
				<summary><?=htmlentities($editor->username)?>&quot;s profile</summary>
				<dl>
					<?php if($editor->profile): ?>
						<li>
							<?php if($editor->profile->avatarImg): ?>
								<!-- <a href="..." TODO -->
									<img class="popup-img" data-src-large="..." src="<?=htmlentities($editor->profile->avatarImg->name)?>" alt="<?=htmlentities($editor->profile->avatarImg->name)?>" />
								<!-- </a> -->
							<?php endif; ?>
							<span class="key">hompage:</span>
							<span class="value">
								<a class="untrusted-link" href="<?=htmlentities($editor->profile->homepage)?>" >
									<?=htmlentities($editor->profile->homepage)?>
								</a>
							</span>
						</li>
					
						<li>
							<a class="popup-link" href="<?=$this->createUrl('profile/show', array('id' => $editor->profile->id))?>" >
								display profile
							</a>
						</li>
			
					<?php else: ?>
						<li>This user doesn't have a profile</li>
					<?php endif;?>				
				</dl>
			</details>				
		</p>
	</div>

	<div id="commentbox">
<?php foreach($comments as $comment): ?>
		<div id="comment-<?=$comment->id?>">
			<h3><?=htmlentities($comment->title)?></h3>
			<?=$parser->safeTransform($comment->content) ?>
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
		<span class="reaction vote">
			<a href="<?=$this->createUrl('page/action', array(
				'id' => $page_id,
				'reaction' => $comment->id,
				'action' => 'vote',
				'add' => 1
			))?>" class="add">+1: <?=$counts[1]?></a>
			<a href="<?=$this->createUrl('page/action', array(
				'id' => $page_id,
				'reaction' => $comment->id,
				'action' => 'vote',
				'sub' => 1
			))?>" class="sub">-1: <?=$counts[-1]?></a>
		<span>
<?php endforeach; ?>
	</div><!-- / commentbox -->
	
	
<?php if($can_comment): ?>
	<form class="comment-form">
		<input type="text" />
		<textarea></textarea>
		<input type="submit" />
	</form>
<?php endif; ?>

</article>
