 <div class="oauth services">
  <ul class="auth-services clear">
  <?php
	foreach ($services as $name => $service) {
		
		$id = $service->getId();
		$class = $service->getHtmlClass();
		
		echo '<li class="auth-service '.$class.'">';
		$html = '<span class="auth-icon '.$class.'"><i></i></span>';
		$html .= '<span class="auth-title">'.$service->title.'</span>';
		$html = CHtml::link($html, array('', 'service' => $id, 'status' => 'redirect'), array(
			'class' => 'auth-link '.$class,
		));
		echo $html;
		echo '</li>';
	}
  ?>
  </ul>
</div>