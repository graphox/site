<h1>Delete a post</h1>
<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm',array( 
    'id'=>'blog-delete-form', 
)); ?>
	<p>
		Are you shure you want to delete this blogpost?
	</p>
	<p>
		<strong>It will be impossible to recover it later!</strong>
	</p>
    <div class="form-actions"> 
		<?php $this->widget('bootstrap.widgets.BootButton', array( 
            'buttonType'=>'link', 
            'type'=>'secondary', 
            'label'=>'nope',
			'url' => array(
				'/blog/viewPost',
				'name'	=>	$this->blog->routeName,
				'id'	=>	$this->post->id,
				'title'	=>	$this->post->routeName,
			)
		))?>
        <?php $this->widget('bootstrap.widgets.BootButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>'Yes i am!', 
        )); ?>
    </div> 

<?php $this->endWidget(); ?>

