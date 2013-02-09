<article>
	<header>
		<h1><?=$data->name?></h1>
		
		<?php #TODO: add publish widget ?>
		<?php if($data->published === false): ?>
			<div class="flash-notice">
				You have not yet made this comment public, noone else can see.
			</div>
		<?php endif; ?>
	</header>
	<div>
		<?=$data->html?>
	</div>
</article>
