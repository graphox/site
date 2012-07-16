<?php

/**
 * This is the model class for table "activationkey".
 *
 * The followings are the available columns in table 'activationkey':
 * @property integer $id
 * @property string $hash
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Activationkey extends AsActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Activationkey the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activationkey';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hash, user_id', 'required', 'on' => 'insert,update'),
			array('user_id', 'numerical', 'integerOnly'=>true, 'on' => 'insert,update'),
			array('hash', 'length', 'max'=>500, 'on' => 'insert,update'),
			
			array('hash', 'required', 'on' => 'activate'),
			array('hash', 'activate', 'on' => 'activate'),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, hash, user_id', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * Validate the activation key and activate the account
	 */
	public function activate($field, $settings)
	{
		$model = self::model()->with('user')->findByAttributes(array(
			'hash' => $this->$field
		));
		
		if(!$this->hasErrors() && $model !== NULL)
		{
			Yii::log('Activated account: '.$model->user->username, 'info', 'as.user');
			$model->user->status = 'active';
			if(!$model->user->save(false))
				$this->addError('hash', 'could not activate user, please try again.');
			else
				$model->delete();
		}
		elseif($model === NULL)
			$this->addError($field, 'invalid activation id');
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'hash' => 'Activation key',
			'user_id' => 'User',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('hash',$this->hash,true);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
