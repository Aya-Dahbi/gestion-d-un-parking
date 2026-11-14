<?php 

require_once __DIR__ .'/db.php';


function mettreAjourEtats(){

$db = getConnexion();

$db->exec(
    "UPDATE place SET etat = 
    CASE 
    WHEN EXISTS(
    SELECT 1 FROM reservation r WHERE
    r.id_place = place.id_place
    AND r.heure_debut <= NOW()
     AND r.heure_FIN  > NOW()
) THEN 'occupee'
ELSE 'libre'
END"

);

}


function getPlaces(){

mettreAjourEtats();

$db = getConnexion();

$stmt = $db->query("SELECT id_place, numero_place, etat FROM place ORDER BY numero_place");


return $stmt -> fetchAll(PDO:: FETCH_ASSOC);


}




?>