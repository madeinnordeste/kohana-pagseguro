<?php if (!defined('PAGSEGURO_LIBRARY')) { die('No direct script access allowed'); }
/*
************************************************************************
Copyright [2011] [PagSeguro Internet Ltda.]

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
************************************************************************
*/

/*
* Library autoloader - spl_autoload_register
*/
class PagSeguroAutoloader {

	public static $loader;
	
	private function __construct() {
		spl_autoload_register(array($this, 'domain'));
		spl_autoload_register(array($this, 'exception'));
		spl_autoload_register(array($this, 'parser'));
		spl_autoload_register(array($this, 'service'));
		spl_autoload_register(array($this, 'utils'));
		spl_autoload_register(array($this, 'helper'));
		spl_autoload_register(array($this, 'config'));
		spl_autoload_register(array($this, 'resources'));
		spl_autoload_register(array($this, 'log'));
	}

	public static function init() {
		if (self::$loader == null) {
			self::$loader = new PagSeguroAutoloader();
		}
		return self::$loader;
	}	
	
	private function addClass($dir, $class){
		$file = PagSeguroLibrary::getPath().DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$class.'.class.php';
		if (file_exists($file) && is_file($file)) {
			require_once $file;
		}
	}
	
	public function config($class) {
		$this->addClass('config', $class);
	}
	public function resources($class) {
		$this->addClass('resources', $class);
	}
	public function log($class) {
		$this->addClass('log', $class);
	}
	public function domain($class) {
		$this->addClass('domain', $class);
	}
	public function exception($class) {
		$this->addClass('exception', $class);
	}
	public function parser($class) {
		$this->addClass('parser', $class);
	}
	public function service($class) {
		$this->addClass('service', $class);
	}
	public function utils($class) {
		$this->addClass('utils', $class);
	}
	public function helper($class) {
		$this->addClass('helper', $class);
	}	

}
?>