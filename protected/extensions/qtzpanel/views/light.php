<?php
/**
 * Created by Roman Revin <xgismox@gmail.com>.
 * Date: 31.01.12 13:19
 */

/**
 * @var $this QtzPanelWidget
 * @var $panel QtzPanel
 * @var $assets string
 * @var $bar array
 * @var $items array of QtzPanelElement
 * @var $item QtzPanelElement
 */

$jsOptions = array(
	'id' => $panel->getId(),
	'initiallyOpen' => $this->initiallyOpen,
	'useCookie' => $this->useCookie,
	'panelHeight' => $this->height . 'px',
	'showBarLogs' => CPropertyValue::ensureBoolean($panel->getState('bar-logs')),
	'showBarDB' => CPropertyValue::ensureBoolean($panel->getState('bar-db')),
);

Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerCssFile($assets . '/style.css');

Yii::app()->getClientScript()->registerScript('QtzPanel init ' . $this->getId(), 'QPanelPool[' . CJavaScript::encode($panel->getId()) . '] = new QtzPanel(' . CJavaScript::jsonEncode($jsOptions) . ');');

if ($this->useCookie) {
	Yii::app()->getClientScript()->registerCoreScript('cookie');
}
?>

<div class="qtzpanelWrapper">
	<div class="panel" id="qtzpanel-<?=$panel->getId()?>" style="height: <?=$this->height?>px;">
		<?
		if (count($items) > 0) {
			echo '<ul class="items">';
			foreach ($items as $item) {
				$item->render(array('height' => $this->height));
			}
			echo '</ul>';
		}
		?>
	</div>

	<div class="bar" id="qtzpanelBar-<?=$panel->getId()?>">
		<ul>
			<?
			foreach ($bar as $b) {
				echo CHtml::tag('li', $b['htmlOptions'], $b['content']);
			}
			?>
			<li class="doshow" title="<?=Yii::t('QtzPanel.app', 'show')?>"><a
					href="#"><?=CHtml::link(CHtml::image($assets . '/icons/arrow_down.png', Yii::t('QtzPanel.app', 'show')))?></a>
			</li>
			<li class="dohide" title="<?=Yii::t('QtzPanel.app', 'hide')?>"><a
					href="#"><?=CHtml::link(CHtml::image($assets . '/icons/arrow_up.png', Yii::t('QtzPanel.app', 'hide')))?></a>
			</li>
		</ul>
	</div>

	<?
	if ($panel->getState('bar-logs')) {
		?>
		<div class="logs" id="qtzpanelLogs-<?=$panel->getId()?>">
			<?
			if (!$this->routeEnable()) {
				echo CHtml::tag('div', array('class' => 'error'), Yii::t('QtzPanel.app', 'In order to obtain this information, you need to add a logger route QtzPanelRoute.'));
			}
			?>
			<code></code>
		</div>
		<?
	}
	?>

	<?
	if ($panel->getState('bar-db')) {
		?>
		<div class="logsDB" id="qtzpanelLogsDB-<?=$panel->getId()?>">
			<?
			if (!$this->routeEnable()) {
				echo CHtml::tag('div', array('class' => 'error'), Yii::t('QtzPanel.app', 'In order to obtain this information, you need to add a logger route QtzPanelRoute.'));
			}
			?>
			<code></code>
		</div>
		<?
	}
	?>
</div>
