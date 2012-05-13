
<article class="module width_full">
	<header>
		<?php if(isset($_GET['id'])): ?>			<h3>Edit Acl Objects#<?=(int)$_GET['id']?></h3>
		<?php else: ?>			<h3>Add Acl Objects		<?php endif; ?>	</header>
	<div>
		<?php $this->renderPartial($_partial_, get_defined_vars()); ?>	</div>
</article>
