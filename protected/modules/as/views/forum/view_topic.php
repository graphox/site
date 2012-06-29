<?php
	$model = new ForumMessage;
	array_unshift($models, $topic);
	$this->renderPartial('as.views.comments', array_merge(array('action' => array('//as/forum/reply', 'topic' => $topic->id), 'comment_header' => false, 'no_show_reply' => true, 'no_show_edit' => true, ), get_defined_vars()));?>

