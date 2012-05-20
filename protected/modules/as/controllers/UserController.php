<?php

class UserController extends Controller
{
	function actionFriends()
	{
	
	}
	
	function actionProfile()
	{
	
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
