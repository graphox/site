<?php
//\Yii::app()->getContainer()->get('widget.timelineAction')->set($title, $body)->render();
?>
<div class="timeline-action">
    <?php if (isset($title)): ?>
        <header>
            <h3><?= $title ?></h3>
        </header>
    <?php endif; ?>

    <?php
    if (isset($user) && $user instanceof \Graphox\Modules\User\User):
        ?>
        <aside style="float:left;width:10%">
            <h4><?= \CHtml::encode($user->getUsername()) ?></h4>
            <p>
                IMG
            </p>
        </aside>
        <article style="float:right;width:90%">
        <?php else: ?>
            <article>
            <?php endif; ?>
            <?= $body ?>
        </article>
        <div style="clear:both"></div>
</div>
