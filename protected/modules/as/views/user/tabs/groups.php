<h3>Groups</h3>

<?php
	$groups = $model->aclGroups;
	$groups[] = (object)array('name' => 'world');
	$groups[] = (object)array('name' => 'user');
	$model->aclGroups = $groups;
?>

<?php /* TODO: displaygroup */ ?>
<?php if(!$model->aclGroups || count($model->aclGroups) < 1): ?>
	<div class="flash-notice">
		You haven't joined any groups.
	</div>
<?php else: ?>
	<ul>
		<?php foreach($model->aclGroups as $group): ?>
			<li><?=$group->name?></li>
		<?php endforeach; ?>
	<ul>
<?php endif;?>
