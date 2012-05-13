
<article class="module width_full">
	<header>
		<?php if(isset($_GET['id'])): ?>			<h3>Edit Acl Privileges#<?=(int)$_GET['id']?></h3>
		<?php else: ?>			<h3>Add Acl Privileges		<?php endif; ?>	</header>
	<div>
		<?php $this->renderPartial($_partial_, get_defined_vars()); ?>	</div>
</article>
