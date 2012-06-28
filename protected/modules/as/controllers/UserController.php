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
		
		if(isset($_POST['User']))
		{
			$user->attributes = array(
				'ingame_password' => isset($_POST['User']['ingame_password']) ? $_POST['User']['ingame_password'] : '',
				'web_password' => isset($_POST['User']['web_password']) && isset($_POST['retype_password']) && $_POST['retype_password'] == $_POST['User']['web_password'] ? $_POST['User']['web_password'] : '',
				
			);
			
			if($user->validate())
			{
				$user->web_password = Crypto::hash($user->web_password, $user->hashing_method);
				if($user->save(false))
					Yii::app()->user->setFlash('edit-account', 'successfully changed your password!');
			}
		}
		
		$name = new Names;
		
		if(isset($_POST['Names']))
		{
			$name->attributes = array(
				'name' => isset($_POST['Names']['name']) ? $_POST['Names']['name'] : '',
				'user_id' => Yii::app()->user->id,
				'status' => Names::STATUS_PENDING	
			);
			
			if($name->validate() && $name->save())
				Yii::app()->user->setFlash('edit-names', 'successfully added a name, please wait for an admin to verify the request.');
		}
		
		if(isset($_POST['delete-name']))
		{
			$dname = Names::model()->findByPk($_POST['delete-name']);
			
			if(!$dname)
				Yii::app()->user->setFlash('delete-names-error', 'Could not find requested name.');
			elseif($dname->user_id != Yii::app()->user->id)
				Yii::app()->user->setFlash('delete-names-error', 'You can only delete your own names!');
			else
			{
				if($dname->delete())
					Yii::app()->user->setFlash('edit-names', 'Successfully deleted name.');
				else
					Yii::app()->user->setFlash('delete-names-error', 'Could not delete the name, please try again.');
			}
		}
		
		$profile_model = new EditProfileForm;
		
		#copy profile model to editprofileform model
		if($user->profile)
		{
			$profile_model->load($user->profile);
		}
		
		
		if(isset($_POST['EditProfileForm']))
		{
			if(
				isset($_POST['EditProfileForm']['page_title']) && !empty($_POST['EditProfileForm']['page_title']) ||
				isset($_POST['EditProfileForm']['page_content']) && !empty($_POST['EditProfileForm']['page_content']) ||
				isset($_POST['EditProfileForm']['page_description']) && !empty($_POST['EditProfileForm']['page_description']) 
			)
				$profile_model->scenario = 'pageisset';

			$profile_model->attributes = $_POST['EditProfileForm'];
			
			$profile_model->user_id = Yii::app()->user->id;
			
			if($profile_model->save())
				Yii::app()->user->setFlash('edit-profile', 'Successfully saved profile.');
		}
		
		
		
		$user->web_password = '';
		
		Yii::app()->clientScript->registerScript('addNameSubmit', '
			$(".name-delete-buttons").toggle();
			$(".name-delete-buttons > span").click(function() {
				console.log("deleting name ...");
				$(this).parent().parent().submit();
			});
	

		', CClientScript::POS_READY);
		
		$this->layout = '//layouts/column2';
		$this->render('edit', array('model' => $user, 'name_model' => $name, 'profile_model' => $profile_model));
	}
	
	function actionProfile()
	{
		if(isset($_GET['id']))
			$user = User::model()->with('profile', 'names')->findByPk($_GET['id']);
		elseif(isset($_GET['name']))
			$user = User::model()->with('profile', 'names')->findByAttributes(array('username' => $_GET['name']));
		elseif(!Yii::app()->user->isGuest)
			$this->redirect(array('edit'));
		else
			throw new CHttpException(400, 'no name or id given');
		
		if(!$user)
			throw new CHttpEception(404, 'Could not find user');
		
		if(!isset($_GET['id']) || !isset($_GET['name']))
			$this->redirect(array('//as/user/profile', 'id' => $user->id, 'name' => $user->username));		
		
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

		//$this->layout = '//layouts/main';
		
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
	
	function actionNewMessage()
	{
		$form = new SendMessageForm;
		
		if(isset($_POST['SendMessageForm']))
		{
			$form->attributes = $_POST['SendMessageForm'];
			
			if($form->validate() && $form->save())
			{
				Yii::app()->user->setFlash('success', "message successfully sent!");
				$this->redirect(array('//as/user/message'));
			}
		}
		
		$this->render('new_message', array('model' => $form));
	}
	
	function actionMessage()
	{
		$dirs = PmDirectory::model()->findAllByAttributes(array('user_id' => Yii::app()->user->id));
		
		#jit create inbox
		if(count($dirs) == 0)
		{
			$dir = new PmDirectory;
			$dir->name = 'inbox';
			$dir->user_id = Yii::app()->user->id;
			$dir->save();
			$dirs[] = $dir;
		}
		
		$root = (object)array(
			'name' => 'Folders',
			'children' => array()
		);
		
		#build tree
			$ids = array();
			
			foreach($dirs as $dir)
				$ids[$dir->id] = (object)array('name' => $dir->name, 'children' => array());

			foreach($dirs as &$element)
				if($element->parent_id == null)
					$root->children[] =& $ids[$element->id];
				else
					$ids[$element->parent_id]->children[] = $ids[$element->id];
		#/build tree
		
		if(!isset($_GET['directory']))
			$_GET['directory'] = 'inbox';
		
		$dir = PmDirectory::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'name' => $_GET['directory']));
		
		if(!$dir)
			throw new CHttpException(404, 'Could not find folder: '. $_GET['directory']);
		
		$this->render('messages', array('tree' => $root, 'dir' => $dir));
	}

}
