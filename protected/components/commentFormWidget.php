<?php

/**
 * CommentFormWidget provides a widget that gives users the abillity to comment on a parental entity by validating by ajax and posting it by ajax. clients with no support for javascript will make a regular request to the specified action.
 */
class commentFormWidget extends CWidget
{
	/**
	 * @var $parentEntity integer the entity id of the parent entity, set by the view that is requesting this widget.
	 */
	public $parentEntity;
	
	public function run()
	{
		echo 'Hey, this is a comment form, right? no? DIEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE!!!!!!!!!!!!!!!!!!!!!11 in that case.';
		echo '<br />@todo implement DA COMMENT FORM.';
	}
}