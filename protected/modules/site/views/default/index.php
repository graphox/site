<?php /* @var $this DefaultController */ ?>

<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
	'heading'=>Yii::app()->site->title,
)); ?>
<?php eval('?>'.Yii::app()->site->description)?>

<?php $this->endWidget(); ?>

<span class="span-3">
<?php $this->widget('user.widgets.LoginWidget'); ?>
</span>