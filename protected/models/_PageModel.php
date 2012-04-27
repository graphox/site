<?php

class PageModel extends CActiveRecord
{
	public $title = '';
	public $description = '';
	public $starter_id = 0;
	public $hidden = 0;
	public $creation_date = '';
	public $lastmessage = '';
	public $category_id = 0;
	
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
 
    public function tableName()
    {
        return 'web_pages';
    }

}
