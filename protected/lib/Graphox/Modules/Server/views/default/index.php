<script type="text/javascript">
	jQuery(function($)
	{
		var pending = [];
		var i = 0;
		
		window.setInterval(function()
		{
			for(var i in pending)
			{
				var request = pending[i];
				
				if(!request)
					continue;
				
				pending[i] = undefined;
				
			$.get("<?=CHtml::encode($this->createUrl('update'))?>", {ip: request.ip, port: request.port}, function(data)
			{
				request.callback(data);
			}, "json");
				
				return;
			}
		}, 500);
		
		function schedueRequest(ip, port, callback)
		{
			pending[i] = {
				ip: ip,
				port: port,
				callback: callback
			};
			
			i++;
		}
		
		$('.server-info').each(function()
		{
			var $this = $(this);
			var ip = $this.attr('data-ip');
			var port = $this.attr('data-port');
			
			var makeRequest;
			makeRequest = function(data)
			{
				var html = "";
				
				html += "<h3>"+data.serverName+"</h3>";
				html += "<p>"
					html += "gamemode: "+data.gameMode+"<br/>";
					html += "Map: "+data.mapName+"<br/>";
				html += "</p>"
				
				html += "<ul>"
				$.each(data.players, function(i, row)
				{
					html += "<li>" + row.name + "</li>"
				});
				html += "</ul>"
				
				$this.html(html);
				console.log(data);
				schedueRequest(ip , port, makeRequest);
			};
			
			schedueRequest(ip , port, makeRequest);
		})
	});
</script>

<?php
	foreach($servers as $server):
		$this->beginWidget('bootstrap.widgets.TbBox', array(
			'title' => $server[2],
			'headerIcon' => 'icon-th-list',
			'htmlOptions' => array('class'=>'bootstrap-widget-table')
		));
	?>
			<div class="server-info" data-ip="<?=\CHtml::encode($server[0])?>" data-port="<?=\CHtml::encode($server[1])?>">
				Loading ...
			</div>
		<?php	/*
			 $this->widget('bootstrap.widgets.TbDetailView', array(
				'data'=>$server,
				'attributes'=>array(
					array('name'=>'serverName'),
					array('name'=>'playerCount'),
					array('name'=>'maxPlayers'),
					
					array('name'=>'protocol'),
					array('name'=>'gamemode'),
					array('name'=>'timeleft'),
					
					array('name'=>'mastermode'),
					array('name'=>'mapName'),
				),
			));*/
		$this->endWidget();
	endforeach;