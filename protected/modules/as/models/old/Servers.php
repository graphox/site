<?php

/**
 * This is the model class for table "servers".
 *
 * The followings are the available columns in table 'servers':
 * @property integer $id
 * @property string $name
 * @property string $ip
 * @property integer $port
 * @property integer $external
 * @property string $mode
 * @property string $map
 * @property string $updated_time
 * @property integer $online
 *
 * The followings are the available model relations:
 * @property OnlinePlayer[] $onlinePlayers
 */
class Servers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Servers the static model class
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
		return 'servers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name, ip, port, external, mode, map, updated_time, online', 'required'),
			array('id, port, external, online', 'numerical', 'integerOnly'=>true),
			array('name, ip, mode, map', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, ip, port, external, mode, map, updated_time, online', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'onlinePlayers' => array(self::HAS_MANY, 'OnlinePlayer', 'server_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'ip' => 'Ip',
			'port' => 'Port',
			'external' => 'External',
			'mode' => 'Mode',
			'map' => 'Map',
			'updated_time' => 'Updated Time',
			'online' => 'Online',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('port',$this->port);
		$criteria->compare('external',$this->external);
		$criteria->compare('mode',$this->mode,true);
		$criteria->compare('map',$this->map,true);
		$criteria->compare('updated_time',$this->updated_time,true);
		$criteria->compare('online',$this->online);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}