<?php

/**
 * Entity for files
 *
 * @author killme
 * @todo make some traits and interfaces.
 * 
 */
class File extends Neo4jNode
{

	public $file;

	public function init()
	{
		parent::init();
		$this->createdDate = time();
	}
	
	/**
	 * @return File returns class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function rules()
	{
		return array(
			array('file', 'file', 'on' => 'upload'),
			
			array('routeName', 'required'),
			//array('descriptionSource', 'required'),
			//array('routeName, publish', 'safe'),
			//array('publish', 'in', 'range' => array(0, 1)),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return CMap::mergeArray(parent::attributeLabels(), array(
			'descriptionSource' => Yii::t('models.file', 'Description'),
			'file' => 'Upload files',
		));
	}

	public function getReadableFileSize($retstring = null)
	{
		// adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
		$sizes = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

		if ($retstring === null)
		{
			$retstring = '%01.2f %s';
		}

		$lastsizestring = end($sizes);

		foreach ($sizes as $sizestring)
		{
			if ($this->size < 1024)
			{
				break;
			}
			if ($sizestring != $lastsizestring)
			{
				$this->size /= 1024;
			}
		}
		if ($sizestring == $sizes[0])
		{
			$retstring = '%01d %s';
		} // Bytes aren't normally fractional
		return sprintf($retstring, $this->size, $sizestring);
	}

	/**
	 * @todo change tags into a relation to a tag.
	 * @return array, the properties of the node.
	 */
	public function properties()
	{
		return CMap::mergeArray(parent::properties(), array(
			//Origional name of the file
			'name' => array('type' => 'string'),
			
			//Name of the file on the filesystem
			'fsName' => array('type' => 'string'),
			
			//'description' => array('type' => 'string'),
			//'descriptionSource' => array('type' => 'string'),
			'size' => array('type' => 'integer'),
			//'tags' => array('type' => 'string[]'),
			//'publish' => array('type' => 'boolean'),
			'routeName' => array('type' => 'string'),
			'mimeType' => array('type' => 'string'),
			
			'createdDate'	=>	array('type' => 'integer'),
		));
	}

	/**
	 * Returns a formated string version of the tags array.
	 * @return string formated string of tags
	 */
	public function getTagString()
	{
		return implode(', ', $this->tags);
	}

	public function afterValidate()
	{
		parent::afterValidate();

		//$this->description = Yii::app()->contentMarkup->safeTransform($this->descriptionSource);
		$this->routeName = preg_replace('/[^a-zA-Z0-9.-]/', '', $this->routeName);
	}

	public function traversals()
	{
		return array(
			'attachment' => array(self::HAS_MANY, self::NODE, 'in("_ATTACHMENT_")'),
			'creator' => array(self::HAS_MANY, self::NODE, 'in("_CREATOR_")'),
		);
	}
	
	public function setCreator($user)
	{
		$r = new _CREATOR_();
		$r->startNode = $user;
		$r->endNode = $this;
		$r->save(false);
	}
	
	public function isImage()
	{
		return strstr($this->mimeType, 'image');
	}
	
	public function getThumbName()
	{
		return $this->fsName.'.thumb';
	}
	
	public function getThumbUrl()
	{
		return Yii::app()->controller->createAbsoluteUrl('/file/raw', array(
			'name' => $this->routeName,
			'thumb' => 'thumb'
		));
	}
	
	public function getUrl()
	{
		return Yii::app()->controller->createAbsoluteUrl('/file/raw', array(
			'name' => $this->routeName,
		));
	}
}
