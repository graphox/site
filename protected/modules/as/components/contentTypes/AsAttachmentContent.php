<?php

class AsAttachmentContent extends AsContentType
{
	protected $indexView = 'as.views.contentType.attachment.index';
	protected $formView = 'as.views.contentType.attachment.form';
	
	public $uploadedFile;
	
	public function rules()
	{
		return array(
			array('name, content, language_id, can_comment, markup_id, published, widgets_enabled', 'required'),
			array('language_id, can_comment, markup_id, published, widgets_enabled', 'numerical', 'integerOnly'=>true),
					array('name', 'length', 'max'=>50),
	
			array('language_id', 'as.components.validators.AsInArrayValidator', 'data' => $this->languageOptions, 'arrayValues' => false),
			array('markup_id', 'as.components.validators.AsInArrayValidator', 'data' => $this->markupOptions, 'arrayValues' => false),

			array('name', 'unique'),
			array('name', 'cleanUrl'),
			
			array('uploadedFile', 'file', 'types'=>'jpg, gif, png, zip, tar, gz, pdf, ogz, dmo, ogg, cfg, lua, txt, avi'),
		);
	}
	
	public function render()
	{
		$attachmentData = $this->attachmentData;
		/*$attachmentData = AttachmentData::model()->findAllByAttributes(array(
			'content_id' => $this->id
		));*/
		
		Yii::app()->request->sendFile($attachmentData->name, $attachmentData->data, $attachmentData->type, false);
		die;
	}
	
	public function download()
	{
		$this->render();
	}
	
	/**
	 * @return array relational rules.
	 * Adds attachment relation
	 */
	public function relations()
	{
		$relations = parent::relations();

		$relations['attachmentData'] = array(self::HAS_ONE, 'AttachmentData', 'content_id');

		return $relations;
	}
	
	public function initAttachment($parent)
	{
		$this->type_id = ContentType::model()->findByAttributes(array(
			'name' => 'attachment'
		))->id;
		
		$this->parent_id = $parent->id;
		$this->published = 1;
		$this->can_comment = 0;
		$this->widgets_enabled = 0;
	}
}
