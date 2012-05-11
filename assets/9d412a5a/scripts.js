var QPanelPool = {};
var QtzPanel = function (options) {
	var self = this;

	var options = jQuery.extend({
		id:'',
		initiallyOpen:false,
		useCookie:true,
		panelHeight:'100px',
		showBarLogs:false,
		showBarDB:false
	}, options);

	self.wrapper = jQuery('div.qtzpanelWrapper');
	self.panel = jQuery('#qtzpanel-' + options.id);
	self.bar = jQuery('#qtzpanelBar-' + options.id);
	self.logs = jQuery('#qtzpanelLogs-' + options.id);
	self.logsDB = jQuery('#qtzpanelLogsDB-' + options.id);

	self.show = function (withoutAnimate) {
		if (withoutAnimate) {
			self.panel.css({height:options.panelHeight});
		} else {
			self.panel.animate({height:options.panelHeight});
		}
		self.bar.find('li.doshow').hide();
		self.bar.find('li.dohide').show();

		if (options.useCookie) {
			jQuery.cookie('qtzpanel-' + options.id + '-showed', true);
		}
	};

	self.hide = function (withoutAnimate) {
		if (withoutAnimate) {
			self.panel.css({height:0});
		} else {
			self.panel.animate({height:0});
		}
		self.bar.find('li.doshow').show();
		self.bar.find('li.dohide').hide();

		if (options.useCookie) {
			jQuery.cookie('qtzpanel-' + options.id + '-showed', false);
		}
	};

	self.logsOpened = false;
	self.logsToggle = function () {
		self.closeAllBarLogs('db');
		if (self.logsOpened) {
			self.logsClose();
		} else {
			self.logsOpen();
		}
	};

	self.logsOpen = function () {
		if (self.logsOpened) return;
		this.h = parseInt(self.logs.outerHeight());
		self.logs.animate({
			bottom:this.h * -1 + 'px'
		}, function () {
			jQuery(this).css('z-index', 100000)
		});
		self.logsOpened = true;
	};

	self.logsClose = function () {
		if (!self.logsOpened) return;
		self.logs.css('z-index', 1).animate({
			bottom:0
		});
		self.logsOpened = false;
	};

	self.logsDBOpened = false;
	self.logsDBToggle = function () {
		self.closeAllBarLogs('logs');
		if (self.logsDBOpened) {
			self.logsDBClose();
		} else {
			self.logsDBOpen();
		}
	};

	self.logsDBOpen = function () {
		if (self.logsDBOpened) return;
		this.h = parseInt(self.logsDB.outerHeight());
		self.logsDB.animate({
			bottom:this.h * -1 + 'px'
		}, function () {
			jQuery(this).css('z-index', 100000)
		});
		self.logsDBOpened = true;
	};

	self.logsDBClose = function () {
		if (!self.logsDBOpened) return;
		self.logsDB.css('z-index', 1).animate({
			bottom:0
		});
		self.logsDBOpened = false;
	};

	self.closeAllBarLogs = function (need) {
		for (id in QPanelPool) {
			if (id != options.id) {
				QPanelPool[id].logsDBClose();
				QPanelPool[id].logsClose();
			} else {
				if (need != 'logs') {
					QPanelPool[id].logsDBClose();
				}
				if (need != 'db') {
					QPanelPool[id].logsClose();
				}
			}
		}
	};

	self.needShow = false;

	jQuery(function () {
		self.bar.find('li.doshow > a').bind('click', function () {
			self.show();
			return false;
		});
		self.bar.find('li.dohide > a').bind('click', function () {
			self.hide();
			return false;
		});


		if (!options.useCookie) {
			self.needShow = options.initiallyOpen;
		} else {
			if (jQuery.cookie('qtzpanel-' + options.id + '-showed') == null) {
				self.needShow = options.initiallyOpen;
			} else {
				self.needShow = jQuery.cookie('qtzpanel-' + options.id + '-showed') == 'true';
			}
		}
		if (self.needShow) {
			self.show(true);
		} else {
			self.hide(true);
		}


		if (options.showBarDB) {
			jQuery('#qtzpanel_bar_db-' + options.id).css('cursor', 'pointer').live('click', function () {
				self.logsDBToggle();
			});
		}
		if (options.showBarLogs) {
			jQuery('#qtzpanel_bar_logs-' + options.id).css('cursor', 'pointer').live('click', function () {
				self.logsToggle();
			});
		}
	});
};

var QtzPanelHelper = {
	DBLogTemplate:'<span class="time">{time} sec.</span>{message}<br />',
	logTemplate:'<span class="time">{time}</span> <span class="type">{type}</span> <span class="sender">{sender}</span> <br /><span class="text">{message}</span><br />',
	setMemory:function (value) {
		jQuery('span.qtzMemoryPoint').html(value);
	},
	setTime:function (value) {
		jQuery('span.qtzTimePoint').html(value);
	},
	setDBStat:function (value) {
		jQuery('span.qtzDBPoint').html(value);
	},
	setLogs:function (logs) {
		console.log(logs);
		jQuery('div.qtzpanelWrapper div.logs > code').html('');
		for (i in logs) {
			jQuery('div.qtzpanelWrapper div.logs > code').html(
					jQuery('div.qtzpanelWrapper div.logs > code').html() +
							this.logTemplate.replace('{time}', logs[i][3]).replace('{type}', logs[i][1]).replace('{sender}', logs[i][2]).replace('{message}', logs[i][0])
			);
		}
	},
	setDBLogs:function (logs) {
		jQuery('div.qtzpanelWrapper div.logsDB > code').html('');
		for (i in logs) {
			jQuery('div.qtzpanelWrapper div.logsDB > code').html(
					jQuery('div.qtzpanelWrapper div.logsDB > code').html() +
							this.DBLogTemplate.replace('{time}', logs[i][1]).replace('{message}', logs[i][0])
			);
		}
	}
};