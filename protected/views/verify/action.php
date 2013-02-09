<?php /** @var TbActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'horizontal',
)); ?>

<h1><?=$it->shortDescr?></h1>
<p><?=$it->longDescr?></p>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'yes i am!')); ?>
</div>
 
<?php $this->endWidget(); ?>