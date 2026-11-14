
var TARIF_HORAIRE = 3;


var toutesLesReservations = [];


var filtreActif = "toutes";


var MOIS = ["janv.", "févr.", "mars", "avr.", "mai", "juin",
            "juil.", "août", "sept.", "oct.", "nov.", "déc."];




function creationXHR() {
    return new XMLHttpRequest();
}



function chargerMesReservations() {

    var objetXHR = creationXHR();                                      
    objetXHR.open("GET", "api.php?action=mes_reservations", true);     

    objetXHR.onreadystatechange = function () {
        if (objetXHR.readyState == 4) {                               

            if (objetXHR.status == 200) {
                toutesLesReservations = JSON.parse(objetXHR.responseText);
               

                afficherStatistiques();                                
                afficherCompteurs();
                afficherListe();

            } else if (objetXHR.status == 401) {
                window.location.href = "index.php?page=login";
            }
        }
    };

    objetXHR.send(null);                                               
}




function prixDe(resa) {
    return (resa.duree_minutes / 60) * TARIF_HORAIRE;
}


function formaterPrix(prix) {
    return prix.toFixed(2).replace(".", ",") + " DH";
}


function deuxChiffres(nombre) {
    if (nombre < 10) {
        return "0" + nombre;
    }
    return "" + nombre;
}


function formaterDuree(minutes) {
    var h = Math.floor(minutes / 60);
    var m = minutes % 60;
    return h + " h " + deuxChiffres(m);
}


function heureCourte(dateMysql) {
    return dateMysql.substring(11, 16);
}


function dateDuJour() {
    var d = new Date();
    return d.getFullYear() + "-" + deuxChiffres(d.getMonth() + 1) + "-" + deuxChiffres(d.getDate());
}



function afficherStatistiques() {

    var nbTerminees = 0;
    var totalMinutes = 0;
    var totalDepenses = 0;
    

    for (var i = 0; i < toutesLesReservations.length; i++) {
        var resa = toutesLesReservations[i];

        if (resa.statut == "terminee") {
            nbTerminees = nbTerminees + 1;
        }

        if (resa.statut != "a_venir") {
           
            totalMinutes  = totalMinutes  + resa.duree_minutes;
            totalDepenses = totalDepenses + prixDe(resa);
        }
    }

    document.getElementById("stat-total").textContent     = toutesLesReservations.length;
    document.getElementById("stat-terminees").textContent = nbTerminees;
    document.getElementById("stat-temps").textContent     = formaterDuree(totalMinutes);
    document.getElementById("stat-depenses").textContent  = formaterPrix(totalDepenses);
}



function afficherCompteurs() {

    var nb = { toutes: 0, en_cours: 0, a_venir: 0, terminee: 0 };
   

    for (var i = 0; i < toutesLesReservations.length; i++) {
        var statut = toutesLesReservations[i].statut;  
        nb.toutes = nb.toutes + 1;
        nb[statut] = nb[statut] + 1;
        
    }

    document.getElementById("nb-toutes").textContent   = nb.toutes;
    document.getElementById("nb-en_cours").textContent = nb.en_cours;
    document.getElementById("nb-a_venir").textContent  = nb.a_venir;
    document.getElementById("nb-terminee").textContent = nb.terminee;
}



function afficherListe() {

    var liste = document.getElementById("liste-reservations");
    liste.innerHTML = "";   

    var texte = document.getElementById("recherche").value.trim().toUpperCase();
    

    var nbAffichees = 0;

    for (var i = 0; i < toutesLesReservations.length; i++) {
        var resa = toutesLesReservations[i];

        
        if (filtreActif != "toutes" && resa.statut != filtreActif) {
            continue;
            
        }

        
        if (texte != "" && resa.numero_place.indexOf(texte) == -1) {
            continue;
          
        }

        liste.appendChild(creerLigne(resa));   
        nbAffichees = nbAffichees + 1;
    }

    if (nbAffichees == 0) {
        liste.innerHTML = '<p class="vide">Aucune réservation à afficher.</p>';
    }
}



function creerLigne(resa) {

    var div = document.createElement("div");
    div.className = "ligne-resa " + resa.statut;
   
    var jour = resa.heure_debut.substring(8, 10);                    
    var mois = MOIS[parseInt(resa.heure_debut.substring(5, 7)) - 1];  
    var grosTexte = jour;
    if (resa.heure_debut.substring(0, 10) == dateDuJour()) {
        grosTexte = "AUJ.";   
    }

  
    var libelle;
    if (resa.statut == "en_cours") {
        libelle = "En cours";
    } else if (resa.statut == "a_venir") {
        libelle = "À venir";
    } else {
        libelle = "Terminée";
    }

    
    div.innerHTML =
        '<div class="resa-date"><strong>' + grosTexte + '</strong><span>' + jour + ' ' + mois + '</span></div>' +
        '<div class="resa-place">' + resa.numero_place + '</div>' +
        '<div class="resa-infos">' +
            '<h3>Place ' + resa.numero_place +
                ' <span class="badge-statut ' + resa.statut + '">' + libelle + '</span></h3>' +
            '<p class="sous-titre"><i class="fa-regular fa-clock"></i> ' +
                heureCourte(resa.heure_debut) + ' → ' + heureCourte(resa.heure_fin) + '</p>' +
        '</div>' +
        '<div class="resa-prix"><strong>' + formaterPrix(prixDe(resa)) + '</strong>' +
            '<span class="sous-titre">' + formaterDuree(resa.duree_minutes) + '</span></div>';
   
    var zoneAction = document.createElement("div");
    zoneAction.className = "resa-action";

    if (resa.statut == "en_cours") {
        var btnLiberer = document.createElement("button");
        btnLiberer.className = "btn-principal btn-auto";
        btnLiberer.textContent = "Libérer";
        btnLiberer.onclick = function () {
            actionReservation("liberer", resa.id_reservation);
        };
        zoneAction.appendChild(btnLiberer);

    } else if (resa.statut == "a_venir") {
        var btnAnnuler = document.createElement("button");
        btnAnnuler.className = "btn-secondaire";
        btnAnnuler.textContent = "Annuler";
        btnAnnuler.onclick = function () {
            actionReservation("annuler", resa.id_reservation);
        };
        zoneAction.appendChild(btnAnnuler);

    } else {
        
        var lien = document.createElement("a");
        lien.className = "btn-lien";
        lien.href = "index.php?page=dashboard";
        lien.textContent = "Réserver à nouveau";
        zoneAction.appendChild(lien);
    }

    div.appendChild(zoneAction);   
    return div;
}



function actionReservation(action, idReservation) {

    var question = "Voulez-vous libérer la place maintenant ?";
    if (action == "annuler") {
        question = "Voulez-vous vraiment annuler cette réservation ?";
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
            chargerMesReservations();   
        }
    };

    objetXHR.send("id_reservation=" + encodeURIComponent(idReservation));   
    
}


function afficherMessage(texte, succes) {
    var zone = document.getElementById("message-liste");
    zone.textContent = texte;
    if (succes) {
        zone.className = "message succes";
    } else {
        zone.className = "message erreur";
    }
}



function choisirFiltre(bouton) {

    filtreActif = bouton.dataset.filtre;
    

    var boutons = document.getElementsByClassName("filtre");
   
    for (var i = 0; i < boutons.length; i++) {
        boutons[i].className = "filtre";        
    }
    bouton.className = "filtre actif";            

    afficherListe();
   
}



window.onload = function () {

    chargerMesReservations();
    

    setInterval(chargerMesReservations, 10000);
    

    document.getElementById("recherche").oninput = afficherListe;
   

    var boutons = document.getElementsByClassName("filtre");
    for (var i = 0; i < boutons.length; i++) {
        boutons[i].onclick = function () {
            choisirFiltre(this);
           
        };
    }
    
};