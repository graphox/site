<?php
/* @var $dataProvider CArrayDataProvider */

$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_page',
    'sortableAttributes'=>array(
        'created_date'=>'Created',
    ),
));