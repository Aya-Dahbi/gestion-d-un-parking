<?php


if (!defined('APP')) { header('Location: ../index.php'); exit(); }

$nom    = $_SESSION['nom'];
$lettre = strtoupper(substr($nom, 0, 1));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpotPark - Mes réservations</title>

   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="views/dashboard.css">

  
    <link rel="stylesheet" href="views/reservations.css">
</head>
<body>

  
    <header class="topbar">
        <div class="logo">
            <span class="logo-box">P</span>
            <span>ParkEasy</span>
        </div>

        <nav class="menu">
            <a href="index.php?page=dashboard">Parking</a>
            <a href="index.php?page=reservations" class="active">Mes réservations</a>
        </nav>

        <div class="profil">
            <div class="avatar"><?= htmlspecialchars($lettre) ?></div>
            <div class="profil-texte">
                <strong><?= htmlspecialchars($nom) ?></strong>
                <a href="index.php?page=logout">Se déconnecter</a>
            </div>
        </div>
    </header>

    <main class="page">

        <div class="page-entete">
            <div>
                <p class="surtitre">Votre espace</p>
                <h1>Mes réservations</h1>
                <p class="sous-titre">Retrouvez ici toutes vos réservations, récentes comme anciennes.</p>
            </div>
            
            <a href="index.php?page=dashboard" class="btn-principal btn-auto">
                <i class="fa-solid fa-plus"></i> Nouvelle réservation
            </a>
        </div>

        
        <div class="stats">
            <div class="carte stat">
                <div class="stat-icone bleu"><i class="fa-solid fa-square-parking"></i></div>
                <div>
                    <strong id="stat-total">0</strong>
                    <p class="sous-titre">Réservations au total</p>
                </div>
            </div>
            <div class="carte stat">
                <div class="stat-icone vert"><i class="fa-solid fa-circle-check"></i></div>
                <div>
                    <strong id="stat-terminees">0</strong>
                    <p class="sous-titre">Stationnements terminés</p>
                </div>
            </div>
            <div class="carte stat">
                <div class="stat-icone orange"><i class="fa-solid fa-clock"></i></div>
                <div>
                    <strong id="stat-temps">0 h 00</strong>
                    <p class="sous-titre">Temps stationné</p>
                </div>
            </div>
            <div class="carte stat">
                <div class="stat-icone violet"><i class="fa-solid fa-wallet"></i></div>
                <div>
                    <strong id="stat-depenses">0,00 DH</strong>
                    <p class="sous-titre">Dépenses cumulées</p>
                </div>
            </div>
        </div>

       
        <section class="carte">

            
            <div class="filtres-barre">
                <div class="filtres">
                   
                    <button type="button" class="filtre actif" data-filtre="toutes">
                        Toutes <span id="nb-toutes">0</span>
                    </button>
                    <button type="button" class="filtre" data-filtre="en_cours">
                        En cours <span id="nb-en_cours">0</span>
                    </button>
                    <button type="button" class="filtre" data-filtre="a_venir">
                        À venir <span id="nb-a_venir">0</span>
                    </button>
                    <button type="button" class="filtre" data-filtre="terminee">
                        Terminées <span id="nb-terminee">0</span>
                    </button>
                </div>

                <input type="search" id="recherche" class="recherche" placeholder="Rechercher une place…">
            </div>

           
            <div id="message-liste" class="message"></div>

        
            <div id="liste-reservations">
                <p class="vide">Chargement…</p>
            </div>
        </section>

    </main>

    <script src="views/reservations.js"></script>
</body>
</html>