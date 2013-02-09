<?php

class AsDashboardWidget extends CWidget
{
	public $dashboard;
	public function run()
	{
		echo '<div class="dashboard">';
		
		foreach($this->dashboard->rows as $iRow => $row)
		{
			$row = (object)$row;
			$i = floor(12/count($row->columns));
			echo '<div class="row-fluid">';
			foreach($row->columns as $iColumn => $column)
			{
				$column = (object)$column;
				
				if(isset($column->callback))
					$this->evaluateExpression ($column->callback, array(
						'i'			=> $i,
						'row'		=> $row,
						'content'	=> $content
					));
				
				echo '<div class="span'.$i.'">';
				$content = $this->controller->renderPartial('ext.dashboard.views.widget', array('column' => $column), true);
				
				if(!isset($column->noBox) || !$column->noBox)
				{
					$w = $this->widget('bootstrap.widgets.TbBox', array(
						'title' => isset($column->title) ? $column->title : 'Widget',
						'headerIcon' => isset($column->icon) ? $column->icon:'icon-home',
						'content' => $content,
						'headerButtons' => array(
							array(
								'class' => 'bootstrap.widgets.TbButtonGroup',
								'type' => 'primary', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
								'buttons' => array(
									array('label' => 'Fullscreen', 'htmlOptions' => array(
										'class' => 'toggle-fullscreen'
									)), // this makes it split :)
								)
							)
						)
					));


					Yii::app()->clientScript->registerScript(
						__CLASS__.'.fullscreen',
						'$(".toggle-fullscreen", $("#'.$w->id.'").parent()).click(function() {
							$("#'.$w->id.'").parent().toggleClass("fullscreen");
							$(window).resize();
						})
						console.log($(".toggle-fullscreen", $("#'.$w->id.'").parent()))
						',
						CClientScript::POS_READY
					);
				}
				else
				{
					echo $content;
				}
				
				echo '</div>';
			}
			echo '</div>';
		}
		echo '</div>';
	}
}

