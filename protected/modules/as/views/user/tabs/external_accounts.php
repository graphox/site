<h3>Oauth accounts</h3>

<?php if($model->status == User::OAUTH_ACCOUNT): ?>
	<p>
		Oauth accounts are allowed only 1 external account.
	</p>
<?php else: ?>
	link more acocounts
							
<?php endif; ?>
