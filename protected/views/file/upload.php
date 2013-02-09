<?php
	
	$this->widget('ext.xupload.XUpload', array(
		'url' => Yii::app()->createUrl("file/uploadFile"),
        'model' => $model,
        'attribute' => 'file',
        'multiple' => true,

		'uploadView' => 'application.views.file._upload',
		'downloadView' => 'application.views.file._download',
		'formView' => 'application.views.file._form',
		'options' => array(
			'submit' => "js:function (e, data) {
				var inputs = data.context.find(':input');
				data.formData = inputs.serializeArray();
				return true;
			}"
		),
	));
         