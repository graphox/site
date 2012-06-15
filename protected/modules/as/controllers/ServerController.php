<?php

class ASBuffer
{
	public $stack = array();

	function getc()
	{ 
		return array_shift($this->stack);
	}

	function getint()
	{  
		$c = $this->getc();
		if ($c == 0x80)
		{
			$n = $this->getc(); 
			$n |= $this->getc() << 8; 
			return $n;
		}
		else if ($c == 0x81)
		{
			$n = $this->getc();
			$n |= $this->getc() << 8;
			$n |= $this->getc() << 16;
			$n |= $this->getc() << 24;
			return $n;
		}
		return $c;
	}

	function getstring($len=10000)
	{
		$r = ""; $i = 0; 
		while (true)
		{ 
			$c = $this->getint();
			if ($c == 0) return $r;
			$r .= chr($c);
		} 
	}
};

function ext_get_player($s, $b) {
	$g = fread($s, 50);
	$b->stack = unpack("C*", $g);

	for ($i = 1; $i <= 7; $i++) $b->getint();

	$player["cn"] = $b->getint();
	$player["ping"] = $b->getint();
	$player["name"] = $b->getstring();
	$player["team"] = $b->getstring();
	$player["frags"] = $b->getint();

	if ($player["frags"] >= 200) 
		$player["frags"] -= 256;

	$player["flags"] = $b->getint();
	$player["deaths"] = $b->getint();
	$player["teamkills"] = $b->getint();
	$player["acc"] = $b->getint();
	$player["health"] = $b->getint();

	if ($player["health"] >= 200) 
		$player["health"] -= 256;
			
	$player["armour"] = $b->getint();
	$player["gun"] = $b->getint();
	$player["priv"] = $b->getint();
	$player["state"] = $b->getint();
			
	$ip = $b->getint();
	$ip .= '.'.$b->getint();
	$ip .= '.'.$b->getint().'.255';	
	$player["ip"] = $ip;
	
	return $player;
}

class ServerController extends Controller
{

	function actionIndex()
	{
		$this->render('index');
	}


	function actionUpdatelist()
	{
		$returning = array();
			
		$i = 0;
				
		do
		{
			$socket = fsockopen('sauerbraten.org', 28787, $errno, $errstr);
		}
		while($i < 3 && $i++ && !$socket);
		
		if(!$socket)
			throw new exception ('could not update from masterserver: Unable to connect #'.(int)$errno.' '.$errstr);
				
		fwrite ($socket , 'list'."\n" );
				
		$data = '';
				
		while(!feof($socket))
			$data .= fread($socket, 100000);
				
		fclose($socket);
				
		foreach(explode("\n", $data) as $i => $row)
		{
			$row = explode(' ', $row);
			if(!isset($row[1]))
				continue;
					
			if(!isset($row[2]))
				$row[2] = 28786;
					
			$returning[] = array($row[1], $row[2]);
		}

		#store for 10 minutes
		Yii::app()->cache->set('serverlist', $returning, 10*60);
		
		return $returning;
	}
	
	function actionGetlist($cache = false)
	{
		$serverlist = Yii::app()->cache->get('serverlist');
		
		#not cached
		if($serverlist === false)
		{
			$serverlist = $this->actionUpdatelist(true);
		}	
		
		if(!$cache)
			echo json_encode($serverlist);
		else
			return $serverlist;
		
	}
	
	function actionGetserver($ajax = true)
	{
		if(!isset($_GET['port']) || !isset($_GET['ip']))
			throw new CHttpException(400, 'ip or port not set');
		
		#query the info port
		$_GET['port'] = (int)$_GET['port'] + 1;
		
		$server = Yii::app()->cache->get('server-'.$_GET['ip'].':'.$_GET['port']);
		
		if(!$ajax && $server === false)
		{
		
			$players = array();
			$serverinfo = array(
				'millis' => -1,
				'numplayer' => 1000,
				'numattr' => -1,
				'map' => '-none-',
				'desc' => 'internal error'
			);
				
			try
			{
				$i = 0;
				do
				{
					#we don't have time to wait
					$s = stream_socket_client("udp://".$_GET['ip'].":".$_GET['port'], $errorno, $errorstr, 0);
				}
				while($i < 3 && $i++ && !$s);
				
				if(!$s)
					throw new Exception(isset($errorstr) ? $errorstr : 'could not open connection');
		
				//get players
				fwrite($s, pack("ccc", 0, 1, '-1')); 
				$b = new ASBuffer();
				
				$g = fread($s, 50);
	
				if (!$g)
					throw new Exception ('Could not open extinfo connection');
			
	
				$x = unpack("C*", $g);
				$b->stack = unpack("C*", $g);
		
				$cn_players = array();
				for ($i = 0; $i <= 7; $i++) 
					if ($b->getint() == 0)
					{ // no error packet
						for ($i=1;$i<=5;$i++) $b->getint();
						break;
					}
	
				for ($i = 7; $i < 100; $i++) {
					$tmp = $b->getint();
					if ($tmp == NULL) continue; 
					$cn_players[$i-7] = $tmp;
				}
		
		
				if ($x[5] == 103 || $x[5] == 104 || $x[5] == 105)  // make sure we are compatible with this server
				{
					if (sizeof($x) > 7) 
						foreach($cn_players as $n_player)
							$players[] = ext_get_player($s, $b);
				}
				else
					throw new Exception('not compatible');
		
				fwrite($s, pack("c", 1));
		
				$b->stack = unpack('C*', fread($s, 50));
				 
				$serverinfo['millis'] = $b->getint();
				$serverinfo['numplayers'] = $b->getint();
				$serverinfo['numattr'] = $b->getint();

				for($attr = 0; $serverinfo['numattr'] <= $attr; $attr++)
					$serverinfo['attr '.$attr] = $b->getint();
		
				$serverinfo['map'] = CHtml::encode($b->getstring());
				$serverinfo['desc'] = CHtml::encode($b->getstring());
				
				#mem
				$b = null;
				unset($b);
		
				fclose($s);
			}
			catch(Exception $e)
			{
				$serverinfo['desc'] = $e->getMessage();
			}
			
			#cache this for ever, should be updated with ajax
			Yii::app()->cache->set('server-'.$_GET['ip'].':'.$_GET['port'], array($players, $serverinfo));
		}
		else
			list($players, $serverinfo) = $server;
		
		if($ajax == true)
			echo json_encode(array($players, $serverinfo));
		else
			return array($players, $serverinfo);
	}
	
	function actionInit_list()
	{
		$list = $this->actionGetlist(true);
		
		
		foreach($list as $i => &$server)
		{
			$_GET['ip'] = $server[0];
			$_GET['port'] = $server[1];
			
			$server_ = $this->actionGetserver(false);
			$server[] =  $server_[0];
			$server[] =  $server_[1];
		}
		
		echo json_encode($list);
	}
}
