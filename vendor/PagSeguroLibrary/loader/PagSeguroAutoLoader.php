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
* Library autoloader - __autoload
*/
if (class_exists('PagSeguroLibrary')) {
	function __autoload($class) {
		
		$dirs = array(
			'domain',
			'exception',
			'parser',
			'service',
			'utils',
			'helper',
			'config',
			'resources',
			'log'
		);
		
		foreach ($dirs as $d) {
			$file = PagSeguroLibrary::getPath().DIRECTORY_SEPARATOR.$d.DIRECTORY_SEPARATOR.$class.'.class.php';
			if (file_exists($file) && is_file($file)) {
				require_once($file);
				return true;
			}
		}
		
		return false;
		
	}
}
?>