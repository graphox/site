<?php

/**
 * Relation between user and a blog
 *
 * @author killme
 */
class _BLOG_OWNER_ extends ENeo4jRelationship
{
	/**
	 * @return _BLOG_OWNER_ returns class
	 */
	public static function model($className = __CLASS__) {
        return parent::model($className);
    }

	/**
	 * Initializes the time
	 */
	public function init()
	{
		parent::init();
		$this->date = time();
	}
	
	/**
	 * @return array list or rules
	 */
	public function rules()
	{
		return array(
			array('rules', 'type', 'type' => 'array'),
			array('rules', 'validateRules'),
		);
	}
	
	public function validateRules()
	{
		if(!$this->hasErrors())
		{
			$actions = Blog::model()->getAccessActions();
			foreach($this->rules as $rule)
			{
				$rule = trim($rule);
				foreach($actions as $action => $_)
				{
					if($rule == $action || ($rule[0] == '-' && ltrim($rule, "- \t") === $action))
						continue 2;
				}
				$this->addError('rules', 'Invalid rule: '.(string)$rule);
			}
		}
	}
	
	public function properties()
    {
        return CMap::mergeArray(parent::properties(), array(
			'date'	=>	array('type'=>'int'),
			'rules' =>	array('type'=>'string[]'),
        ));
    }
}

?>
