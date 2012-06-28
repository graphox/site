<?php
$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs'=>array(
        'General user info' => $this->renderPartial('as.views.user.tabs.general', get_defined_vars(), true),
		'Names' => $this->renderPartial('as.views.user.tabs.names', get_defined_vars(), true),
		'profile' => $this->renderPartial('as.views.user.tabs.profile', get_defined_vars(), true),
		'groups' => $this->renderPartial('as.views.user.tabs.groups', get_defined_vars(), true),		
		'external accounts' =>
			($model->status != User::OAUTH_ACCOUNT)
				? $this->renderPartial('as.views.user.tabs.external_accounts', get_defined_vars(), true)
				: $this->renderPartial('as.views.user.tabs.oauth', get_defined_vars(), true),		
    ),
    'options' => array('cookie' => array('expire' => 600))
));

