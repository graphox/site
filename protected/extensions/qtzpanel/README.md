Quetzal Panel
=============

The control panel for the site Yii framework.

Install
-------

Extract source code in directory `application.extensions.qtzpanel`.

In `application.config.main`

```php
<?php
return array(
	'behaviors' => array(
		'ext.qtzpanel.QtzPanelBehavior',
		// ...
	),
	'components' => array(
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					// Route is only needed if you want to display the logging on the bar.
					'ext.qtzpanel.QtzPanelRoute'
				)
				// ...
			),
		),
		// ...
	),
	// ...
);
```

How to use?
-----------

In controller or view

```php
<?php
// set elements on panel "adminPanel"
Yii::app()->getPanel('adminPanel')
		->separator() // add separator
		->raw('Hello <b>administrator</b>.<br />How are you?') // add raw text
		->separator() // add separator
		->single(
			'Big button', // label
			array('controller/action', 'id' => 1), // url
			'http://goo.gl/AMVKB' // icon
		) // add single element
		->stack(array(
			array('label' => 'show', 'url' => '#', 'icon' => 'http://goo.gl/Wkvb3'),
			array('label' => 'more', 'url' => '#', 'icon' => 'http://goo.gl/6F5Ar'),
		)) // add stack elements
		->separator(); // add separator

// set elements on bar "adminPanel"
Yii::app()->getPanel('adminPanel')
		->pushOnBar(QtzPanel::BAR_DB) // add DB stats
		->pushOnBar(QtzPanel::BAR_LOGS) // add logs
		->pushOnBar(QtzPanel::BAR_MEMORY) // add memory stats
		->pushOnBar(QtzPanel::BAR_EXECUTION_TIME) // add time info
		->pushOnBar('custom info'); // add custom info

// set elements on panel "publicPanel"
Yii::app()->getPanel('publicPanel')
		->push(array('separator')) // add separator
		->push(array('raw', 'html' => 'Hello <b>user</b>.<br />How are you?')) // add raw text
		->push(new QtzPanelElement_separator()) // add separator (old school)
		->push(new QtzPanelElement_single('Show more', '#', 'http://goo.gl/t7EEQ')); // add single element (old school)
```

In your template

```php
<html>
<bead />
<body>
<?php
Yii::app()->getPanel('adminPanel')->show(array(
	'visibleRule' => 'Yii::app()->user->checkAccess("admin")', // this panel visible only admin
	'height' => 120, // this panel is 120px  height
	'useCookie' => true, // this panel use cookiew
	'initiallyOpen' => false // this panel is closed by default
));
Yii::app()->getPanel('publicPanel')->show(array(
	'skin' => 'dark', // skin dark
	'visibleRule' => 'true', // this panel visible for all
	'height' => 80, // this panel is 80px height
	'useCookie' => false, // this panel not use cookie
	'initiallyOpen' => true // this panel is open by default

));
?>
<p>content</p>
</body>
</html>
```

Working preview
-----------

Screenshot 1 (from example):
<img src="http://farm8.staticflickr.com/7144/6807213489_bb44299fb0_b.jpg" alt="Screenshot1" />

Screenshot 2 (in real project):
<img src="http://farm8.staticflickr.com/7154/6800627199_386737ebbc_b.jpg" alt="Screenshot2" />

