<?php

namespace Graphox\Modules\User\Repository;
use HireVoice\Neo4j\Repository as BaseRepository;

class User extends BaseRepository
{
	const MODEL_CLASS = '\\Graphox\\Modules\\User\\User';
	
	public function findByEmail($email)
	{
		$mail = Yii::app()->neo4j->getRepository(self::MODEL_CLASS)->findByAddress($email);
		
		if($mail)
			return $mail->getUser();
		
		return null;
	}
	
	public function findAllByName($name)
	{
		return $this->findBy(array(
			'fullName' => $name
		));
	}
}