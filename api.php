<?php 

session_start();

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ .'/modeles/places.php';
require_once __DIR__ .'/modeles/reservation.php';


if(!isset($_SESSION['id_utilisateur'])){

http_response_code(401);

echo json_encode(['erreur' => 'Vous devez être connecté.']);
    exit();
}

$action =$_GET['action'] ?? '';

switch($action){

case'places':
    $places = getPlaces();

    echo json_encode($places);

    break;

    

case 'reserver':

$id_place = (int) ($_POST['id_place'] ?? 0);

$date = $_POST['date'] ?? '';
$hDebut = $_POST['debut'] ?? '';
$hFin = $_POST['fin'] ?? '';

$debut = date('Y-m-d H:i:s' , strtotime($date . ' ' . $hDebut));
$fin = date('Y-m-d H:i:s' , strtotime($date . ' ' . $hFin));
if (strtotime($debut) < time()) {
    $debut = date('Y-m-d H:i:s');
    
}

if($id_place <= 0 || $date === '' || $hDebut === '' || $hFin === '' ){

$reponse = ['succes' => false, 'message' => "Choisissez une place, une date et des heures."];

} else if (strtotime($fin) <= strtotime ($debut)){

 $reponse = ['succes' => false, 'message' => "L'heure de fin doit être après l'heure de début."];

}else if (strtotime($fin) <= time()){
$reponse = ['succes' => false, 'message' => "Ce créneau est déjà passé."];
}elseif (!creneauDisponible($id_place, $debut, $fin)) {
            $reponse = ['succes' => false, 'message' => "Désolé, cette place est déjà réservée sur ce créneau."];
}else {
            
            ajouterReservation($_SESSION['id_utilisateur'], $id_place, $debut, $fin);
          
            $reponse = ['succes' => true,
         'message' => "Réservation confirmée de " . date('H:i', strtotime($debut))
          . " à " . date('H:i', strtotime($fin)) ];
 }
  echo json_encode($reponse);
       
        break;

 case 'ma_reservation':
 
        $res = getReservationActive($_SESSION['id_utilisateur']);
       
 
        if ($res) {
            
            $res['secondes_avant_debut'] = strtotime($res['heure_debut']) - time();
            $res['secondes_avant_fin']   = strtotime($res['heure_fin'])   - time();
            
 
            $res['en_cours'] = ($res['secondes_avant_debut'] <= 0);
           
        }
 
        echo json_encode($res ? $res : null);
       
        break;

   case 'annuler':
 
        $id_reservation = (int) ($_POST['id_reservation'] ?? 0);
        
 
        if (annulerReservation($id_reservation, $_SESSION['id_utilisateur'])) {
           
            $reponse = ['succes' => true,  'message' => "Réservation annulée. La place est de nouveau libre."];
        } else {
            $reponse = ['succes' => false, 'message' => "Impossible d'annuler : la réservation a déjà commencé."];
        }
 
        echo json_encode($reponse);
       
        break;


 case 'liberer':
 
        $id_reservation = (int) ($_POST['id_reservation'] ?? 0);
 
        if (libererReservation($id_reservation, $_SESSION['id_utilisateur'])) {
            $reponse = ['succes' => true,  'message' => "Place libérée. Merci et bonne route ! "];
        } else {
            $reponse = ['succes' => false, 'message' => "Impossible de libérer : la réservation n'est pas en cours."];
        }
 
        echo json_encode($reponse);
        break;


        case 'mes_reservations':
 
       
        $liste = getMesReservations($_SESSION['id_utilisateur']);
       
 
        for ($i = 0; $i < count($liste); $i++) {
      
 
            $debut = strtotime($liste[$i]['heure_debut']);   
            $fin   = strtotime($liste[$i]['heure_fin']);
 
            if ($debut > time()) {
                $liste[$i]['statut'] = 'a_venir';     
            } elseif ($fin > time()) {
                $liste[$i]['statut'] = 'en_cours';    
            } else {
                $liste[$i]['statut'] = 'terminee';    
            }
 
           
            $liste[$i]['duree_minutes'] = round(($fin - $debut) / 60);
            
        }
 
        echo json_encode($liste);
        
        break;
 


    case 'nouveautes':
 
        
        $depuis = (int) ($_GET['depuis'] ?? 0);
        
 
        
        $dernierId = getDernierIdReservation();
        
 
        if ($depuis == 0) {
            $nouvelles = [];
            
        } else {
            $nouvelles = getNouvellesReservations($_SESSION['id_utilisateur'], $depuis, $dernierId);
            
        }
 
        
        echo json_encode(['dernier_id' => $dernierId, 'nouvelles' => $nouvelles]);
       
        break;
    default:
        http_response_code(400);

        echo json_encode(['erreur' => 'Action inconnue.']);
}



 
   


?>