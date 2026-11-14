<?php
require_once __DIR__ . '/modeles/db.php';


echo "Fuseau de PHP : " . date_default_timezone_get() . "<br>";
echo "Heure de PHP  : " . date('H:i:s') . "<br>";

$db = getConnexion();
echo "Heure de MySQL : " . $db->query("SELECT NOW()")->fetchColumn();