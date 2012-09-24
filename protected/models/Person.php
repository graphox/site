<?php
class Person extends ENeo4jNode
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
            'username'	=>	array('type'=>'string'),
            'password'	=>	array('type'=>'integer'),
            'email'		=>	array('type'=>'string[]'),
			'ingameName'		=>	array('type'=>'string[]'),
			'lastLoggedin'	=> array('type' => 'integer'),
			'lastLoggedinHost' => array('type' => 'string')
        ));
    }

    public function rules()
    {
        return array(
            array('username, password, email, ingameName', 'safe'),
            array('name, password, email','required')
        );
    }

    public function traversals()
    {
        return array(
            'friends'=>array(self::HAS_MANY,self::NODE,'out("_FRIEND_")'),
            'fof'=>array(self::HAS_MANY,self::NODE,'out("_FRIEND_").out("_FRIEND_")'),
        );
    }
}