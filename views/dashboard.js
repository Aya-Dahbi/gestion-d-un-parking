var placeSelectionnee = null;                       
var INTERVALLE = 5000;
var TARIF_HORAIRE = 3;
var maReservation = null;
var secondesRestantes = 0;
var dernierIdConnu = 0;

function creationXHR() {
    return new XMLHttpRequest();
}

function chargerPlaces() {                          
    var objetXHR = creationXHR();
    objetXHR.open("GET", "api.php?action=places", true);

    objetXHR.onreadystatechange = function () {
        if (objetXHR.readyState == 4) {
            if (objetXHR.status == 200) {
                var places = JSON.parse(objetXHR.responseText);
                afficherPlaces(places);
                afficherHeureMiseAJour();
            } else if (objetXHR.status == 401) {
                window.location.href = "index.php?page=login";
            } else {
                console.log("Erreur HTTP n° " + objetXHR.status);
            }
        }
    };

    objetXHR.send(null);
}

function afficherPlaces(places) {
    var rangeeA = document.getElementById("rangee-A");
    var rangeeB = document.getElementById("rangee-B");
    rangeeA.innerHTML = "";
    rangeeB.innerHTML = "";

    for (var i = 0; i < places.length; i++) {
        var place = places[i];

        if (place.id_place == placeSelectionnee && place.etat == "occupee") {   
            deselectionner();
        }

        var div = creerCasePlace(place);

        if (place.numero_place.charAt(0) == "A") {
            rangeeA.appendChild(div);
        } else {
            rangeeB.appendChild(div);
        }
    }
}

function creerCasePlace(place) {
    var div = document.createElement("div");

    if (place.id_place == placeSelectionnee) {
        div.className = "place selectionnee";       
    } else if (place.etat == "occupee") {
        div.className = "place occupee";
    } else {
        div.className = "place libre";
    }

    if (place.etat == "occupee") {                  
        div.innerHTML = "<span>" + place.numero_place + "</span>";
    } else {
        div.textContent = place.numero_place;
    }

    if (place.etat == "libre") {
        div.onclick = function () {
            selectionnerPlace(place);               
        };
    }

    return div;
}

function selectionnerPlace(place) {                 
    placeSelectionnee = place.id_place;             
    document.getElementById("id_place").value = place.id_place;
    document.getElementById("place-nom").textContent = "Place " + place.numero_place;   
    document.getElementById("place-icone").textContent = place.numero_place;
    document.getElementById("btn-reserver").disabled = false;
    chargerPlaces();
}

function deselectionner() {
    placeSelectionnee = null;                       
    document.getElementById("id_place").value = "";
    document.getElementById("place-nom").textContent = "Aucune place";
    document.getElementById("place-icone").textContent = "–";
    document.getElementById("btn-reserver").disabled = true;
}

function afficherHeureMiseAJour() {
    var maintenant = new Date();
    document.getElementById("derniere-maj").textContent =
        "Actualisé à " + maintenant.toLocaleTimeString("fr-FR");   
}



function enMinutes(heure){
    var morceaux = heure.split(":");

    return parseInt(morceaux[0]) * 60 + parseInt(morceaux[1]);
}

function calculerPrix(){


var debut = document.getElementById("debut").value;
var fin = document.getElementById("fin").value;


var zoneDuree = document.getElementById("duree");
var zoneTotal= document.getElementById("total");


if (debut == "" || fin == ""){

    zoneDuree.textContent = "-";
    zoneTotal.textContent = "0,00 DH";
    return;
}

var minutes = enMinutes(fin) - enMinutes(debut);

if(minutes <=0){
    zoneDuree.textContent = "Fin avant le début !";
    zoneTotal.textContent = "0,00 DH";
    return;
}



var heures = Math.floor(minutes / 60);
var reste = minutes %60;
zoneDuree.textContent = heures + "h" + reste + "min";

var prix = (minutes/60) * TARIF_HORAIRE;
zoneTotal.textContent = prix.toFixed(2).replace(".", ".") + "DH";
}

function dateDuJour(){
    var d = new Date();
    var mois = d.getMonth()+1;
    var jour = d.getDate();
    if (mois< 10 ){
        mois = "0"+mois;
    }
    if(jour <10 ){
        jour = "0" +jour;
    }

    return d.getFullYear() + "-" + mois +"-" +jour ;
}


function reserverPlace(event){
    event.preventDefault();
    var donnees =
        "id_place=" + encodeURIComponent(document.getElementById("id_place").value) +
        "&date="    + encodeURIComponent(document.getElementById("date").value) +
        "&debut="   + encodeURIComponent(document.getElementById("debut").value) +
        "&fin="     + encodeURIComponent(document.getElementById("fin").value);



   var objetXHR = creationXHR();

   objetXHR.open("POST", "api.php?action=reserver", true);

objetXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  objetXHR.onreadystatechange = function () {
        if (objetXHR.readyState == 4) {
 
            if (objetXHR.status == 200) {
                var reponse = JSON.parse(objetXHR.responseText);
              
                afficherMessage(reponse.message, reponse.succes);
                
                if (reponse.succes) {
                    deselectionner();
                    chargerMaReservation();    
                }
 
            } else if (objetXHR.status == 401) {
                window.location.href = "index.php?page=login";
 
            } else {
                afficherMessage("Erreur du serveur (" + objetXHR.status + ")", false);
            }
        }
    };
 
   
    objetXHR.send(donnees);
   
}


function afficherMessage(texte, succes) {
    var zone = document.getElementById("message-reservation");
    zone.textContent = texte;
 
    if (succes) {
        zone.className = "message succes";   
    } else {
        zone.className = "message erreur";   
    }
}




function chargerMaReservation() {
 
    var objetXHR = creationXHR();                                   
    objetXHR.open("GET", "api.php?action=ma_reservation", true);   
    objetXHR.onreadystatechange = function () {
        if (objetXHR.readyState == 4 && objetXHR.status == 200) {  
 
            maReservation = JSON.parse(objetXHR.responseText);
            
 
            afficherBandeau();                                    
 
            chargerPlaces();
           
        }
    };
 
    objetXHR.send(null);                                             
}
 
 

function afficherBandeau() {
 
    var bandeau = document.getElementById("bandeau");
 
    if (maReservation == null) {
        bandeau.className = "bandeau cache";
        
        return;
    }
 
    bandeau.className = "bandeau";
   
 
    document.getElementById("bandeau-place").textContent = "Place " + maReservation.numero_place;
    document.getElementById("bandeau-horaires").textContent =
        "De " + heureCourte(maReservation.heure_debut) + " à " + heureCourte(maReservation.heure_fin);
 
    if (maReservation.en_cours) {
      
        document.getElementById("bandeau-statut").textContent  = "Réservation en cours";
        document.getElementById("bandeau-libelle").textContent = "Temps restant";
        secondesRestantes = maReservation.secondes_avant_fin;
        document.getElementById("btn-annuler").className = "btn-secondaire cache";   
        document.getElementById("btn-liberer").className = "btn-principal btn-auto"; 
    } else {
       
        document.getElementById("bandeau-statut").textContent  = "Prochaine réservation";
        document.getElementById("bandeau-libelle").textContent = "Commence dans";
        secondesRestantes = maReservation.secondes_avant_debut;
        document.getElementById("btn-annuler").className = "btn-secondaire";              
        document.getElementById("btn-liberer").className = "btn-principal btn-auto cache"; 
    }
 
    afficherCompteur();
}
 

function heureCourte(dateMysql) {
    return dateMysql.substring(11, 16);
    
}
 
 

function deuxChiffres(nombre) {
    if (nombre < 10) {
        return "0" + nombre;
    }
    return "" + nombre;   
}
 
 

function formaterDuree(secondes) {
    if (secondes < 0) { secondes = 0; }     
 
    var h = Math.floor(secondes / 3600);     
    var m = Math.floor((secondes % 3600) / 60);   
    var s = secondes % 60;                   
 
    return deuxChiffres(h) + ":" + deuxChiffres(m) + ":" + deuxChiffres(s);   
}
 
 

function afficherCompteur() {
    document.getElementById("compte-rebours").textContent = formaterDuree(secondesRestantes);
}
 
 

function tic() {
 
    if (maReservation == null) {
        return;   
    }
 
    secondesRestantes = secondesRestantes - 1;
 
    if (secondesRestantes <= 0) {
        
        chargerMaReservation();   
    }
 
    afficherCompteur();
}
 
 

function actionReservation(action) {
 
    if (maReservation == null) {
        return;
    }
 
    var question;
    if (action == "annuler") {
        question = "Voulez-vous vraiment annuler votre réservation ?";
    } else {
        question = "Voulez-vous libérer la place maintenant ?";
    }
 
    if (!confirm(question)) {
        return;
       
    }
 
    var objetXHR = creationXHR();                                         
    objetXHR.open("POST", "api.php?action=" + action, true);             
    
    objetXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
 
    objetXHR.onreadystatechange = function () {
        if (objetXHR.readyState == 4 && objetXHR.status == 200) {         
            var reponse = JSON.parse(objetXHR.responseText);
            afficherMessage(reponse.message, reponse.succes);           
            chargerMaReservation();
           
        }
    };
 
    objetXHR.send("id_reservation=" + encodeURIComponent(maReservation.id_reservation));
   
}
 

function verifierNouveautes() {
 
    var objetXHR = creationXHR();                                                  
    objetXHR.open("GET", "api.php?action=nouveautes&depuis=" + dernierIdConnu, true);  
    
 
    objetXHR.onreadystatechange = function () {
        if (objetXHR.readyState == 4 && objetXHR.status == 200) {                   
 
            var reponse = JSON.parse(objetXHR.responseText);
           
 
            for (var i = 0; i < reponse.nouvelles.length; i++) {
                var resa = reponse.nouvelles[i];
                afficherNotification(
                    "Nouvelle réservation",
                    "Place " + resa.numero_place + " · de " +
                    heureCourte(resa.heure_debut) + " à " + heureCourte(resa.heure_fin)
                );                                                                 
            }
 
            dernierIdConnu = reponse.dernier_id;
           
        }
    };
 
    objetXHR.send(null);                                                             
}
 
 

function afficherNotification(titre, texte) {
 
    var zone = document.getElementById("zone-notifications");
 
    
    var notif = document.createElement("div");
    notif.className = "notification";
 
    var icone = document.createElement("i");
    icone.className = "fa-solid fa-bell";       
 
    var contenu = document.createElement("div");
    var titreHTML = document.createElement("strong");
    titreHTML.textContent = titre;
    var texteHTML = document.createElement("p");
    texteHTML.textContent = texte;
    contenu.appendChild(titreHTML);
    contenu.appendChild(texteHTML);
 
    notif.appendChild(icone);
    notif.appendChild(contenu);
    
 
    zone.appendChild(notif);  
 
    notif.onclick = function () {
        fermerNotification(notif);
    };
 
   
    setTimeout(function () {
        fermerNotification(notif);
    }, 6000);
 
}
 
 

function fermerNotification(notif) {
    if (notif.parentNode != null) {
        notif.parentNode.removeChild(notif);
        
    }
}

window.onload = function () {

 
    chargerMaReservation();
    
 
    setInterval(chargerMaReservation, INTERVALLE);
   
 
    document.getElementById("btn-actualiser").onclick = chargerPlaces;
  
 
    document.getElementById("date").value = dateDuJour();
    document.getElementById("date").min   = dateDuJour();
   
 
    document.getElementById("debut").oninput = calculerPrix;
    document.getElementById("fin").oninput   = calculerPrix;
    
 
    document.getElementById("form-reservation").onsubmit = reserverPlace;
   
 
    calculerPrix();
    
 
    setInterval(tic, 1000);
   
 
    document.getElementById("btn-annuler").onclick = function () {
        actionReservation("annuler");
    };
    document.getElementById("btn-liberer").onclick = function () {
        actionReservation("liberer");
    };

  verifierNouveautes();
   
    setInterval(verifierNouveautes, INTERVALLE);
    
    
};