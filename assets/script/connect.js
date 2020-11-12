/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/

/* Vérification des valeurs saisie dans le formulaire */
function verif_data() {
  // On stop le formulaire
  event.preventDefault();

  // On récupère les données du formulaire
  let matricule = document.getElementById("user_matricule").value;
  let mdp = document.getElementById("user_mdp").value;

  // Vérification de saisie non vide
  if (matricule === '') {
    affiche_error("Saisissez un matricule");
    erreurForme(2);
    return;
  }

  if (mdp === '') {
    affiche_error("Saisissez un mot de passe");
    erreurForme(4);
    return;
  }

  // On met en forme les données
  const data = new FormData();
  data.append('user_matricule', matricule);
  data.append('user_mdp', mdp);

  // Configuration de la requête et envoi des données
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/connect');

  requeteAjax.onload = function() {
    const resultat = JSON.parse(requeteAjax.responseText);

    const nex = [];
    for (let i = 0; i < mdp.length; i++) {
      nex[i] = "*";
    }

    if (resultat.etat == 0) {
      let tab_phrase = [
        ["Initialisation de la connexion.....", "txt1", 1],
        ["Vérification matricule : " + matricule, "txt2", 1],
        ["Matricule confirmé.....", "txt3", 2],
        ["Vérification mot de passe : " + nex.join(''), "txt4", 1],
        ["Mot de passe validé.....", "txt5", 2],
        ["Identité confirmée : " + resultat.message, "txt6", 1],
        ["Accès autorisé !", "txt7", 2]
      ];

      programmeAnimation(tab_phrase, 1);
    }
    else {
      /* On affiche l'erreur avec l'animation */
      // L'erreur est au compte bloqué
      if (resultat.etat == 1) {
         let tab_phrase = [
           ["Initialisation de la connexion.....", "txt1", 1],
           ["Ligne bloquée.....", "txt2", 0], // A modifier plus tard pour trouver qqc de plus beau
           ["Contactez votre supérieur.....", "txt3", 1],
           ["Accès refusé !", "txt4", 0]
         ];
         programmeAnimation(tab_phrase, 0);
      }

      // Le matricule saisie n'est pas valide
      if (resultat.etat == 2 || resultat.etat == 3) {
         let tab_phrase = [
           ["Initialisation de la connexion.....", "txt1", 1],
           ["Vérification matricule : " + matricule, "txt2", 1],
           ["Echec authentification.....", "txt3", 0],
           ["Accès refusé !", "txt4", 0]
         ];
         programmeAnimation(tab_phrase, 0);
      }

      // Le mot de passe saisie n'est pas valide
      if (resultat.etat == 4) {
        let tab_phrase = [
          ["Initialisation de la connexion.....", "txt1", 1],
          ["Vérification matricule : " + matricule, "txt2", 1],
          ["Matricule confirmé.....", "txt3", 2],
          ["Vérification mot de passe : " + nex.join(''), "txt4", 1],
          ["Mot de passe incorrect.....", "txt5", 0],
          ["Accès refusé !", "txt6", 0]
        ];
        programmeAnimation(tab_phrase, 0);
      }

      // On affiche l'erreur sur le formulaire de saisie
      erreurForme(resultat.etat);
      affiche_error(resultat.message);
    }
  }
  requeteAjax.send(data);
}

/* Affiche les messages d'erreurs */
function affiche_error(message) {
  document.getElementById("info_error").style.display = "block";
  document.getElementById("info_error").innerHTML = message;
}

/* Met en forme la case d'erreur */
function erreurForme(indice) {
  switch (indice) {
    case 2:
    case 3:
      document.getElementById("user_matricule").style.border = "2px solid #AE0000";
      break;
    case 4:
      document.getElementById("user_mdp").style.border = "2px solid #AE0000";
      break;
    default:
      break;
  }
}

/* Initialise la page de connexion */
function initialisation() {
  // L'indication erreur n'est pas visible
  document.getElementById("info_error").style.display = "none";
  // Les élements d'animation ne sont pas visible
  document.getElementById("animation_page").style.display = "none";
}

async function programmeAnimation(tab_phrase, etat) {
  // Mise en place de la page bleue
  document.getElementById("formulaire_connexion").style.display = "none";
  document.getElementById("animation_page").style.display = "block";
  // Initialisation
  let list_id = ["txt1", "txt2", "txt3", "txt4", "txt5", "txt6", "txt7"];
  for (var j = 0; j < list_id.length; j++) {
    document.getElementById(list_id[j]).innerHTML = "";
    document.getElementById(list_id[j]).style.color = "#fff";
  }

  for (i = 0; i < tab_phrase.length; i++) {
    switch (tab_phrase[i][2]) {
      case 0:
        document.getElementById(tab_phrase[i][1]).style.color = "#ff0002";
        break;
      case 2:
        document.getElementById(tab_phrase[i][1]).style.color = "#0076FF";
        break;
      default:
        document.getElementById(tab_phrase[i][1]).style.color = "#00ff00";
        break;
    }

    effetTyping(tab_phrase[i][0], tab_phrase[i][1]);
    await sleep((tab_phrase[i][0].length)*65);
  }

  await sleep(500);

  if (etat == 1) {
    document.location.href="/";
  }
  else {
    document.getElementById("formulaire_connexion").style.display = "block";
    document.getElementById("animation_page").style.display = "none";
  }
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function effetTyping(message, id_champ) {
  document.getElementById(id_champ).style.display = "block";
  let phrase = "";
  for (var i = 0; i < message.length; i++) {
    phrase = phrase + message[i];
    await sleep(50);
    document.getElementById(id_champ).innerHTML = phrase;
  }
}

initialisation();
