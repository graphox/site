<?php
$this->layout = 'column2';
$this->breadcrumbs=array(
	'User',
);

$this->menu = array(
	array('label' => 'logout', 'url' => array('logout')),
);
?>

<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file:<br />
	<tt><?php echo __FILE__; ?></tt>.
</p>
