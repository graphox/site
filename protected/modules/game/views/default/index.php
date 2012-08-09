<?php Yii::app()->clientScript->registerScript('game', '
	$(".game-window").kGame({id: 0})
', CClientScript::POS_READY); ?>

<script type="text/javascript" >
					/* NOTE: the following code was extracted from the UFO source and extensively reworked/simplified */

/* Unobtrusive Flash Objects (UFO) v3.20 <http://www.bobbyvandersluis.com/ufo/ >
	Copyright 2005, 2006 Bobby van der Sluis
	This software is licensed under the CC-GNU LGPL <http://creativecommons.org/licenses/LGPL/2.1/ >
*/

function createCSS(selector, declaration) {
	// test for IE
	var ua = navigator.userAgent.toLowerCase();
	var isIE = (/msie/.test(ua)) && !(/opera/.test(ua)) && (/win/.test(ua));

	// create the style node for all browsers
	var style_node = document.createElement("style");
	style_node.setAttribute("type", "text/css");
	style_node.setAttribute("media", "screen"); 

	// append a rule for good browsers
	if (!isIE) style_node.appendChild(document.createTextNode(selector + " {" + declaration + "}"));

	// append the style node
	document.getElementsByTagName("head")[0].appendChild(style_node);

	// use alternative methods for IE
	if (isIE && document.styleSheets && document.styleSheets.length > 0) {
		var last_style_node = document.styleSheets[document.styleSheets.length - 1];
		if (typeof(last_style_node.addRule) == "object") last_style_node.addRule(selector, declaration);
	}
};


	(function($) {
		$.fn.kGame = function(options)
		{
			var undefined;
			var self = $(this);
			
			var defaults = {
				id: 0,
				url: "<?=$this->createUrl('ajax');?>",
			};
			
			var instance = {
				protocol: {
					//load block id's and images
					LOAD_BLOCKS : 0,
					
					//load the map
					LOAD_MAP : 1,
					
					//load event listners
					LOAD_EVENTS : 2,
					
					//the player has changed the map
					MAP_UPDATE : 10,
					
					//listen for any event happened
					LISTEN : 100,
				},
				
				protocol_cache : [],
				
				protocol_listners : {},
			
				options : $.extend({}, defaults, options),
				blocks : {},
				
				maps: [],
				
				element: undefined,

				loadImage : function(id, image)
				{
					var that = this;
					
					$.get(image, function()
					{
						createCSS("#map-"+that.options.id+" .map #gImg-"+id, "background: url("+image+")");
					}, "text");					
					
					this.blocks[id] = image;
				},
				
				loadBlocks : function(callback)
				{
					var that = this;
					this.makeRequest(
						[ this.protocol.LOAD_BLOCKS ],
						function(result, self)
						{
							$.each(result[0], function(k, v){
								that.loadImage(k, v)
							});

							self = undefined;
							callback()
						},
						true
					);
				},
				
				init : function(element)
				{
					var that = this;
					
					createCSS("#map-"+that.options.id+' .map .game-block', 'background:black;float:left;width:16px;height:16px');
					createCSS("#map-"+that.options.id+' .map .game-row', 'clear:both;height:16px');
				
					this.element = element;
					this.loadBlocks(
						function()
						{
							that.loadWorld(function(){
								that.initListners(function()
								{
									that.initEvents(function()
									{
										that.element.attr("id", "map-"+that.options.id);
										that.startListning();
									});
								});
							})							
						}
					);
					
				},
				
				startListning: function()
				{
					var that = this;
					this.makeRequest(
						[ this.protocol.LISTEN ],
						function(result, self)
						{
							setTimeout(function() { 
								that.startListning();
							 }, 5000);
						},
						true
					);				
				},
				
				initListners : function(callback)
				{
					var that = this;
					
					this.protocol_listners[this.protocol.MAP_UPDATE] = function(msg)
					{
						var map = msg[0];

						$.each(msg[1], function(i, msg)
						{
							var coord = {y: msg[1], x: msg[2]};
							that.maps[map][coord.y][coord.x] = msg[0];
							that.renderMap();
						});
					}
					
					callback();
				},
				
				bindEvent : function(id, event, func)
				{
					var that = this;
					
					this.element.off(event);
					this.element.on(event, function(event){
						var target = $(event.target);
					
						var x = target.index();
						var y = target.parent().index();
						eval('('+func+')')(id, y, x, that);
					});
				},
				
				initEvents : function(callback)
				{
					var that = this;
					this.makeRequest(
						[ this.protocol.LOAD_EVENTS ],
						function(result, self)
						{
							//result[0] - map id
							//result[1] - eventhandlers object eventname:function
							
							$.each(result[1], function(event, func)
							{
								that.bindEvent(result[0], event, func);
							});
							
							self = undefined;
							callback();
						},
						true
					);
				},
				
				makeRequest : function(message, callback, force)
				{
					var force = force && true;
					
					if(callback)
					{
						this.protocol_listners[
							message[0]
						] = callback;
					}
					
					this.protocol_cache.push(message)
					
					if(force)
						this.doRequest();
				},
				
				doRequest : function()
				{
					var obj = this;
					
					$.post(this.options.url, {
						map: this.options.id,
						msg: this.protocol_cache
					}, function(data) {
						$.each(data, function(i, msg) {
							obj.handlePackage(i, msg)
						});
					}, "json");
					
					this.protocol_cache = [];
				},
				
				handlePackage : function(i, msg)
				{
					var type = msg.shift();
					this.protocol_listners[type](msg, this.protocol_listners[type])					
				},
				
				updateMap : function(id, updates)
				{
					this.makeRequest([ this.protocol.MAP_UPDATE, id, updates ], false, true);					
				},
				
				loadWorld: function(callback)
				{
					var that = this;
					
					this.makeRequest(
						[ this.protocol.LOAD_MAP ],
						function(map, self)
						{
							that.maps[map[0]] = map[1];
							that.renderMap();
							self = undefined;
							callback();
						},
						true
					);					
				},
				
				renderMap: function()
				{
					var map_element = this.element.children('.map');
					map_element.html('');
					
					var that = this;
					
					//y
					$.each(this.maps[this.options.id], function(i, row)
					{
						var dom_row = $('<div class="game-row"></div>');
						//x
						$.each(row, function(i, block)
						{
							var dom_block = $('<div class="game-block" id="gImg-'+block+'"></div>');
							//dom_block.append(that.renderImage(block));
							dom_row.append(dom_block);
						})
						
						map_element.append(dom_row);
					});
				},
	
			};
			
			instance.init(self);			 
			
			$.data(self, "kGame", instance);			
		};
	
	})(jQuery);
</script>

<div class="game-window">
	<div class="background"></div>
	<div class="map"></div>
	<div class="overlay"></div>
</div>
