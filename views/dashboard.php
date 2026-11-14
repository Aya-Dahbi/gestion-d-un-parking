<?php 
if (!defined('APP')) {header('Location: ../index.php'); exit();}?>

<?php 

$nom = $_SESSION ['nom'];
$prenom = ucfirst(explode(' ',$nom)[0]);
$lettre = strtoupper(substr($nom,0,1)); 

?>
<!DOCTYPE html>
<html lang ="fr">
    <head>
        <meta charset ="UTF-8">
        <meta name ="viewport" content ="width=device-width , initial-scale=1.0">
        <title>SpotPark - Parking</title>
    
       <link rel="stylesheet" href ="views/dashboard.css">
    </head>
    <body>
        <header class= "topbar">
            <div class ="logo">
                <span class ="logo-box">P</span>
                <span>SpotPark</span>
            </div>
            <nav class="menu">
                <a href="index.php?page=dashboard" class= "active" > Parking </a>
                <a href="index.php?page=reservations" class= "active" > Mes reservations </a>
            </nav>
            <div class ="profil">
                <div class ="avatar"><?= htmlspecialchars($lettre) ?></div>
                <div class ="profil-texte">
                    <strong><?= htmlspecialchars($nom) ?></strong>
                    <a href="index.php?page=logout">Se deconnecter</a>
                </div>
            </div>
        </header>
           
        <main class="page">
            <div class ="page-entete">
                <div>
                    <p class ="surtitre">Parking Centre-Ville</p>
                    <h1>Bonjour <?= htmlspecialchars($prenom) ?>, où souhaitez-vous stationner</h1>
                    <p class= "sous-titre">Disponibilités mises à jour en temps réel. </p>
                </div>
                <input type ="search" id="recherche" class="recherche" placeholder="Rechercher une place...">
            </div>
             <section id="bandeau" class="bandeau cache">
 
           
            <div class="bandeau-gauche">
                <div class="bandeau-icone">P</div>
                <div>
                    <p class="bandeau-statut">
                        <span class="point-vert"></span>
                        <span id="bandeau-statut">Réservation en cours</span>
                    </p>
                    <h3 id="bandeau-place">Place —</h3>
                    <p class="sous-titre" id="bandeau-horaires"></p>
                </div>
            </div>
 
           
            <div class="bandeau-temps">
                <p class="sous-titre" id="bandeau-libelle">Temps restant</p>
                <p class="compte-rebours" id="compte-rebours">00:00:00</p>
            </div>
 
            
            <div class="bandeau-boutons">
                <button type="button" id="btn-annuler" class="btn-secondaire">Annuler la réservation</button>
                <button type="button" id="btn-liberer" class="btn-principal btn-auto">Libérer maintenant →</button>
            </div>
        </section>
 
            <div class ="contenu">

                <section class="carte">
                    <div class ="carte-entete">
                    <div>
                        <p class="surtitre">Plan en temps reel </p>
                        <h2>Choisissez votre place</h2>
                    </div>
                    <ul class ="legende">
                        <li ><span class ="pastille libre"></span></li>
                        <li ><span class ="pastille occupee"></span></li>
                        <li ><span class ="pastille selectionnee"></span></li>
                    </ul>
                    </div>

                 <div class ="parking">
                    <div class ="parking-indications">
                        <span> <-Entree</span>
                        <span> Sortie -></span>
                    </div>

                    <div class ="rangee" id="rangee-A">
                       
                    </div>

                    <div class ="allee">
                        <span>Zone A</span>
                        <span>Allée centrale</span>
                        <span>Zone B</span>
                    </div>

                    <div class ="rangee" id="rangee-B">
                       
                    </div>
                 </div>
                  
                 <div class ="carte-pied">
                 <span>
                    <span class ="point-vert"></span>
                    <span id="derniere-maj">Capteurs actualisés il y a quelques secondes</span>
                 </span>
                    <button type ="button" id="btn-actualiser" class ="lien-bouton">Actualiser</button>
                 </div>
                </section>

                 <aside class ="carte panneau">
                    <div class="carte-entete">
                        <p class="surtitre">Place sélectionnée</p>
                        <span class="badge" id="badge-etat">Libre maintenant</span>
                    </div>
                    <div class ="place-choisie">
                    <div class ="place-icone" id="place-icone">-</div>

                   <div>
                    <h3 id="place-nom"> Aucune place</h3>
                    <p class="sous-titre">Cliquez sur une place verte</p>
                   </div>

                    </div>

                    <hr>
                    <h4>Planifier votre stationnement</h4>

                    <form id="form-reservation">
                        <input type ="hidden" id="id_place" name="id_place">

                      <label for="date">Date</label>
                      <input type ="date" id="date" name="date" required >
                      <div class ="horaires">
                        <div>
                            <label for="debut">Début</label>
                            <input type ="time" id="debut" name ="debut" required>

                        </div>
                        <span class="fleche">-></span>
                        <div>
                            <label for ="fin">Fin</label>
                            <input  type="time" id ="fin" name ="fin" required>
                              
                        </div>
                      </div>
                      <div class="ligne-info">
                        <span>Durée estimée</span>
                        <strong id="duree">—</strong>
                    </div>
                      <div class ="ligne-info ligne-total">
                        <span>Total</span>
                        <strong id="total">0,00 DH</strong>
                      </div>

                      <div id="message-reservation" class="message"></div>
                       <button type="submit" class="btn-principal" id="btn-reserver" disabled>Réserver cette place →</button>
                        <p class="note">Annulation gratuite avant le début de la réservation</p>
                    </form>
                 </aside>

            </div>
        </main>
          

    
    <div id="zone-notifications"></div>
          <script src ="views/dashboard.js"></script>
    </body>
</html>
