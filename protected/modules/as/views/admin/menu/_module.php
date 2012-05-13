
<article class="module width_full">
	<header>
		<?php if(isset($_GET['id'])): ?>			<h3>Edit Menu Items#<?=(int)$_GET['id']?></h3>
		<?php else: ?>			<h3>Add Menu Items		<?php endif; ?>	</header>
	<div>
		<?php $this->renderPartial($_partial_, get_defined_vars()); ?>	</div>
</article>
