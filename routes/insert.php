<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('POST /insert/casier', function() {
  verif_connecter();
  /* Variable de POST */
  $delit = $_POST['delit_name'];
  $rapport = urlencode($_POST['rapport']);
  $proprio = $_POST['casier_owner'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  if ($agent->ajouter == 0 or $delit == 0) {
    return;
  }
  else {
    addCasier($delit, $rapport, $proprio, $agent);
    addHistorique($agent->matricule, "4¤0¤0¤" . $delit . "¤" . $proprio);
  }
});

Flight::route('/insert/civil', function() {
  verif_connecter();
  /* Variable de POST */
  $nom = ucfirst($_POST['civil_name']);
  $prenom = ucfirst($_POST['civil_firstame']);
  $dob = $_POST['civil_dob'];
  if (!isset($_POST['civil_phone'])) {
    $phone = NULL;
  }
  else {
    $phone = $_POST['civil_phone'];
  }
  $nationality = ucfirst($_POST['civil_nationality']);
  $sexe = $_POST['civil_sex'];
  $permis = $_POST['civil_licence'];
  $job = $_POST['civil_job'];
  $photo = "temp.png";
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  if ($agent->ajouter == 0) { // N'est pas autorisé à ajouter
    Flight::redirect("/ajouter/civil");
  }
  else {
    addPersonne($nom, $prenom, $dob, $phone, $nationality, $sexe, $permis, $photo, $job);
    addHistorique($agent->matricule, "4¤0¤1¤$nom $prenom");

    $info = Personne::getIDPersonne($nom, $prenom);
    Flight::redirect("/civil/$info->id");
  }
});

Flight::route('/insert/delit', function() {
  verif_connecter();
  verif_admin();

  $nom = $_POST['nom'];
  $montant = $_POST['amende'];
  $prison = $_POST['temps'];
  $type = $_POST['type'];

  $agent = Agent::getInfoAgent();
  addDelit($nom, $montant, $prison, $type);
  addHistorique($agent->matricule, "0¤1¤" . ($type+1) . "¤" . $nom);
});

Flight::route('/insert/weapon/categorie', function() {
  verif_connecter();
  verif_admin();

  $cat = $_POST['categorie'];
  $pref = $_POST['prefix'];

  $agent = Agent::getInfoAgent();
  addWeaponCategorie($cat, $pref);
  addHistorique($agent->matricule, "0¤1¤7¤" . $cat . "¤" . $pref);
});

Flight::route('/insert/img/@type', function($type) {
  verif_connecter();
  verif_admin();
  /* Début des vérifications de sécurité et upload*/ // A vérifier si ça marche en ligne
  $dossier = "assets/img/$type/";
  $image = $_FILES['photo'];
  $taille_maxi = 5000000; // Limitation de la taille à 5 Mo
  $extensions = array('png', 'jpg', 'jpeg', 'gif');

  $upload = uploadFile($dossier, $taille_maxi, $image, $extensions);

  if(!$upload) { // Si l'extension n'est pas dans le tableau
    Flight::redirect("/administration/ajout");
    exit();
  }

  /* Fin vérification de sécurité et upload */
  $agent = Agent::getInfoAgent();

  switch ($type) {
    case 'vehicule':
      $nom = $_POST['vehicule_name'];
      addImgCar($nom, $upload['name']);
      addHistorique($agent->matricule, "0¤1¤5¤" . $nom);
      Flight::redirect("/ajouter/vehicule");
      break;
    case 'identite':
      $id_civil = $_POST['nom'];
      addImgCivil($id_civil, $upload['name']);
      addHistorique($agent->matricule, "0¤1¤6¤" . $id_civil);
      Flight::redirect("/civil/$id_civil");
      break;
    case 'arme':
      $name = $_POST['nom_name'];
      $cat = $_POST['cat_weapon'];
      addWeaponList($name, $cat, $upload['name']);
      addHistorique($agent->matricule, "0¤1¤8¤" . $name);
      Flight::redirect("/administration/ajout");
      break;
    default:
      Flight::redirect("/administration/ajout");
      break;
  }
});

Flight::route('/insert/plainte', function() {
  verif_connecter();
  /* Variable de POST */
  $deposeur = $_POST['deposeur'];
  $citoyen = $_POST['citoyen_1'];
  $detail = urlencode($_POST['plainte']);
  /* Variable de POST */

  if ($citoyen == 0) {
    $citoyen = 1;
  }

  $agent = Agent::getInfoAgent();
  if ($agent->ajouter == 0 or $deposeur == 0) { // N'est pas autorisé à ajouter
    Flight::redirect("/ajouter/plainte");
  }
  else {
    addPlainte($deposeur, $citoyen, $detail, $agent);
    addHistorique($agent->matricule, "4¤0¤2¤" . $deposeur . "¤" . $citoyen);

    $info = Plainte::getIDPlainte($deposeur, $citoyen, $agent->lspd_id);
    Flight::redirect("/detail-plainte/$info->id");
  }
});

Flight::route('/insert/rapport', function() {
  verif_connecter();
  /* Variable de POST */
  $depo = $_POST['agent_vise'];
  $titre = urlencode($_POST['titre_rapport']);
  $rapport = urlencode(traitementRapport($_POST['rapport']));
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  if ($agent->ajouter == 0) { // N'est pas autorisé à ajouter
    Flight::redirect("/rapport");
  }
  else {
    addRapport($depo, $titre, $rapport, $agent);
    addHistorique($agent->matricule, "4¤0¤3¤" . $agent->matricule . "¤" . Agent::getInfoAgentID($depo)->matricule);
    Flight::redirect("/");
  }
});

Flight::route('/insert/routier', function() {
  verif_connecter();
  /* Variable de POST */
  $conducteur = $_POST['casier_owner'];
  $vehicle = $_POST['vehicule_delit'];
  $delit = $_POST['delit_name'];
  $rapport = urlencode($_POST['rapport']);
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  if ($agent->ajouter == 0 or $delit == 0) { // N'est pas autorisé à ajouter
    return;
  }
  else {
    addRoutier($conducteur, $vehicle, $delit, $rapport, $agent);
    addHistorique($agent->matricule, "4¤0¤4¤" . $delit . "¤" . $conducteur . "¤" . Voiture::getCarID($vehicle)->plaque);
  }
});

Flight::route('/insert/voiture', function() {
  verif_connecter();
  /* Variable de POST */
  $model = $_POST['vehicule_model'];
  $color = urlencode($_POST['vehicule_color']);
  $color = urlencode($_POST['vehicule_color']);
  $plaque = strtoupper($_POST['vehicule_plaque']); // On met la plaque en majuscule
  $proprio = $_POST['vehicule_owner'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  if ($agent->ajouter == 0) { // N'est pas autorisé à ajouter
    Flight::redirect("/ajouter/vehicule");
  }
  else {
    addVoiture($model, $color, $plaque, $proprio, $agent);
    addHistorique($agent->matricule, "4¤0¤5¤$plaque" . "¤" . $proprio);

    Flight::redirect("/vehicule/$plaque"); // Redirige vers la page
  }
});

Flight::route('/insert/weapon', function() {
  verif_connecter();
  /* Variable de POST */
  $model = $_POST['weapon_model'];
  $proprio = $_POST['weapon_owner'];
  /* Variable de POST */

  do {
    $serie = rand(1000, 9999);
  }
  while(listeArme::getNumSerie($serie));

  $agent = Agent::getInfoAgent();
  if ($agent->ajouter == 0) { // N'est pas autorisé à ajouter
    Flight::redirect("/ajouter/arme");
  }
  else {
    addWeapon($serie, $model, $proprio, $agent);
    addHistorique($agent->matricule, "4¤0¤6¤$model" . "¤" . $proprio); // HERE

    Flight::redirect("/arme/$serie"); // Redirige vers la page
  }
});
?>
