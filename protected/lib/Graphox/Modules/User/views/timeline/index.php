<?php

$renderManager = Yii::app()->getContainer()->get('timeline.renderManager');
echo $renderManager->renderTimeline($updates);
http://pimple.sensiolabs.org/