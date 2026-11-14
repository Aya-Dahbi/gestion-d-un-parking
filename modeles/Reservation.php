<?php 

require_once __DIR__ . '/db.php';



function creneauDisponible($id_place,$debut,$fin){

$db = getConnexion();

$stmt = $db->prepare(
    "SELECT COUNT(*) FROM RESERVATION WHERE id_place = ?
    AND heure_debut <?
     AND heure_fin > ? "
);


$stmt->execute([$id_place,$fin,$debut]);

return $stmt->fetchColumn() ==0;
}


function ajouterReservation($id_utilisateur,$id_place ,$debut,$fin){


$db = getConnexion();

$stmt= $db->prepare("INSERT INTO reservation (id_utilisateur,id_place,heure_debut,heure_fin) VALUES (?,?,?,?)");


$stmt->execute([$id_utilisateur,$id_place,$debut,$fin]);


}


function getReservationActive($id_utilisateur){
    $db = getConnexion();
    $stmt = $db->prepare(
        "SELECT r.id_reservation,r.id_place,p.numero_place,r.heure_debut,r.heure_fin
        FROM reservation r JOIN place p ON p.id_place = r.id_place
        WHERE r.id_utilisateur = ?
        AND r.heure_fin > NOW()
        ORDER BY r.heure_debut
        LIMIT 1"
    );

    $stmt->execute([ $id_utilisateur]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function annulerReservation($id_reservation,$id_utilisateur){
    $db = getConnexion();
    $stmt = $db->prepare(
        "DELETE FROM reservation
        WHERE id_reservation = ?
        AND id_utilisateur = ?
        AND heure_debut > NOW()"
    );
    
    $stmt->execute([$id_reservation,$id_utilisateur]);
    return $stmt->rowCount() > 0;
}

function libererReservation($id_reservation,$id_utilisateur){
    $db = getConnexion();
    $stmt = $db->prepare(
          "UPDATE reservation SET heure_fin = NOW()
         WHERE id_reservation = ?
           AND id_utilisateur = ?
           AND heure_debut <= NOW()
           AND heure_fin   >  NOW()"
    );

    $stmt->execute([$id_reservation,$id_utilisateur]);
    return $stmt->rowCount() > 0;
}



function getMesReservations($id_utilisateur) {
 
    $db = getConnexion();
 
    $stmt = $db->prepare(
        "SELECT r.id_reservation, p.numero_place, r.heure_debut, r.heure_fin
         FROM reservation r
         JOIN place p ON p.id_place = r.id_place
         WHERE r.id_utilisateur = ?
         ORDER BY (r.heure_fin <= NOW()),
                  CASE WHEN r.heure_fin > NOW() THEN r.heure_debut END,
                  r.heure_debut DESC"
    );
    
 
    $stmt->execute([$id_utilisateur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
}


 
 

function getDernierIdReservation() {
 
    $db = getConnexion();
 
    $stmt = $db->query("SELECT COALESCE(MAX(id_reservation), 0) FROM reservation");
    
 
    return (int) $stmt->fetchColumn();
    
}
 
 

function getNouvellesReservations($id_utilisateur, $depuis, $jusqua) {
 
    $db = getConnexion();
 
    $stmt = $db->prepare(
        "SELECT r.id_reservation, p.numero_place, r.heure_debut, r.heure_fin
         FROM reservation r
         JOIN place p ON p.id_place = r.id_place
         WHERE r.id_reservation > ?
           AND r.id_reservation <= ?
           AND r.id_utilisateur <> ?
         ORDER BY r.id_reservation"
    );
    
 
    $stmt->execute([$depuis, $jusqua, $id_utilisateur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>