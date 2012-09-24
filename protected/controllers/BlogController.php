<?php

class BlogController extends Controller
{
    /**
     * @var string the default layout for the views.
     */
    public $layout='//layouts/column2';

	/**
	 *
	 * @var Blog the blog that the user is browsing.
	 */
	public $blog;
	
	/**
	 *
	 * @var BlogEntity the post the user is browsing.
	 */
	public $post;
	
	/**
	 * Displays an index page containing the recently published posts of the accepted blogs.
	 */
	public function actionIndex()
	{

		$model = BlogEntity::model()->findRecentPublished();
		//ArrayList<Node> results = new ArrayList<Node>();for ( Node node : g.getRawGraph().index().forNodes("Blog").query("modelclass:Blog AND modelclass:Blog")) { results.add(node.) }; return results
		//g.getRawGraph().index().forNodes("Blog").query("modelclass:Blog AND it.publish == true")	
				
		$this->render('index', array(
			'dataProvider' => new CArrayDataProvider($model)
		));


	}
	
	/**
	 * Checks whether this is an ajax request and validates the model when it is.
	 * @param Blog $model
	 */
	public function ajaxValidateBlog($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'blog-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
	}
	
	/**
	 * Form for creating a blog.
	 * @todo: better api for the owner actions.
	 */
	public function actionCreate()
	{
		if(Yii::app()->user->isGuest)
			$this->redirect (array('/user/login'));
		
		$model = new Blog;
		
		$this->ajaxValidateBlog($model);
		
		if(isset($_POST['Blog']))
		{
			$model->attributes = $_POST['Blog'];
			
			if($model->save())
			{
				try
				{
					$relation = new _BLOG_OWNER_;
					$relation->rules = array_keys(
						array_filter(
							Blog::getOwnerAccessActions()
						)
					);
					
					$relation->startNode = Yii::app()->user->node;
					$relation->endNode = $model;
					
					if(!$relation->save())
					{
						Yii::log ('Error while storing relation! '.print_r($relation->getErrors (), true), CLogger::LEVEL_ERROR, 'as.blog');
						throw new CException('Invalid relation, please contact an admin!');
					}
					
					$this->redirect(array('/blog/viewBlog', 'name' => $model->routeName));
				}
				catch(CException $e)
				{
					$model->destroy();
					//$model->delete();
					throw $e;
				}
			}
		}
		
		$this->render('createBlog', array('model' => $model));
	}
	
	/**
	 * Main view of a blog, displays main content
	 */
	public function actionViewBlog()
	{
		$this->render('viewBlog', array('model' => $this->blog));
	}
	
	public function filters()
	{
		return array(
			'Blog + viewPost, viewBlog, createPost, updatePost, deletePost',
			'BlogPost + viewPost, updatePost, deletePost',
		);
	}
	
	/**
	 * Filter to automatically find the parent blog node.
	 * @param type $chain
	 * @throws CHttpException when unable to locate the blog.
	 */
	public function filterBlog($chain)
	{
		if(!isset($_GET['name']) || !is_string($_GET['name']))
			throw new CHttpException(404, 'Could not find blog.');
		
		$this->blog = Blog::model()->findByAttributes(array('routeName' => $_GET['name']));
		
		if($this->blog === null)
			throw new CHttpException(404, 'Could not find blog.');
		
		$chain->run();
	}
	
	/**
	 * Filter for automatic post location.
	 * @param type $chain
	 * @throws CHttpException when unable to locate the post.
	 */

	public function filterBlogPost($chain)
	{
		if(!isset($_GET['id']))
			throw new CHttpException(400, 'invalid uri');
		
		$this->post = BlogEntity::model()->findById($_GET['id']);
		
		if($this->post === null || $this->post->blog->id !== $this->blog->id || $_GET['title'] !== $this->post->routeName) //hack attempt
			throw new CHttpException(404, 'Could not find post');
		
		$chain->run();
	}
	
	/**
	 * Creates a blog.
	 */	
	public function actionCreatePost()
	{
		if(Yii::app()->user->isGuest || !$this->blog->hasAccess('blog.create'))
			if(Yii::app()->user->isGuest)
				$this->redirect (array('/user/login'));	
			else
				throw new CHttpException(403, 'You are not allowed to create blogposts!');
		
		$model = new BlogEntity();
		
		$this->performAjaxValidation($model);

        if(isset($_POST['BlogEntity']))
        {
            $model->attributes = $_POST['BlogEntity'];
            
            if($model->save())
			{
				$this->blog->addRelationshipTo($model, '_BLOG_HAS_POST_');
				
                $this->redirect(array(
					'/blog/viewPost',
					'name'	=> $this->blog->routeName,
					'id'	=> $model->id,
					'title'	=> $model->routeName
				));
			}
        }

        $this->render('createBlogPost',array(
            'model'=>$model,
        ));
	}
	
    /**
     * Displays the specified post
     */
    public function actionViewPost()
    {
		$this->render('viewBlogPost',array(
            'model'=>$this->post,
        ));
    }


    /**
     * Updates a particular blog post.
     * If update is successful, the browser will be redirected to the post.
	 * 
	 * @todo also check for blog.edit.OWN
     */
    public function actionUpdatePost()
    {
		if(Yii::app()->user->isGuest || !$this->blog->hasAccess('blog.edit')/* && ! blog.edit.OWN*/)
			if(Yii::app()->user->isGuest)
				$this->redirect (array('/user/login'));	
			else
				throw new CHttpException(403, 'You are not allowed to update this post.');
			
		$this->performAjaxValidation($this->post);

        if(isset($_POST['BlogEntity']))
        {
            $this->post->attributes=$_POST['BlogEntity'];
            if($this->post->save())
				$this->redirect(array(
					'/blog/viewPost',
					'name'	=> $this->blog->routeName,
					'id'	=> $this->post->id,
					'title'	=> $this->post->routeName
				));
        }

        $this->render('updateBlogPost',array(
            'model'=>$this->post,
        ));
    }

    /**
     * Deletes a particular blog post.
     * If deletion is successful, the browser will be redirected to the blog index page.
     */
    public function actionDeletePost()
    {
        if(Yii::app()->request->isPostRequest)
        {
            $this->post->delete();

            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(
					'viewBlog',
					'name' => $this->blog->routeName
				));
        }
        else
			$this->render('deleteBlogPost', array('model' => $this->post));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='entity-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
