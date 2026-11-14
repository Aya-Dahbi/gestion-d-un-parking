<?php
date_default_timezone_set('UTC');
function getConnexion(){
$host='localhost';
$dbname='parking_db';
$username='root';
$password='';

try{

$db= new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$username,$password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$db->exec("SET time_zone = '" . date('P') . "'");
return $db;

}catch(PDOException $e){
    die("Erreur de connexion : " . $e->getMessage());
}

}

?>