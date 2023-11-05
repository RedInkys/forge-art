<?php 
$pdo = new PDO('mysql:host=localhost;dbname=site-forgeart','root','', [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);

session_start();

define('RACINE_SITE', $_SERVER['DOCUMENT_ROOT'].'/PHP-cours/projet-perso/');

define('URL', 'http://localhost/PHP-cours/projet-perso/');

$content ='';

require_once('fonction.php');

// debug($pdo,1);

?>