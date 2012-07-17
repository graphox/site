<h1>Attachments</h1>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$models,
    'itemView'=>'as.views.contentType.attachment._item',
    'ajaxUpdate' => true,
    'sortableAttributes' => array(
        'title',
        'create_time'=>'Post Time',
    ),
)); ?>
