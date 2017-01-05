<?php
session_start();
function getPHPFilesFromDir($dir) {
	$files=array();
	$items = glob($dir . '/*');
	foreach ($items as $item) {
		if (is_dir($item)) {
			$subfiles=getPHPFilesFromDir($item);
			foreach ($subfiles as $subitem)
				$files[]=$subitem;
		} elseif (substr($item, strlen($item)-4,4)==".php")	{
			$files[]=$item;
		}
	}
	return $files;
}
$classes = getPHPFilesFromDir('php/classes');
foreach ($classes as $class) {
	include_once $class;
}

$mysqli = new mysqli('localhost', 'root', '123456', 'iptvdb');
$mysqli->set_charset("utf8");
$mysqli->query("SET time_zone = '+08:00'");
class Pager {
	public function DBQuery($sql) {
		global $mysqli;
		$result = $mysqli->query($sql);
		return $result;
	}
	public function DBFetch($result) {
		return mysqli_fetch_assoc($result);
	}
	public function handle() {
		$page = (isset($_GET['page'])?$_GET['page']:'');
		$parts = explode('/', $page);
		$class_name = (empty($parts[0])?'Main':$parts[0]);
		$func_name = (isset($parts[1]) && !empty($parts[1])?$parts[1]:'Home');
		$class = new $class_name();

		$params = array();
		$ref = new ReflectionClass($class_name);
		$ref_func=$ref->getMethod($func_name);
		foreach ($ref_func->getParameters() as $param) {
			if (isset($_REQUEST[$param->getName()])) {
				$params[$param->getName()]=$_REQUEST[$param->getName()];
			} elseif ($param->isOptional()) {
				$params[$param->getName()]=$param->getDefaultValue();
			} else {
				$params[$param->getName()]=null;
			}
		}

		$return = call_user_func_array(array(&$class,$func_name),$params);
		echo $return;
	}
	public function setUrl($class, $func, $params=null) {
		if ($params===null) $params = array();
		$url = 'index.php?page='.$class.'/'.$func;
		$qs = array();
		foreach ($params as $key => $val) {
			$qs[] = "{$key}=$val";
		}
		if (count($qs)>0) {
			$url .= '&'.implode('&', $qs);
		}
		return $url;
	}
	public function meUrl($func, $params=null) {
		$class = get_class($this);
		return $this->setUrl($class, $func, $params);
	}
}
?>