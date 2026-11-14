<?php 
require_once __DIR__ . '/../modeles/utilisateur.php';

function ouvrirSession($utilisateur){

session_regenerate_id(true);

$_SESSION['id_utilisateur'] = $utilisateur['id_utilisateur'];   
$_SESSION['nom'] = $utilisateur['nom'];
$_SESSION['email'] = $utilisateur['email'];

}

function estConnecte(){
return isset($_SESSION['id_utilisateur']);

}


function  verifierCookiesSouvenir() {

if (estConnecte()|| empty($_COOKIE['souvenir'])){

return;

}
$utilisateur = trouverParToken($_COOKIE['souvenir']);

if($utilisateur){

ouvrirSession($utilisateur);

}else{
    setcookie('souvenir','',time()-3600,'/');

}
}

function traiterLogin() {
    $message = "";
    $action  = $_POST['action'] ?? '';

    if ($action === 'inscription') {
        $nom   = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mdp   = $_POST['password'] ?? '';
        if ($nom !== '' && $email !== '' && $mdp !== '') {
            $message = inscrireUtilisateur($nom, $email, $mdp);
        } else {
            $message = "Veuillez remplir tous les champs pour l'inscription.";
        }
    }

    if ($action === 'connexion') {
        $email = trim($_POST['email'] ?? '');
        $mdp   = $_POST['password'] ?? '';
        $utilisateur = connecterUtilisateur($email, $mdp);

        if ($utilisateur) {
            ouvrirSession($utilisateur);

            if (!empty($_POST['remember'])) {           
                $token = bin2hex(random_bytes(32));     
                enregistrerToken($utilisateur['id_utilisateur'], $token);
                setcookie('souvenir', $token, [
                    'expires'  => time() + 30 * 24 * 3600, 
                    'path'     => '/',
                    'httponly' => true,    
                    'samesite' => 'Lax'
                ]);
            }
            header('Location: index.php?page=dashboard');
            exit();
        }
        $message = "E-mail ou mot de passe incorrect.";
    }

    require __DIR__ . '/../views/login.php';   // la vue voit la variable $message
}

function deconnecter() {
    if (estConnecte()) {
        enregistrerToken($_SESSION['id_utilisateur'], null);  
    }
    setcookie('souvenir', '', time() - 3600, '/');            
    $_SESSION = [];
    session_destroy();
    header('Location: index.php?page=login');
    exit();
}

?>