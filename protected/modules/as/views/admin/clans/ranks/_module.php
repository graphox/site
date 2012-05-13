
<article class="module width_full">
	<header>
		<?php if(isset($_GET['id'])): ?>			<h3>Edit Clan Ranks#<?=(int)$_GET['id']?></h3>
		<?php else: ?>			<h3>Add Clan Ranks		<?php endif; ?>	</header>
	<div>
		<?php $this->renderPartial($_partial_, get_defined_vars()); ?>	</div>
</article>
