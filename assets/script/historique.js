/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/

function get_connexion(matricule) {
  /* Mise en forme des données */
  const data = new FormData();
  data.append('matricule_cop', matricule);

  /* Envoi de la requêtes */
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/administration/historique_connexion/get_info');
  requeteAjax.send(data);

  /* On récupère les informations de la requêtes */
  requeteAjax.onload = function() {
    const resultat = JSON.parse(requeteAjax.responseText);

    if (resultat.etat == 0) {
      protocole_0_historique(resultat);
    }
    else {
      protocole_affichage_historique(resultat);
    }
  }
}

function protocole_0_historique(resultat) {
  // On indique le nom de la pesonne ou de l'agent
  document.getElementById("agent_nom3").innerHTML = "- " + resultat.nom;
  document.getElementById("contenu_historique2").innerHTML = `<p class="vide">Aucune action effectuée</p>`;
}

function protocole_affichage_historique(resultat) {
  document.getElementById("agent_nom3").innerHTML = "- " + resultat[(resultat.length-1)].etat;

  let content = [];

  for (var i = 0; i < resultat.length - 1; i++) {
    content[i] = `
    <div id="${i}">
      <p class="date">${resultat[i].date}</p>
      <p>${resultat[i].content}</p>
    </div>`
  }

  document.getElementById("contenu_historique2").innerHTML = content.join(' ');
}

function get_historique(matricule) {
  /* Mise en forme des données */
  const data = new FormData();
  data.append('matricule_cop', matricule);

  /* Envoi de la requêtes */
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/administration/historique/get_info');
  requeteAjax.send(data);

  /* On récupère les informations de la requêtes */
  requeteAjax.onload = function() {
    const resultat = JSON.parse(requeteAjax.responseText);

    if (resultat.etat == 0) {
      protocole_0_action(resultat);
    }
    else {
      protocole_affichage_action(resultat);
    }
  }
}

function protocole_0_action(resultat) {
  // On indique le nom de la pesonne ou de l'agent
  document.getElementById("agent_nom").innerHTML = "- " + resultat.nom;
  document.getElementById("agent_nom2").innerHTML = "- " + resultat.nom;
  document.getElementById("contenu_historique").innerHTML = `<p class="vide">Aucune action effectuée</p>`;
}

function protocole_affichage_action(resultat) {
  document.getElementById("agent_nom").innerHTML = "- " + resultat[(resultat.length-1)].etat;
  document.getElementById("agent_nom2").innerHTML = "- " + resultat[(resultat.length-1)].etat;

  let content = [];

  for (var i = 0; i < resultat.length - 1; i++) {
    content[i] = `
    <div id="${i}">
      <p class="date">${resultat[i].date}</p>
      <p>${resultat[i].event}</p>
    </div>`
  }

  document.getElementById("contenu_historique").innerHTML = content.join(' ');
}

function get_statistique(matricule) {
  /* Mise en forme des données */
  const data = new FormData();
  data.append('matricule_cop', matricule);

  /* Envoi de la requêtes */
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/administration/historique/get_stats');
  requeteAjax.send(data);

  /* On récupère les informations de la requêtes */
  requeteAjax.onload = function() {
    const resultat = JSON.parse(requeteAjax.responseText);
    if (resultat.etat == 1) {
      document.getElementById("stats_null").style.display = "none";
      document.getElementById("stat_full").style.display = "grid";
      document.getElementById("nb_connect").innerHTML = resultat.login;
      document.getElementById("nb_action").innerHTML = resultat.action;
      document.getElementById("nb_open").innerHTML = resultat.open;
      document.getElementById("nb_close").innerHTML = resultat.close;
      document.getElementById("nb_car").innerHTML = resultat.car;
    }
  }
}

function protocoleHistorique(matricule) {
  document.location.href="#agent_nom"; // Retour en haut de page
  get_historique(matricule);
  get_statistique(matricule);
  get_connexion(matricule);
}

// On masque la partie stats
document.getElementById("stat_full").style.display = "none";
