<?php

class ContentMakeup
{
	static $SUPPORTED = array('markdown', 'safe_html', 'html', 'php', 'plain');
	
	/**
	 *	Parses the content to their markedu up variant
	 *	NOTE: this does not check for any access
	 *	@return: the marked up string
	 */
	static public function parse($content, $makeup)
	{
		switch($makeup)
		{
			case 'markdown':
				$m = new CMarkdownParser;
				$content = $m->safeTransform($content);
				break;
			
			case 'safe_html':
				$p = new CHtmlPurifier();
				$p->options = Yii::app()->params['purifier.settings'];
				
				$content = $p->purify($content);
				break;
			
			case 'bb':
				throw new Exception('There is no support for bb code yet!');
				break;
			
			case 'html':
				break;
			
			case 'php':
				ob_start();
				ob_implicit_flush(false);
				eval('?>'.$content.'<?');
				$content = ob_get_clean();
				break;

			default:
			case 'plain':
				$content = CHtml::encode($content);
				break;
		
		}
		
		return $content;
	}
	
	/**
	 *	Get all markup types that the user is allowed to use.
	 *	@return: array with types
	 */
	 
	static public function userAllowed()
	{
		$allowed = array();
		foreach(self::$SUPPORTED as $type)
		{
			#we should not worry about this
			if($type == 'plain')
				continue;
				
			$acl_object = null;
			$acl_object = AccessControl::GetObjectByName('markup.'.$type);
		
			if(!$acl_object)
			{
				$parent = AccessControl::GetObjectByName('markup');
				
				if(!$parent)
					$parent = AccessControl::AddObject('markup');		
			
				$acl_object = AccessControl::AddObject('markup.'.$type, $parent);		
			}
			
			if(AccessControl::getUserAccess($acl_object)->read == 1)
				$allowed[$type] = $type;
		}
		
		$allowed['plain'] = 'plain';
	}
}
