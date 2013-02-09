<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'links'=>array('Forum')
));

if(!Yii::app()->user->isGuest && Yii::app()->user->isAdmin)
{
    echo 'Admin: '. CHtml::link('New forum', array('/forum/forum/create')) .'<br />';
}

foreach($categories as $category)
{
    $this->renderpartial('_subforums', array(
        'forum'=>$category,
        'subforums'=>new CArrayDataProvider(Forum::model()->findAll(), array(
            'pagination'=>false,
        )),
    ));
}