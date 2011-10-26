<?php if (!defined('ALLOW_PAGSEGURO_CONFIG')) { die('No direct script access allowed'); }
/*
************************************************************************
PagSeguro Config File
************************************************************************
*/

$PagSeguroConfig = array();

$PagSeguroConfig['environment'] = Array();
$PagSeguroConfig['environment']['environment'] = "production";


$PagSeguroConfig['credentials'] = Array();
$PagSeguroConfig['credentials']['email'] = "your@email.com";
$PagSeguroConfig['credentials']['token'] = "your_token_here";

$PagSeguroConfig['application'] = Array();
$PagSeguroConfig['application']['charset'] = "ISO-8859-1"; // UTF-8, ISO-8859-1

$PagSeguroConfig['log'] = Array();
$PagSeguroConfig['log']['active'] = TRUE;
$PagSeguroConfig['log']['fileLocation'] = "";

//get pagseguro config for kohana directory
$PagSeguroConfig = Kohana::config('pagseguro.data');


?>