/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/

/* Mise en forme des données à envoyer*/
function createData(var_tab, var_tab2) {
  const data = new FormData();
  for (var i = 0; i < var_tab.length; i++) {
    let content = document.getElementById(var_tab2[i]).value;
    data.append(var_tab[i], content);
  }

  return data;
}

/* On affiche la photo en fonction du modèle de son id */
function affiche_photo_voiture() {
  let modele = document.getElementById("car_type").value;

  // On met en forme les données
  const data = new FormData();
  data.append('type', modele);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/photo/vehicule');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("image_voiture").setAttribute('src', '/assets/img/vehicule/' + resultat.couleur);
    document.getElementById("image_voiture").setAttribute('alt', 'Photo ' + resultat.modele);
  }
}

/* On affiche la photo en fonction du modèle de sa plaque */
function affiche_photo_plaque()
{
  let modele = document.getElementById("car_type").value;

  // On met en forme les données
  const data = new FormData();
  data.append('type', modele);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/photo/plaque');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("image_voiture").setAttribute('src', '/assets/img/vehicule/' + resultat.couleur);
    document.getElementById("image_voiture").setAttribute('alt', 'Photo ' + resultat.modele);
  }
}

/* On affiche la photo de la personne en fonction de son id */
function affiche_photo_personne()
{
  let civil_id = document.getElementById("personne_type").value;

  // On met en forme les données
  const data = new FormData();
  data.append('id', civil_id);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/photo/personne');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("image_civil").setAttribute('src', '/assets/img/identité/' + resultat.photo);
    document.getElementById("image_civil").setAttribute('alt', 'Photo ' + resultat.nom + ' ' + resultat.prenom);
  }
}

/* On affiche la photo de la personne en fonction de son id */
function affiche_photo_personne_2()
{
  let civil_id = document.getElementById("personne_type_2").value;

  // On met en forme les données
  const data = new FormData();
  data.append('id', civil_id);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/photo/personne');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("image_civil2").setAttribute('src', '/assets/img/identité/' + resultat.photo);
    document.getElementById("image_civil2").setAttribute('alt', 'Photo ' + resultat.nom + ' ' + resultat.prenom);
  }
}

/* On affiche la photo d'un agent */
function affiche_photo_cop()
{
  let cop_id = document.getElementById("cop_type").value;

  // On met en forme les données
  const data = new FormData();
  data.append('id', cop_id);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/photo/cop');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("image_cop").setAttribute('src', '/assets/img/identité/' + resultat.photo);
    document.getElementById("image_cop").setAttribute('alt', 'Photo ' + resultat.grade + ' ' + resultat.nom);
  }
}

/* On affiche la photo d'un agent */
function affiche_photo_cop_2()
{
  let cop_id = document.getElementById("cop_type_2").value;

  // On met en forme les données
  const data = new FormData();
  data.append('id', cop_id);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/photo/cop');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("image_cop2").setAttribute('src', '/assets/img/identité/' + resultat.photo);
    document.getElementById("image_cop2").setAttribute('alt', 'Photo ' + resultat.grade + ' ' + resultat.nom);
  }
}

/* Vérification que la plaque ne soit pas enregistrer */
function verif_plaque()
{
  // On récupère la valeur de la plaque
  let plaque = document.getElementById("plaque_value").value;

  // On met en forme les données
  const data = new FormData();
  data.append('plaque', plaque);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/validiter/plaque');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    if (resultat.etat == 1) { // La plaque existe
      document.getElementById("alerte_message").innerHTML = "Le véhicule est déjà enregistré au nom de " + resultat.nom.toUpperCase() + " " + resultat.prenom.toUpperCase();
      document.getElementById("alerte_message").style.display = "initial";
      document.getElementById("valid_button").style.display = "none";
      document.getElementById("formulaire_ajout_car").setAttribute('action', '');
    }
    else {
      document.getElementById("alerte_message").style.display = "none";
      document.getElementById("valid_button").style.display = "initial";
      document.getElementById("formulaire_ajout_car").setAttribute('action', '/insert/voiture');
    }
  }
}

/* On vérifie qsi qqn possède le même nom */
function alerte_citoyennete()
{
  let nom = document.getElementById("name").value;
  let prenom = document.getElementById("firstname").value;

  // On met en forme les données
  const data = new FormData();
  data.append('nom', nom);
  data.append('prenom', prenom);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/doublon/civil');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    if (resultat.etat == 1) { // Une personne est enreistré sous ce nom
      alert("Attention!\nUne personne est déjà enregistrée sous ce nom.")
    }
  }
}

/* Modifier Mot de passe */
function editPassWord() {
  event.preventDefault(); // Arret du formulaire

  const data = new FormData();
  data.append('id', document.getElementById("nom_pwd").value);
  data.append('dscpt', document.getElementById("mdp_content").value);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/edit/mdp/admin');
  requeteAjax.send(data);

  document.getElementById("confirm_edit_mdp").style.display = "block";
  document.getElementById("mdp_content").value = "";
}

/* Ajouter un délit dans la BDD */
function AddDelitAdmin() {
  event.preventDefault(); // Arret du formulaire

  let var_tab = ['nom', 'amende', 'temps', 'type'];
  let var_tab2 = ['name_delit', 'amd_delit', 'tps_delit', 'type_delit'];
  const data = createData(var_tab, var_tab2);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/insert/delit');
  requeteAjax.send(data);

  document.getElementById("confirm_admin_delit").style.display = "block";
  for (var i = 0; i < (var_tab2.length - 1); i++) {
    document.getElementById(var_tab2[i]).value = "";
  }
}

/* Modification Serveur Param */
function EditServParam() {
  event.preventDefault(); // Arret du formulaire

  let value_tab = [
    "failed_connexion",
    "periode_drive",
    "time_dirve",
    "level_censure",
    "etat_recrut"
  ];

  const data = createData(value_tab, value_tab);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/administration/parametre-serveur/modification');
  requeteAjax.send(data);
  document.getElementById("confirm_admin_modifserv").style.display = "block";
}

/* Vérification Saisie MDP */
function VerifSaisieMDP() {
  let old = document.getElementById("old_mdp").value
  let newMdp = document.getElementById("new_mdp").value
  let nexMDP = document.getElementById("nex_mdp").value

  document.getElementById("old_mdp_false").style.display = "none";
  document.getElementById("new_mdp_false").style.display = "none";
  document.getElementById("nex_mdp_false").style.display = "none";
  document.getElementById("btn_confirm").style.display = "block";

  if (old == newMdp) {
    document.getElementById("btn_confirm").style.display = "none";
    document.getElementById("new_mdp_false").style.display = "block";
  }

  if (nexMDP != newMdp) {
    document.getElementById("btn_confirm").style.display = "none";
    document.getElementById("nex_mdp_false").style.display = "block";
  }
}

/* Affichage du bon menu */
function AfficheMenuModDelit() {
  document.getElementById("list_route").style.display = "none";
  document.getElementById("list_leger").style.display = "none";
  document.getElementById("list_moyen").style.display = "none";
  document.getElementById("list_grave").style.display = "none";
  document.getElementById("dtls_amende").style.display = "none";
  document.getElementById("dtls_prison").style.display = "none";
  document.getElementById("confirm_edit_delit").style.display = "none";
  document.getElementById("button_delit").style.display = "none";

  switch (document.getElementById("delit_type").value) {
    case "1":
      document.getElementById("list_route").style.display = "block";
      break;
    case "2":
      document.getElementById("list_leger").style.display = "block";
      break;
    case "3":
      document.getElementById("list_moyen").style.display = "block";
      break;
    case "4":
      document.getElementById("list_grave").style.display = "block";
      break;
    default:
      document.getElementById("list_route").style.display = "none";
      document.getElementById("list_leger").style.display = "none";
      document.getElementById("list_moyen").style.display = "none";
      document.getElementById("list_grave").style.display = "none";
      document.getElementById("confirm_edit_delit").style.display = "none";
      break;
  }
}

/* Aficher les informations en fonction du délit */
function EditMenuModDelit(value_this)
{
  if (value_this == 0) {
  document.getElementById("dtls_amende").style.display = "none";
  document.getElementById("dtls_prison").style.display = "none";
  document.getElementById("confirm_edit_delit").style.display = "none";
  document.getElementById("amd_delit").value = '';
  document.getElementById("tps_delit").value = '';
  document.getElementById("button_delit").style.display = "none";
  return;
  }

  document.getElementById("dtls_amende").style.display = "block";
  document.getElementById("dtls_prison").style.display = "block";
  document.getElementById("confirm_edit_delit").style.display = "none";
  document.getElementById("amd_delit").value = '';
  document.getElementById("tps_delit").value = '';
  document.getElementById("button_delit").setAttribute('value', value_this);
  document.getElementById("button_delit").style.display = "initial";


  // On met en forme les données
  const data = new FormData();
  data.append('id_delit', value_this);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/info_delit');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("amd_delit").placeholder = resultat.amende;
    document.getElementById("tps_delit").placeholder = resultat.prison;
  }
}

/* Mise à jour du Délit */
function updateDelitAdmin() {
  event.preventDefault(); // Arret du formulaire

  const data = new FormData();
  data.append('id_delit', document.getElementById('button_delit').value);
  data.append('amd_delit', document.getElementById('amd_delit').value);
  data.append('tps_delit', document.getElementById('tps_delit').value);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/delit/edit/value');
  requeteAjax.send(data);

  document.getElementById("confirm_edit_delit").style.display = "block";
}

/* Ajouter catégorie d'arme dans la BDD */
function addWeaponCategorie() {
  event.preventDefault(); // Arret du formulaire

  let var_tab = ['categorie', 'prefix'];
  let var_tab2 = ['categorie_weapon', 'prefixe_weapon'];
  const data = createData(var_tab, var_tab2);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/insert/weapon/categorie');
  requeteAjax.send(data);
  document.getElementById("confirm_add_catWeapon").style.display = "block";

  document.getElementById('categorie_weapon').value = '';
  document.getElementById('prefixe_weapon').value = '';
}

/* On affiche la photo de l'arme en fonction du modèle de son id */
function affiche_photo_arme()
{
  // On met en forme les données
  const data = new FormData();
  data.append('type', document.getElementById("weapon_type").value);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/photo/arme');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("image_arme").setAttribute('src', '/assets/img/arme/' + resultat.couleur);
    document.getElementById("image_arme").setAttribute('alt', 'Photo ' + resultat.modele);
  }
}
