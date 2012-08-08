Please activate your user account by clicking this link or pasting it in your browser:
<a href="<?=$this->createAbsoluteUrl(array('/user/activate', 'code' => $model->primaryEmail->key))?>">
	<?=$this->createAbsoluteUrl(array('/user/activate', 'code' => $model->primaryEmail->key))?>
</a>
