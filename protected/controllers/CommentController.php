<?php

class CommentController extends Controller
{
	public $layout = '//layouts/column2';
	
	public function actionCreate($parentId)
	{
		$model = new application\models\Comment;
		
		$q = new EGremlinScript('g.v(id)');
        $q->setParam('id', (int)$parentId);
        $parent = ENeo4jNode::model()->populateRecord(
			ENeo4jNode::model()->getConnection()->queryByGremlin($q)->getData()
		);
		
		if($parent === NULL || !$parent instanceof \ICommentable)
			throw new CHttpException(404, 'Could not find parent.');
		
		if(isset($_POST['application\models\Comment']))
		{
			
			$model->attributes = $_POST['application\models\Comment'];
			
			if($model->save())
			{
				$parent->addComment($model);
				Yii::app()->user->setFlash('success', Yii::t('comment', 'successfully added comment!'));
				if(isset($_POST['returnUrl']))
					$this->redirect($_POST['returnUrl']);
			}
		}
		
		$this->render(
			'add',
				isset($_POST['returnUrl'])
				?	array('model' => $model, 'parentId' => $parentId, 'returnUrl' => $_POST['returnUrl'])
				:	array('model' => $model, 'parentId' => $parentId)
		);
	}
	
}
