<!-- TODO: tabs
<ul class="mode">
	<li>servers</li>
	<li>players</li>
</ul>-->

<table id="servers" class="{sortlist: [[0,0],[1,0]]}">
	<thead>
		<tr>
			<th>playercount</th>
			<th>map</th>
			<th>description</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<table id="players" class="{sortlist: [[0,0],[1,0]]}">
	<thead>
		<tr>
			<th>name</th>
			<th>server</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>


<script type="text/javascript">
//<![CDATA[
//	$(function()
//	{
		
		var server_player_id = 0;
				
		var update_list = function()
		{
			var update_url = '<?=$this->createUrl('//as/server/getlist')?>';
			
			$.get(update_url, function(data)
			{
				$.each(data, function(i, t){ update_server(t[0], t[1]); });
				
				$("#servers").tablesorter({
					 headers: { 
					   0:{sorter: 'digit'}
					}
				});
				
				$("#players").tablesorter({
					 headers: { 
					   0:{sorter: 'digit'}
					}
				});
			}, "json");	
			
		};
		
		var init = function()
		{
			var init_url = '<?=$this->createUrl('//as/server/init_list')?>';
		
			$.get(init_url, function(data)
			{
				var j = 0;
				$.each(data, function(i, t)
				{
					var server_id = server_player_id;
					server_player_id ++;
					//console.log("adding server");
					var ip = t[0], port = t[1], players = t[2], info = t[3];
					var element, player_elements;
					var string;
					
					if(!players || !info)
						return;

					var build_details = function(players)
					{
						var details = '<h3>Players</h3><ul>';

						//foreach player
						$.each(players, function(key, value)
						{
							details += "<li>"+value["name"]+"</li>";
						});
				
						details += '</ul>';
						
						return details;
					};
					
					var build_info = function(info)
					{
						return "<td>"+info.numplayers+"</td>"+
							"<td>"+info.map+"</td>"+
							"<td>"+info.desc+"</td>"+
							"<td class=\"details\">details</td>";
					};
					
					var build_player_info = function(info, server)
					{
						return "<td>"+info['name']+"</td>"+
							"<td>"+server.desc+"</td>"+
							"<td class=\"details\">details</td>";
					};
					
					var update_player_info = function(players, server, init)
					{
						//console.log(".server-"+server_id);
						$(".server-"+server_id).remove();
						
						$.each(players, function(i, row)
						{
							var string = "<tr class=\"server-"+server_id+"\">";
							string += build_player_info(row, server);
							string += "</tr>";
							
							var element = $(string);
							//player_elements[] = element;
							$("#players").append(element);
						});
						
						$("#players").trigger("update");
					};
				
					var update_function = function()
					{
						//console.log("updating");
						
						var update_url = '<?=$this->createUrl('//as/server/getserver')?>';
			
						$.get(update_url, {ip: ip, port: port}, function(data)
						{
							var players = data[0], info = data[1];
							
							if(!players || !info)
								return;
							
							if(info.numplayers == 0)
								return;
								
							element.html(build_info(info));
							
							element.children("td.details").click(function()
							{
								$('<div>'+build_details(players)+'</div>').dialog({modal:true});
							});
							
							update_player_info(players,info);
							
							
							j++;
							setTimeout(update_function, 1000*j);
				
						}, "json");
					}
				
				
					if(info.numplayers == 0)
					{
						//update these too
						setTimeout(update_function, 5000*j);
						return;
					}

					string = "<tr id=\""+ip+"-"+port+"\">"+
									build_info(info)+
								"</tr>";
				
					//console.log("string="+string);
				
					element = $(string);
					
					element.children("td.details").click(function()
					{
						$('<div>'+build_details(players)+'</div>').dialog({modal:true});
					});
					
					$("#servers tbody").append(element);
				
					$("#servers").trigger("update");
					
					update_player_info(players, info, true);
					
					j++;
					//console.log("timeout="+1000*j);
					setTimeout(update_function, 1000*j);
				});
				
			}, "json");	

			$("#servers").tablesorter({
				 headers: { 
				   0:{sorter: 'digit'}
				},
				sortList: [[0,0],[2,0]] 
			});
			
			$("#players").tablesorter({
				 headers: { 
				   0:{sorter: 'digit'}
				}
			});
		}
		
		//setTimeout(100, update_list);
		
		$(init);
//	});
//]!>
</script>
