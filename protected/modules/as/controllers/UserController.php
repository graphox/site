<?php

class UserController extends Controller
{
	function actionFriends()
	{
		if(Yii::app()->user->isguest)
			$this->denyAccess();
			
		if(isset($_GET['action']) || ( isset($_POST['ajax']) && $_POST['ajax'] == 1))
		{
			$user = User::model()->findByAttributes(array('username' => isset($_POST['friend']) ? $_POST['friend'] : $_GET['friend']));
			$user_id = Yii::app()->user->id;
			
			if(!$user)
			{
				if(isset($_POST['action']))
					echo json_encode(array('result' => false, 'message' => 'Could not find user'));					
				else
				{
					Yii::app()->user->setFlash('error', 'Could not find user');
					$this->redirect(Yii::app()->request->referer);
				}			
			}
			
			switch(isset($_POST['action']) ? $_POST['action'] : $_GET['action'])
			{
				case 'user':
				
					break;
				case 'accept':
				case 'activate':
					$link = Friends::model()->findByAttributes(array('owner_id' => $user->id, 'friend_id' => $user_id));
					if(!$link)
						throw new CHttpException('could not find friend');
					$link = Friends::model()->findByPk($link->id);
					$link->status = Friends::STATUS_ACTIVE;
					
					$link2 = new Friends;
					$link2->owner_id = $user_id;
					$link2->friend_id = $user->id;
					$link2->status = Friends::STATUS_ACTIVE;
					
					if($link->save() && $link2->save())
						;
					else
						throw new exception('could not save');
					
					if(isset($_POST['action']))
						echo json_encode(array('result' => true, 'message' => 'successfully activated'));
					else
					{
						Yii::app()->user->setFlash('success', 'successfully activated');
						$this->redirect(Yii::app()->request->getUrlReferrer());
					}
					break;
	
					
				case 'block':
					$link = Friends::model()->findByAttributes(array('owner_id' => $user_id, 'friend_id' => $user->id));
					if(!$link)
						throw new CHttpException('could not find friend');
					$link->status = Friends::STATUS_IGNORE;
					if(!$link->save())
						throw new exception('could not save');
				
					if(isset($_POST['action']))
						echo json_encode(array('result' => true, 'message' => 'successfully blocked'));					
					else
					{
						Yii::app()->user->setFlash('success', 'successfully blocked');
						$this->redirect(Yii::app()->request->getUrlReferrer());
					}	
					break;				


				case 'remove':
					$link = Friends::model()->findByAttributes(array('owner_id' => $user_id, 'friend_id' => $user->id));
					$link2 = Friends::model()->findByAttributes(array('owner_id' => $user->id, 'friend_id' => $user_id));
					
					if($link->delete() && $link2->delete())
						;
					else
						throw new exception('could not save');
						
					if(isset($_POST['action']))
						echo json_encode(array('result' => true, 'message' => 'successfully removed'));					
					else
					{
						Yii::app()->user->setFlash('success', 'successfully removed');
						$this->redirect(Yii::app()->request->getUrlReferrer());
					}					
					break;
				
				
				default:
					throw new CHttpException(400, 'invalid action');
			}
		}
		$error = '';
		if(isset($_POST['friend-form']))
		{
			$model = new Friends;
			$model->owner_id = Yii::app()->user->id;
			if(isset($_POST['friend-form']['username']))
			{
				$user = User::model()->findByAttributes(array('username' => $_POST['friend-form']['username']));
			
				if($user)
				{
					$model->friend_id = $user->id;

					if(Friends::model()->findByAttributes(array('owner_id' => Yii::app()->user->id, 'friend_id' => $user->id)))
						$error = 'there is already a connection between you';
				}
				else
					$error = 'couldnot find user';
			}
			else
				$error = 'please fill in the name';
			$model->status = Friends::STATUS_PENDING;
			

			
			if(empty($error) && $model->validate())
			{
				if($model->save())
				{
					#...
					Yii::app()->user->setFlash('success', 'Sent friend request.');
				}
				else
					$error = 'Internal error, could not save friend';
			}
			elseif(empty($error))
				$error = 'Unkown format error';
		}
		
		$friends = Friends::model()->findAllbyAttributes(array('owner_id' => Yii::app()->user->id));
		
		$this->layout = '//layouts/column2';
		$this->title = 'Friends';
		$this->render('friends', array('model' => $friends, 'error' => $error));
	}
	
	function actionEdit()
	{
		if(Yii::app()->user->isguest)
			$this->denyAccess();
				
		$user = User::model()->findByPk(Yii::app()->user->id);
		$this->layout = '//layouts/column2';
		$this->render('edit', array('model' => $user));
	}
	
	function actionProfile()
	{
		if(!isset($_GET['name']))
			throw new CHttpException(400, 'no name given');
		
		$user = User::model()->with('profile', 'names')->findByAttributes(array('username' => $_GET['name']));
		
		$this->title = $user->username.'\'s profile';
		$this->render('profile', array('user' => $user));
	}
	
	function actionDashboard()
	{
		if(Yii::app()->user->isguest)
			$this->denyAccess();
			
		//ajax request: save, update 
		if(isset($_POST['ajax']) && $_POST['ajax'] == 1)
		{
			if (isset($_POST['portlets']) && !empty($_POST['portlets']))
			{
				$portlets = CJavaScript::jsonDecode($_POST['portlets']);
				$transaction=Yii::app()->db->beginTransaction();
			
				try
				{
					// Delete outdated user settings.
					DashboardPortlet::model()->deleteAll('uid=:uid', array(':uid' => Yii::app()->user->id));

					// Save user new settings.
					$model = new DashboardPortlet;
					$model->settings = serialize($portlets);
					$model->save();
					
					$transaction->commit();
					
					echo CJavaScript::jsonEncode(array('message' => 'Save Successfully'));
				}
				catch (Exception $e)
				{
					$transaction->rollBack();
					echo CJavaScript::jsonEncode(array('message' => 'Transaction Failed'));
				}
			}
			else
			{
				echo CJavaScript::jsonEncode(array('message' => 'Incorrect arguments'));
			}
			Yii::app()->end();
		}
		
		$settings = array();

		$this->layout = '//layouts/main';
		
		// Get user settings.
		$criteria = new CDbCriteria(array(
			'condition' => 'uid=' . Yii::app()->user->id,
		));

		$dataProvider = new CActiveDataProvider('DashboardPortlet', array('criteria' => $criteria));
		
		$data = $dataProvider->getData();
		
		if(isset($data[0]))
		{
			$userSettings = unserialize($data[0]->settings);
			
			foreach ($userSettings as $class => $properties)
			{
				$settings[$properties['column']][$properties['weight']] = array(
					'class' => $class,
					'visible' => $properties['visible'],
					'weight' => $properties['weight']
				);
			}
			
			foreach ($settings as $key => $value)
			{
				// Sort all portlets in every column by weight.
				ksort($settings[$key]);
			}
		}
		
		// Use the default portlets settings if user did not set any portlet before.
		if(empty($settings))
		{
			$deaultSettings = array(
					'NewsPorlet' => array('class' => 'NewsPorlet', 'visible' => true, 'weight' => 0), 
					'ForumsPorlet' => array('class' => 'ForumsPorlet', 'visible' => true, 'weight' => 1), 
					'VideosPorlet' => array('class' => 'VideosPorlet', 'visible' => true, 'weight' => 2), 
					'BlogsPorlet' => array('class' => 'BlogsPorlet', 'visible' => true, 'weight' => 3), 
					'FriendsPorlet' => array('class' => 'FriendsPorlet', 'visible' => true, 'weight' => 4),
					'ServersPorlet' => array('class' => 'ServersPorlet', 'visible' => true, 'weight' => 5),
			);
		
			foreach ($deaultSettings as $class => $properties)
			{
				$column = isset($properties['column']) ? $properties['column'] : 0;
				$settings[$column][$properties['weight']] = array(
					'class' => $class,
					'visible' => isset($properties['visible']) ? $properties['visible'] : true,
					'weight' => $properties['weight']
				);
			}
		}

		$this->render('dashboard',array(
			'portlets'=>$settings
		));
	}
	
	function actionIndex()
	{
		$this->redirect(array('//as/user/dashboard'));
	}

}
