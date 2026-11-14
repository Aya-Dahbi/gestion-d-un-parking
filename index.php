<?php

session_start();
define('APP',true);

require_once  __DIR__ . '/controllers/authControleur.php';

verifierCookiesSouvenir();

$page = $_GET['page'] ?? 'login';

$pagesProtegees = ['dashboard', 'reservations'];

if(in_array($page ,$pagesProtegees) && !estConnecte()){

header('Location: index.php?page=login');
exit();
}

switch ($page){

case 'login':
    if(estConnecte()){
      header('Location: index.php?page=dashboard');
            exit();   
    }
    traiterLogin();
    break;

    case 'logout':
        deconnecter();
        break;

    case 'dashboard' :

       require __DIR__ . '/views/dashboard.php';
        break;
        
    case 'reservations':
        require __DIR__ . '/views/reservations.php';
        
        break;
      default :
      
      http_response_code(404);
       
      require __DIR__ . '/views/404.php';

}
?>