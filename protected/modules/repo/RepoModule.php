<?php
/*
GET /yii-cp/as2/repo/index.git/info/refs?service=git-upload-pack HTTP/1.1" 404 492 "-" "git/1.7.5.4"
GET /yii-cp/as2/repo/index.git/info/refs HTTP/1.1" 404 492 "-" "git/1.7.5.4"
GET /yii-cp/as2/repo/index.git/info/ HTTP/1.1" 200 712 "http://localhost/yii-cp/as2/repo/index.git/" "Mozilla/5.0 (X11; Linux i686)
*/



class RepoModule extends CWebModule
{
	public $repos = array();
	public $services = array();
	
	public function __construct()
	{
		$this->repos = array(
			'as.git' => dirname(__FILE__).'/repos/as.git'
		);
		
		$this->services = array(
			array('GET', '/HEAD$', 'get_text_file'),
			array('GET', '/info/refs$', 'get_info_refs'),
			array('GET', '/objects/info/alternates$', 'get_text_file'),
			array('GET', '/objects/info/http-alternates$', 'get_text_file'),
			array('GET', '/objects/info/packs$', 'get_info_packs'),
			array('GET', '/objects/[0-9a-f]{2}/[0-9a-f]{38}$', 'get_loose_object'),
			array('GET', '/objects/pack/pack-[0-9a-f]{40}\\.pack$', 'get_pack_file'),
			array('GET', '/objects/pack/pack-[0-9a-f]{40}\\.idx$', 'get_idx_file')
		);
	}
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'repo.models.*',
			'repo.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		global $repos;
		
		$path = $_SERVER['REQUEST_URI'];
		$path = preg_replace('/^(.*)index.php\/repo\//i', '', $path);
		$path = explode('/', $path);

		
		
		if(isset($this->repos[$path[0]]))
		{
			$repo = $this->repos[$path[0]];
			
			$path[0] = '';
			$path = implode('/', $path);
			
			$found = false;
			foreach($this->services as $service)
			{
				if(preg_match('~^'.$service[1].'~', $path)) 
				{
					$found = true;
					if ($_SERVER['REQUEST_METHOD'] != $service[0])
					{
						header('Status: 405 Method Not Allowed');
						header('Allow: '.$service[0]);
						echo 'Method Not Allowed';
						Yii::app()->end();
					}
					
					call_user_func(array($this, $service[2]), $repo[1], $path);
					break;
				}
			}
			
			if(!$found)
				header('Status: 404 Not Found');
			
			Yii::app()->end();
		}
		else	
			print_r($path);
	
		return false;
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
	//git functions
	function str_endswith($s, $test) {
		$strlen = strlen($s);
		$testlen = strlen($test);
		if ($testlen > $strlen) return FALSE;
		return substr_compare($s, $test, -$testlen) === 0;
	}

	function header_nocache() {
		header('Expires: Fri, 01 Jan 1980 00:00:00 GMT');
		header('Pragma: no-cache');
		header('Cache-Control: no-cache, max-age=0, must-revalidate');
	}

	function header_cache_forever() {
		header('Expires: '.date('r', time() + 31536000));
		header('Cache-Control: public, max-age=31536000');
	}

	function send_local_file($type, $path) {
		$f = @fopen($path, 'rb');
		if (!$f) {
			header('Status: 404 Not Found');
			die();
		}

		$stat = fstat($f);
		header('Content-Type: '.$type);
		header('Last-Modified: '.date('r', $stat['mtime']));

		fpassthru($f);
		fclose($f);
	}

	function get_text_file($git_path, $name) {
		$this->header_nocache();
		$this->send_local_file('text/plain', $git_path.$name);
	}

	function get_loose_object($git_path, $name) {
		$this->header_cache_forever();
		send_local_file('application/x-git-loose-object', $git_path.$name);
	}

	function get_pack_file($git_path, $name) {
		$this->header_cache_forever();
		send_local_file('application/x-git-packed-objects', $git_path.$name);
	}

	function get_idx_file($git_path, $name) {
		$this->header_cache_forever();
		send_local_file('application/x-git-packed-objects-toc', $git_path.$name);
	}


	function ref_entry_cmp($a, $b) {
		return strcmp($a[0], $b[0]);
	}

	function read_packed_refs($f) {
		$list = array();

		while (($line = fgets($f)) !== FALSE) {
			if (preg_match('~^([0-9a-f]{40})\s(\S+)~', $line, $matches)) {
				$list[] = array($matches[2], $matches[1]);
			}
		}

		usort($list, 'ref_entry_cmp');
		return $list;
	}

	function get_packed_refs($git_path) {
		$packed_refs_path = $git_path.'/packed-refs';
		$f = @fopen($packed_refs_path, 'r');

		$list = array();

		if ($f) {
			$list = $this->read_packed_refs($f);
			fclose($f);
		}

		return $list;
	}

	function resolve_ref($git_path, $ref) {
		$depth = 5;

		while (TRUE) {
			$depth -= 1;
			if ($depth < 0) {
				return array(NULL, '0000000000000000000000000000000000000000');
			}

			$path = $git_path.'/'.$ref;
			if (!@lstat($path)) {
				foreach ($this->get_packed_refs($git_path) as $pref) {
					if (!strcmp($pref[0], $ref)) {
						return array($ref, $pref[1]);
					}
				}
				return array(NULL, '0000000000000000000000000000000000000000');
			}

			if (is_link($path)) {
				$dest = readlink($path);
				if (strlen($dest) >= 5 && !strcmp('refs/', substr($dest, 0, 5))) {
					$ref = $dest;
					continue;
				}
			}

			if (is_dir($path)) {
				return array(NULL, '0000000000000000000000000000000000000000');
			}

			$buffer = file_get_contents($path);
			if (!preg_match('~ref:\s*(.*)~', $buffer, $matches)) {
				if (strlen($buffer) < 40) {
					return array(NULL, '0000000000000000000000000000000000000000');
				}

				return array($ref, substr($buffer, 0, 40));
			}

			$ref = $matches[1];
		}
	}

	function get_ref_dir($git_path, $base, $list=array()) {
		$path = $git_path.'/'.$base;
		$dir = dir($path);

		while (($entry = $dir->read()) !== FALSE) {
			if ($entry[0] == '.') continue;
			if (strlen($entry) > 255) continue;
			if (str_endswith($entry, '.lock')) continue;

			$entry_path = $path.'/'.$entry;

			if (is_dir($entry_path)) {
				$list = get_ref_dir($git_path, $base.'/'.$entry, $list);
			} else {
				$r = $this->resolve_ref($git_path, $base.'/'.$entry);
				$list[] = array($base.'/'.$entry, $r[1]);
			}
		}

		usort($list, 'ref_entry_cmp');
		return $list;
	}

	function get_loose_refs($git_path) {
		return $this->get_ref_dir($git_path, 'refs');
	}

	function get_refs($git_path) {
		$list = array_merge($this->get_loose_refs($git_path), $this->get_packed_refs($git_path));
		usort($list, 'ref_entry_cmp');
		return $list;
	}

	function get_info_refs($git_path, $name) {
		$this->header_nocache();
		header('Content-Type: text/plain');

		/* TODO Are dereferenced tags needed in this
		   list, or just a convenience? */

		foreach ($this->get_refs($git_path) as $ref) {
			echo $ref[1]."\t".$ref[0]."\n";
		}
	}

	function get_info_packs($git_path, $name) {
		$this->header_nocache();
		header('Content-Type: text/plain; charset=utf-8');

		$pack_dir = $git_path.'/objects/pack'; 
		$dir = dir($pack_dir);

		while (($entry = $dir->read()) !== FALSE) {
			if (str_endswith($entry, '.idx')) {
				$name = substr($entry, 0, -4);
				if (is_file($pack_dir.'/'.$name.'.pack')) {
					echo 'P '.$name.'.pack'."\n";
				}
			}
		}
	}
}
