<?php
/**
 * Created by Roman Revin <xgismox@gmail.com>.
 * Date: 02.02.12 16:58
 */

class QtzPanelRoute extends CLogRoute
{
	private $_toolbarWidget;
	private $_startTime;
	private $_endTime;

	private $_textHighlighter = null;

	protected function processLogs($logs)
	{
		if(Yii::app()->getRequest()->getIsAjaxRequest()){
			return false;
		}

		$DBLogs = $this->getCallstack($logs);
		$mem = round(Yii::getLogger()->getMemoryUsage() / 1024 / 1024, 3);
		$time = round(Yii::getLogger()->getExecutionTime(), 3);

		$queryTime = 0;
		foreach ($DBLogs as $l) {
			$queryTime += $l[1];
		}
		$DB = Yii::t(
			'QtzPanel.app',
			'{driver}: {n} query in {s} s.|{driver}: {n} queries in {s} s.',
			array(count($DBLogs), '{driver}' => Yii::app()->db->getDriverName(), '{s}' => vsprintf('%0.4F', $queryTime))
		);

		$flogs = $logs;
		foreach($flogs as &$l){
			$t = explode('.', $l[3]);
			$l[3] = Yii::app()->getDateFormatter()->format('h:m:s', $l[3]) . ' ' . $t[1];
		}

		$js = 'QtzPanelHelper.setLogs(' . CJavaScript::jsonEncode($flogs) . ');' .
				'QtzPanelHelper.setDBStat(' . CJavaScript::jsonEncode($DB) . ');' .
				'QtzPanelHelper.setDBLogs(' . CJavaScript::jsonEncode($DBLogs) . ');' .
				'QtzPanelHelper.setMemory(' . CJavaScript::jsonEncode($mem) . ');' .
				'QtzPanelHelper.setTime(' . CJavaScript::jsonEncode($time) . ');';

		echo CHtml::script($js);
	}

	protected function getCallstack($logs)
	{
		$stack = array();
		$results = array();
		$n = 0;
		foreach ($logs as $log)
		{
			if ($log[1] !== CLogger::LEVEL_PROFILE)
				continue;
			$message = $log[0];
			if (!strncasecmp($message, 'begin:', 6)) {
				$log[0] = substr($message, 6);
				$log[4] = $n;
				$stack[] = $log;
				$n++;
			}
			else if (!strncasecmp($message, 'end:', 4)) {
				$token = substr($message, 4);
				if (($last = array_pop($stack)) !== null && $last[0] === $token) {
					$delta = $log[3] - $last[3];
					$results[$last[4]] = array($token, $delta, count($stack));
				}
				else
					throw new CException(Yii::t('yii', 'CProfileLogRoute found a mismatching code block "{token}". Make sure the calls to Yii::beginProfile() and Yii::endProfile() be properly nested.',
						array('{token}' => $token)));
			}
		}
		// remaining entries should be closed here
		$now = microtime(true);
		while (($last = array_pop($stack)) !== null)
			$results[$last[4]] = array($last[0], $now - $last[3], count($stack));
		ksort($results);
		return array_map(array($this, 'formatLog'), $results);
	}

	public function formatLog(array $el)
	{
		// extract query from the entry
		$queryString = $el[0];
		$sqlStart = strpos($queryString, '(') + 1;
		$sqlEnd = strrpos($queryString, ')');
		$sqlLength = $sqlEnd - $sqlStart;

		$queryString = substr($queryString, $sqlStart, $sqlLength);

		if (false !== strpos($queryString, '. Bound with ')) {
			list($query, $params) = explode('. Bound with ', $queryString);

			$params = explode(',', $params);
			$binds = array();

			foreach ($params as $param)
			{
				list($key, $value) = explode('=', $param, 2);
				$binds[trim($key)] = trim($value);
			}

			$el[0] = strtr($query, $binds);
		}
		else
		{
			$el[0] = $queryString;
		}

		$el[0] = $this->getTextHighlighter()->highlight($el[0]);

		$el[0] = strip_tags($el[0], '<div>,<span>');
		$el[1] = sprintf('%0.6F', $el[1]);
		return $el;
	}

	/**
	 * @static
	 * @return CTextHighlighter
	 */
	public function getTextHighlighter()
	{
		if (is_null($this->_textHighlighter)) {
			$this->_textHighlighter = Yii::createComponent(array(
				'class' => 'CTextHighlighter',
				'language' => 'sql',
				'showLineNumbers' => false,
			));
		}
		return $this->_textHighlighter;
	}
}