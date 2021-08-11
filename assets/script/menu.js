/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/

/* On récupère la taille de l'écran */
if (document.body) {
  var larg = (document.body.clientWidth);
  var haut = (document.body.clientHeight);
}

/* Initialisation de la page */
if (larg < 1000) {
  /* Initialisation sur téléphone */
  document.getElementById("menu_navigation").style.display = "none";
  document.getElementById("croix").style.display = "none";
  document.getElementById("phone_menu").style.display = "none";
}
else {
  /* Initialisation sur Ordinateur */
  document.getElementById("phone_menu").style.display = "none";
}

function close_menu() {
  document.getElementById("phone_menu").style.display = "none";
  document.getElementById("croix").style.display = "none";
  document.getElementById("barre").style.display = "block";
}

function open_menu() {
  document.getElementById("phone_menu").style.display = "grid";
  document.getElementById("barre").style.display = "none";
  document.getElementById("croix").style.display = "block";
  maskSousMenu();
}

// open_menu();

function maskSousMenu()
{
  for (var i = 1; i < 10; i++) {
    if (document.getElementById(i)) { // On vérifie que la "famille" existe
      document.getElementById(i).style.display = "initial";
      for (var j = (i*10); j < (((i+1)*10)-1); j++) {
        if (document.getElementById(j)) { // On vérifie que l'élément existe
          document.getElementById(j).style.display = "none";
        }
      }
    }
  }
}

function afficheSousMenu(id) {
  /* On masque le menu principal */
  for (var i = 1; i < 10; i++) {
    if (document.getElementById(i)) { // On vérifie que la "famille" existe
      document.getElementById(i).style.display = "none";
    }
  }

  /* On Affiche le menu secondaire */
  for (var j = (id*10); j < (((id+1)*10)-1); j++) {
    if (document.getElementById(j)) {
      document.getElementById(j).style.display = "initial";
    }
  }
}

function redirectionButton(id, section)
{
  switch (id) {
    /* Bouton menu principal */
    case 7:
      document.location.href="/discussion-interne";
      break;
    case 8:
      document.location.href="/connexion";
      break;

    /* Menu Admin */
    case 10:
      document.location.href="/administration/ajout";
      break;
    case 11:
      document.location.href="/administration/modification";
      break;
    case 12:
      document.location.href="/administration/parametre-serveur";
      break;
    case 13:
      document.location.href="/administration/historique";
      break;

    /* Ecole */
    case 20:
      document.location.href="/ppa";
      break;
    case 21:
      document.location.href="/recrutement";
      break;
    case 22:
      document.location.href="/dossier-candidat?identifiant=";
      break;
    case 23:
      document.location.href="".concat("/", section, "?flic_name=&flic_firstname=&flic_matricule=");
      // document.location.href="" + String(section) + "?flic_name=&flic_firstname=&flic_matricule=";
      break;
    case 24:
      document.location.href="/rapport";
      break;

    /* Info */
    case 30:
      document.location.href="/";
      break;
    case 31:
      document.location.href="/formation";
      break;
    case 32:
      document.location.href="/documents";
      break;

    /* Registre */
    case 40:
      document.location.href="/recherche/civil?civil_name=&civil_firstname=&civil_phone=&civil_job=";
      break;
    case 41:
      document.location.href="/recherche/vehicule?modele=&plaque=&couleur=";
      break
    case 42:
      document.location.href="/recherche/plainte?civil_name=&civil_firstname=";
      break;

    /* Casier */
    case 50:
      document.location.href="/ajouter/casier";
      break;
    case 51:
      document.location.href="/ajouter/casier-routier";
      break;
    case 52:
      document.location.href="/ajouter/plainte";
      break;

    /* Ajouter */
    case 60:
      document.location.href="/ajouter/civil";
      break;
    case 61:
      document.location.href="/ajouter/vehicule";
      break;
    case 62:
      document.location.href="/rapport/add";
      break;
    case 63:
      document.location.href="/ajouter/arme";
      break;

    default:
      document.location.href="/";
      break;
  }
}
