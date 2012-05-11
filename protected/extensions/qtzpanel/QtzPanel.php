<?php
/**
 * Created by Roman Revin <xgismox@gmail.com>.
 * Date: 31.01.12 13:09
 */

class QtzPanel extends CComponent implements QtzPanelInterface
{
	/**
	 * @var string id of panel
	 */
	public $id = null;

	/**
	 * placeholder for DB stats bar
	 */
	const BAR_DB = 'QtzPanel-bar-DB';

	/**
	 * placeholder for memory stats bar
	 */
	const BAR_MEMORY = 'QtzPanel-bar-memory';

	/**
	 * placeholder for execution time bar
	 */
	const BAR_EXECUTION_TIME = 'QtzPanel-bar-execution-time';

	/**
	 * placeholder for execution time bar
	 */
	const BAR_LOGS = 'QtzPanel-bar-logs';

	/**
	 * @var array bar pool
	 */
	public $bar = array();

	/**
	 * @var array elements pool
	 */
	public $elements = array();

	/**
	 * @var array a stack of intermediate parameters
	 */
	private $_state = array();

	/**
	 * @var array map of class
	 */
	public static $map = array(
		'raw' => 'QtzPanelElement_raw',
		'separator' => 'QtzPanelElement_separator',
		'single' => 'QtzPanelElement_single',
		'stack' => 'QtzPanelElement_stack'
	);

	/**
	 * init
	 */
	public function init()
	{
	}

	/**
	 * this method is alias to pushing separator
	 * @return QtzPanel
	 */
	public function separator()
	{
		return $this->push(array('separator'));
	}

	/**
	 * this method is alias to pushing raw text
	 * @param $html
	 * @return QtzPanel
	 */
	public function raw($html)
	{
		return $this->push(array('raw', 'html' => $html));
	}

	/**
	 * this method is alias to pushing single element
	 * @param $label
	 * @param string $url
	 * @param bool $icon
	 * @param bool $cssClass
	 * @param array $htmlOptions
	 * @param array $linkHtmlOptions
	 * @return QtzPanel
	 */
	public function single($label, $url = '#', $icon = false, $cssClass = false, $htmlOptions = array(), $linkHtmlOptions = array())
	{
		return $this->push(array('single', 'label' => $label, 'url' => $url, 'icon' => $icon, 'cssClass' => $cssClass, 'htmlOptions' => $htmlOptions, 'linkHtmlOptions' => $linkHtmlOptions));
	}

	/**
	 * this method is alias to pushing stack elements
	 * @param $items
	 * @param bool $cssClass
	 * @param array $htmlOptions
	 * @return QtzPanel
	 */
	public function stack($items, $cssClass = false, $htmlOptions = array())
	{
		return $this->push(array('stack', 'items' => $items, 'cssClass' => $cssClass, 'htmlOptions' => $htmlOptions));
	}

	/**
	 * Output method in the template panel.
	 */
	public function show($properties = array())
	{
		$widget = Yii::app()->getWidgetFactory()->createWidget($this, 'QtzPanelWidget', $properties);
		$widget->init();
		$widget->run();
	}

	/**
	 * The method of adding a new section on the panel.
	 * @param mixed $element the object instance is a descendant QtzPanelElement.
	 * @return QtzPanel
	 */
	public function push($element)
	{
		$obj = false;
		if (is_array($element)) {
			$type = array_shift($element);
			if (!isset(self::$map[$type])) {
				throw new CException(Yii::t('QtzPanel.app', 'Elements of type "{type}" not found in the stack QtzPanel::$map.', array('{type}' => $type)));
			} else {
				$obj = new self::$map[$type]($element);
			}
		}
		if (is_object($element) and $element instanceof QtzPanelElement) {
			$obj = $element;
		}
		if ($obj instanceof QtzPanelElement) {
			// push
			array_push($this->elements, $obj);
		}
		// result
		return $this;
	}

	/**
	 * Method to add arbitrary information to the bar.
	 * @param $html
	 * @return QtzPanel
	 */
	public function pushOnBar($html, $htmlOptions = array())
	{
		$result = $html;
		switch ($html) {
			case self::BAR_DB:
				$htmlOptions['id'] = 'qtzpanel_bar_db-' . $this->id;
				$this->pushState('bar-db', true);
				break;
			case self::BAR_LOGS:
				$htmlOptions['id'] = 'qtzpanel_bar_logs-' . $this->id;
				$this->pushState('bar-logs', true);
				break;
			default:
				break;
		}
		// push result
		array_push($this->bar, array(
			'content' => $result,
			'htmlOptions' => $htmlOptions,
		));
		// result
		return $this;
	}

	/**
	 * this method prepares the sections on the bar
	 */
	public  function prepareBars()
	{
		foreach ($this->bar as &$bar) {
			$result = false;
			switch ($bar['content']) {
				case self::BAR_DB:
					// if $html == self::BAR_DB
					CTextHighlighter::registerCssFile();
					$driver = Yii::app()->db->getDriverName();
					$result = CHtml::tag('span', array('class' => 'qtzDBPoint'), Yii::t(
						'QtzPanel.app',
						'{driver}: unknown info.',
						array('{driver}' => $driver)
					));
					break;
				case self::BAR_MEMORY:
					// if $html == self::BAR_MEMORY
					$result = Yii::t(
						'QtzPanel.app',
						'Usage memory: <span class="qtzMemoryPoint">unknown</span> Mb'
					);
					break;
				case self::BAR_EXECUTION_TIME:
					// if $html == self::BAR_EXECUTION_TIME
					$result = Yii::t(
						'QtzPanel.app',
						'Execution time: <span class="qtzTimePoint">unknown</span> s.'
					);
					break;
				case self::BAR_LOGS:
					// if $html == self::BAR_LOGS
					$result = Yii::t(
						'QtzPanel.app',
						'Show logs'
					);
					break;
				default:
					$result = $bar['content'];
					break;
			}
			$bar['content'] = $result;
		}
	}

	// getters/setters

	public function pushState($key, $value)
	{
		$this->_state[$key] = $value;
	}

	public function getState()
	{
		return $this->_state;
	}

	public function setState($value)
	{
		$this->_state = $value;
	}

	public function getId()
	{
		return CPropertyValue::ensureString($this->id);
	}

	public function setId($value)
	{
		$this->id = CPropertyValue::ensureString($value);
	}
}