<?php
/**
 * Created by Roman Revin <xgismox@gmail.com>.
 * Date: 01.02.12 20:47
 */

class QtzPanelHelper_
{
	public static $_textHighlighter = null;

	public static function getDBLogs()
	{
		$results = array();
		$n = 0;
		foreach (Yii::getLogger()->getLogs() as $row) {
			if ($row[1] == CLogger::LEVEL_PROFILE && strpos($row[2], 'system.db.CDbCommand') === 0) {
				//						echo CVarDumper::dumpAsString($row, 10, true);
				$message = $row[0];

				if (strncasecmp($message, 'begin:', 6) === 0) {
					$row[0] = substr($message, 6);
					$row[4] = $n;
					$stack[] = $row;
					$n++;
				}
				else if (strncasecmp($message, 'end:', 4) === 0) {
					$token = substr($message, 4);
					if (($last = array_pop($stack)) !== null && $last[0] === $token) {
						$delta = $row[3] - $last[3];
						$results[$last[4]] = array($token, $delta, count($stack));
					}
					else
						throw new CException(Yii::t('app',
							'Mismatching code block "{token}". Make sure the calls to Yii::beginProfile() and Yii::endProfile() be properly nested.',
							array('{token}' => $token)
						));
				}


			}
		}

		$now = microtime(true);
		while (null !== ($last = array_pop($stack))) {
			$results[$last[4]] = array($last[0], $now - $last[3], count($stack));
		}

		ksort($results);
		return array_map(array(__CLASS__, 'formatLog'), $results);
	}

	public static function formatLog(array $el)
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

		$el[0] = self::getTextHighlighter()->highlight($el[0]);

		$el[0] = strip_tags($el[0], '<div>,<span>');
		return $el;
	}

	/**
	 * @static
	 * @return CTextHighlighter
	 */
	public static function getTextHighlighter()
	{
		if (is_null(self::$_textHighlighter)) {
			self::$_textHighlighter = Yii::createComponent(array(
				'class' => 'CTextHighlighter',
				'language' => 'sql',
				'showLineNumbers' => false,
			));
		}
		return self::$_textHighlighter;
	}
}