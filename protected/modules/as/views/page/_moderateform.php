<div id="content" >
	<!-- title -->
	<div id="page-title">
		<span class="title">Edit page</span>
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
				<?php echo $form->textField($model,'title', array('class' => 'form-poshytip', 'title' => 'The title of your post', 'placeholder' => CHtml::encode($page->title))); ?>
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
				$.keyup
			});
				
			
			   
		</script>

		<!-- ENDS content -->
	</div>
	<!-- ENDS column -->	
</div>
