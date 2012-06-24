<div>
<?php
	function print_recursive_message_tree($tree)
	{
		if($tree->children)
		{
			?>
				<div style="padding-left:10px">
					<strong>
						<?=CHtml::encode($tree->name)?>
					</strong>
					<ul>
					<?php
						foreach($tree->children as $element)
						{
							echo '<li>';
							print_recursive_message_tree($element);
							echo '</li>';
						}
					?>
					</ul>
				</div>
			<?php
		}
		else
		{
			?>
			<div style="padding-left:10px">
				<strong><?=CHtml::encode($tree->name)?></strong>
			</div>
			<?php
		}
	};

	print_recursive_message_tree($tree);
?>
</div>

<h1>messages</h1>
<h2><?=CHtml::encode($dir->name)?></h2>

<ul>
	<?php foreach($dir->pmMessages as $message): ?>
		<li>
			<strong><?=$message->title?></strong>
			<details>
				<summary><?=substr($message->content, 0, 10)?>..</summary>
				<p>
					<?=CHtml::encode($message->content)?>
				</p>
			</details>
		</li>
	<?php endforeach; ?>
</ul>

<?=CHtml::link('new message', array('//as/user/newMessage'))?>
