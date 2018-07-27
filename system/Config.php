<?php 

/*
* @author Luis Eduardo <luisdesenvolvedor@gmail.com>
* Arquivo de configurações gerais	
*/

/* Ambiente de desenvolvimento atual. */
define("ENVIRONMENT", 'develop');

/* URL base do projeto */
$base_url = "http://" . $_SERVER['HTTP_HOST'];
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
define('BASE_URL', $base_url);

/* Configurações globais */
global $config;
$config = array();

if(ENVIRONMENT == 'develop'){
	$config['db_name'] = 'your_database';
	$config['db_user'] = 'root';
	$config['db_pass'] = '';
	$config['db_host'] = 'localhost';
} else {
	$config['db_name'] = 'your_database';
	$config['db_user'] = 'root';
	$config['db_pass'] = '';
	$config['db_host'] = 'localhost';
}

/* Conexão global ao banco de dados */
global $db;
try {
	$db = new PDO("mysql:host=".$config['db_host']."; dbname=".$config['db_name'], $config['db_user'], $config['db_pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	exit("ERRO: ".$e->getMessage());
}
