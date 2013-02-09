<?php
	$formatBytes = function($size)
	{
		$base = substr($size, 0, -1);
		$mod = substr($size, -1);
		
		switch(strtolower($mod))
		{
			case 'g':
				$base *= 1024;
			case 'm':
				$base *= 1024;
			case 'k':
				$base *= 1024;
				break;
		}
		
		return $base;
	};

	$phpLog = ini_get('error_log');
	if (!$phpLog)
		$phpLog = Yii::t('admin.info', 'Using web server\'s error log.');

	$maxPostSize = ini_get('post_max_size');
	$maxUpSize = ini_get('upload_max_filesize');

	if ($formatBytes($maxUpSize) > $formatBytes($maxPostSize))
	{
		Yii::app()->user->setFlash('warning', Yii::t('admin.info', 'Post size should be larger than upload size!'));
	}
	
	$memUsage = memory_get_peak_usage();
	$maxMemUsage = $formatBytes(ini_get('memory_limit'));
?>

<h2>Server info</h2>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => array(
			'version' =>  $_SERVER['SERVER_SOFTWARE'],
			'log' => getenv('APACHE_LOG_DIR')
		),
		'attributes' => array(
			array('label' => Yii::t('admin.info', 'Server version'), 'name' => 'version'),
			array('label' => Yii::t('admin.info', 'Logging directory'), 'name' => 'log')
		)
	)); ?>

<h2>PHP info</h2>

	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => array(
			'version' => phpversion(),
			'ini' => php_ini_loaded_file(),
			'memUsage' => $memUsage,
			'memLimit' => $maxMemUsage,
			'logPath' => $phpLog,
			'maxPostSize' => ini_get('post_max_size'),
			'maxUploadSize' => ini_get('upload_max_filesize'),
			
		),
		'attributes' => array(
			array('label' => Yii::t('admin.info', 'PHP version'), 'name' => 'version'),
			array('label' => Yii::t('admin.info', 'PHP ini file'), 'name' => 'ini'),
			array('label' => Yii::t('admin.info', 'PHP Memory usage'), 'name' => 'memUsage'),
			array('label' => Yii::t('admin.info', 'PHP Memory limit'), 'name' => 'memLimit'),
			array('label' => Yii::t('admin.info', 'PHP logging directory'), 'name' => 'logPath'),
			array('label' => Yii::t('admin.info', 'PHP Maximum post size'), 'name' => 'maxPostSize'),
			array('label' => Yii::t('admin.info', 'PHP Maximum upload size'), 'name' => 'maxUploadSize')
		)
	)); ?>

<h2>Memory usage.</h2>
<?php $this->widget('bootstrap.widgets.TbProgress', array(
	'stacked'=>array(
		array('type'=>'warning', 'percent'=> $percUsed = (($memUsage/$maxMemUsage) * 100), 'htmlOptions' => array( 'title' => 'Used: '.$percUsed.'%')),
		array('type'=>'success', 'percent'=> $percFree = (100-(($memUsage/$maxMemUsage) * 100)), 'htmlOptions' => array( 'title' => 'Free: '. $percFree.'%')),
	)));
?>

<?php /*<details>
	<summary>More info</summary>
	<?php var_dump($info); ?>
</details>
*/