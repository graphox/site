<article class="module width_full">
	<header>
		<h3 class="tabs_involved">Privilege order</h3>
	</header>
	
	<p>
		Drag and drop to change the order of the rules.
	</p>
	
	<div>
		<table>
			<thead>
				<tr>
					<th>group</th>
					<th>object</th>
					<th>read</th>
					<th>write</th>
					<th>update</th>
					<th>delete</th>
				</tr>
			</thead>
			<tbody class="sortme">
				<?php foreach($model as $row): ?>
					<tr>
						<td><?=$row->group->name?></td>
						<td><?=$row->object->name?></td>
						<td><?=$row->read?></td>
						<td><?=$row->write?></td>
						<td><?=$row->update?></td>
						<td><?=$row->delete?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</article>

<script type="text/javascript">
	$(function()
	{
		$(".sortme").sortable({
			placeholder: "ui-state-highlight",
			update: function(event, ui)
			{
				var info = [];
				
				var i = 0;
				
				$.each($(".sortme").children().get(), function(j, element)
				{
					info[i] = {
						group: $.trim($(element).children().eq(0).text()),
						object: $.trim($(element).children().eq(1).text())
					};
					
					i++;
				});
				
				var base_url = "<?=$this->createUrl('//as/admin/acl/privileges/order')?>";
				console.log(info);
				
				$.post(base_url, {ajax: {action: "order", data:info}})
			}
		});
	});
</script>
