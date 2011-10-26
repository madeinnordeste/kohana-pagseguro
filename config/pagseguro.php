<?php defined('SYSPATH') or die('No direct script access.');

/*
OBS: Se o arquivo de log nao existir e tiver permissão de escrita.
Um novo arquivo de log tentará ser criado no diretorio do modulo
*/

return array
(
    'data' => array(
	                'environment' => array('environment' => "production"),
	                'credentials' => array('email' => "SEUEMAIL",
	                                        'token' => "SEUTOKEN"),
	                'application' => array('charset' => "ISO-8859-1"),
	                'log' => array('active' => TRUE,
	                            'fileLocation' => APPPATH."logs/pagseguro/logs.php"),
	                )
);

