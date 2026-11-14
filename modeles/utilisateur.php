<?php
require_once __DIR__ . '/db.php';

function inscrireutilisateur($nom,$email,$mot_de_passe){
$db=getConnexion();
$smt =$db ->prepare("SELECT id_utilisateur From utilisateur where email=?");
$smt->execute([$email]);
if($smt->fetch()){
    return "Erreur : cette email exit deja ";
}


$hashedPassword = password_hash($mot_de_passe,PASSWORD_DEFAULT);


$insert= $db->prepare("INSERT INTO utilisateur (nom,email,mot_de_passe) VALUES(?,?,?)");
if($insert->execute([$nom,$email,$hashedPassword])){
    return "Inscription réussie ! Vous pouvez vous connecter.";

}else{
    return "Une erreur est survenue lors de l'inscription.";
}

}

function connecterutilisateur($email,$mot_de_passe){
  $db=getConnexion();
$stmt =$db ->prepare("SELECT id_utilisateur,nom ,email, mot_de_passe From utilisateur where email=?");
$stmt->execute([$email]);  
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur  && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
         unset($utilisateur['mot_de_passe']);
        return $utilisateur;
    } else {
        return false; 
    }
}


function enregistrerToken($id_utilisateur,$token){
$db = getConnexion();
$hash = ($token === null ) ? null : hash('sha256', $token);
$stmt = $db ->prepare("UPDATE utilisateur SET token = ? WHERE id_utilisateur = ?");
$stmt -> execute ([$hash,$id_utilisateur]);

}


function trouverParToken($token){

$db = getConnexion();
$stmt = $db->prepare("SELECT id_utilisateur ,nom,email FROM utilisateur WHERE token= ?");
$stmt->execute([hash('sha256', $token )]);
return $stmt->fetch(PDO :: FETCH_ASSOC);


}








?>