<?php  
if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

/* Include ez-db library file  */
require_once('ez_sql_core.php');
require_once('ez_sql_mysqli.php');

/* Initialise database object and establish a connection
* at the same time - db_user / db_password / db_name / db_host */
$db = new ezSQL_mysqli($conn["username"], $conn["password"], $conn["dbname"], $conn["server"],   $conn["encoding"]);
