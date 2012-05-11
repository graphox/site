<div id="content" >
	<!-- title -->
	<div id="page-title">
		<span class="title">Add Comment</span>
		<span class="subtitle">RE: <?=CHtml::encode($page->title)?>.</span>
	</div>
	<!-- ENDS title -->
							
	<!-- column (left)-->
	<div class="one-column" id="other-column">
		<!-- form -->
		<h2>Contact Form</h2>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'page-comments-_commentform-form',
			'enableAjaxValidation'=>false,
			'htmlOptions' => array('class' => 'admin-form')
		)); ?>
		<fieldset>

			<p class="note">Fields with <span class="required">*</span> are required.</p>

			<?php echo $form->errorSummary($model); ?>

			<div class="row">
				<?php echo $form->labelEx($model,'title'); ?>
				<?php echo $form->textField($model,'title', array('class' => 'form-poshytip', 'title' => 'The title of your post', 'placeholder' => CHtml::encode('RE: '.$page->title))); ?>
				<?php echo $form->error($model,'title'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'content'); ?>
				<?php echo $form->textArea($model,'content', array('class' => 'form-poshytip', 'title' => 'The title of your post', 'rows' => 5, 'cols' => 20)); ?>
				<?php echo $form->error($model,'content'); ?>
			</div>

			<div class="row buttons">
				<?php echo CHtml::submitButton('Submit', array('id' => 'submit')); ?>
			</div>

			<p id="success" class="success">Your comment was added, <a>here</a>.</p>
		</fieldset>

		<?php $this->endWidget(); ?>
		<!-- ENDS form -->
	</div>
	<!-- ENDS column -->
						
	<!-- column (right)-->
	<div class="one-column">
	
		<h2>Other reactons</h2>
		<div style="float:right;margin-top:-28px;margin-right:10px;" id="reaction-update-control">
			<span title="status" id="update-status" class="warning-box" style="background-color:lightblue;none;padding:0;float:left;height:32px;width:32px;background-position:0"></span>
			<a id="update-pause" class="pause-box" style="float:left;padding:0;height:32px;width:32px;background-position:0" title="Stop"></a>
			<a id="update-start" class="play-box" style="float:left;padding:0;visibility:hidden;display:none;height:32px;width:32px;background-position:0" title="Start"></a>
			<div style="clear:both"></div>
		</div>
		<div style="clear:both"></div>
		<!-- content -->
		<ul id="reactionbox" style="overflow:scroll">
		</ul>
		
		<script type="text/javascript">
			$(function($) {
				var AddElement = function(element)
				{
					$("ul#reactionbox").prepend("<li><a href=\""+element.url+"\"><h3 class=\"small\" style=\"display:inline\">"+element.title+"</h3><strong style=\"display:inline\">"+element.username+":</strong> <div style=\"display:inline\">"+element.html+"</div></li>");
				}
			
				var url = "<?=$this->createurl('comment/fetch', array('page-id' => 1)); ?>";
				var fetched = [];
				
				var paused = false;
				var AjaxUpdate = function()
				{
					if(!paused)
						$("#update-status").attr('class', 'refresh-box');
					var xhr = $.get(url, function(data)
					{
						$.each(data, function(i, value)
						{
							if(fetched[value.id] == undefined)
							{
								AddElement(value);
								fetched[value.id] = true;
							}
						});
						
						if(!paused)
						{
							$("#update-status").attr('class', 'success-box');
							setTimeout(AjaxUpdate, 5000); //update every 5 seconds
						}

							
					}, 'json');
					
					xhr.fail(function()
					{
						$("#update-status").attr('class', 'error-box');
						console.log("stopped updating, server error.");
					});
					
					$("#update-start").click(function()
					{
						$("#update-status").attr('class', 'refresh-box');					
						setTimeout(function(){
							paused = false;
							AjaxUpdate();
						
						}, 5000);
						
						$(this).css("visibility", "hidden");
						$(this).css("display", "none");
						$("#update-pause").css("visibility", "visible");
						$("#update-pause").css("display", "block");
					})
					
					$("#update-pause").click(function()
					{
						paused = true;
						$("#update-status").attr('class', 'pause-box');
						$(this).css("visibility", "hidden");
						$(this).css("display", "none");
						$("#update-start").css("visibility", "visible");
						$("#update-start").css("display", "block");
					})
				}
				
				AjaxUpdate();
				$("ul#reactionbox").height($("#other-column").height());
			});
				
			
			   
		</script>

		<!-- ENDS content -->
	</div>
	<!-- ENDS column -->	
</div>
