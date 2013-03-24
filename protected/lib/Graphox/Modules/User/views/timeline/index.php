<?php

$renderManager = Yii::app()->getContainer()->get('timeline.renderManager');
echo $renderManager->renderTimeline($updates);


$this->renderPartial('form',
        array(
    'form' => $formBuilderModel = new \Graphox\Modules\User\Forms\ShareForm));
