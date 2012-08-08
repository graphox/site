<?php
#static tabs:
Yii::app()->clientScript->registerScriptFile(
    Yii::app()->assetManager->publish(
        Yii::getPathOfAlias('application.assets').'/hashchange.js'
    ),
    CClientScript::POS_END
);

Yii::app()->clientScript->registerScript('staticTab', <<<JS
    // Function to activate the tab
    function activateTab() {
        var activeTab = $('[href=' + window.location.hash.replace('/', '') + ']');
        activeTab && activeTab.tab('show');
    }

    // Trigger when the page loads
    activateTab();

    // Trigger when the hash changes (forward / back)
    $(window).hashchange(function(e) {
        activateTab();
    });

    // Change hash when a tab changes
    $('a[data-toggle="tab"], a[data-toggle="pill"]').on('shown', function () {
        window.location.hash = '/' + $(this).attr('href').replace('#', '');
    }); 

JS
, CClientScript::POS_READY);

?>

<?php if($model->isNewRecord): ?>
	<div class="alert alert-block alert-info fade in">
		<a class="close" data-dismiss="alert">×</a>
		You can add email addressess after having created the user.
	</div>
<?php else: ?>
	<?php
		$email_items = array();
		
		foreach($model->emails as $email)
			$email_items[] = array('id' => 'email-'.$email->id, 'label' => $email->email, 'content' => $this->renderPartial('_email', array('email' => $email), true));

		$email_items[] = array(
			'id' => 'add-email', 
			'label' => 'add address',
			'content' => $this->renderPartial('_form_email', array('isNew' => $model->isNewRecord, 'email_model' => $email_model), true),
		);
	?>
<?php endif; ?>

<div class="form">

	<fieldset>
		<?php $this->widget('bootstrap.widgets.BootTabbable', array(
			'id' => 'user-tabs',
			'type'=>'tabs', // 'tabs' or 'pills'
			'placement' => 'left',
			'tabs'=>array(
				array('id' => 'account', 'label'=>'Account', 'content'=>$this->renderPartial('_form_general', get_defined_vars(), true), 'active'=>true),
				!$model->isNewRecord ? array('id' => 'status', 'label'=>'Status', 'content'=>$this->renderPartial('_form_status', get_defined_vars(), true)) : array( 'visible' => false),
				!$model->isNewRecord ? array('id' => 'data', 'label'=>'Data', 'content'=>$this->renderPartial('_form_details', get_defined_vars(), true)) : array( 'visible' => false),
			    !$model->isNewrecord ? array('id' => 'email', 'label'=>'email ('.(int)count($model->emails).')', 'items'=>$email_items) : array( 'visible' => false),
			),
		)); ?>
	</fieldset>

</div><!-- form -->
