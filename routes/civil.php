<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/civil/@id_citoyen', function($id_citoyen) {
  verif_connecter();
  $civil = Personne::getinfoPersonne($id_citoyen);
  if (!$civil) {
    Flight::redirect("/recherche/civil?civil_name=&civil_firstname=&civil_phone=&civil_job=");
    return;
  }

  // Traitement de l'information date
  if ($civil->permis == 0) {
    $value = traitementDate($civil->date_permis);
    $civil->date_permis = $value[0]; // On modifie la valeur de date permis
    $civil->date_trad = $value[1];
  }

  Flight::view()->display('fiche/civil.twig', array(
    'civil' => $civil, // Information sur le civil
    'voitures' => Voiture::getListCarPersonne($id_citoyen), // Liste des véhicules
    'routes' => Route::getListDelitByC($id_citoyen), // Liste délits routiers
    'casiers' => Casier::getListCasier($id_citoyen), // Liste casier judiciaire
    'plaintes' => Plainte::getListPlainte($id_citoyen), // Liste des plaintes
    'policier' => Agent::getInfoAgentIDUser($civil->id), // Si la personne est cops
    'armes' => Arme::getListIDUser($civil->id)
  ));
});

Flight::route('/civil/@id_citoyen/calcul-amende', function($id_citoyen) {
  verif_connecter();
  $civil = Personne::getinfoPersonne($id_citoyen);
  $amendeCasier = Casier::getAmende($id_citoyen);
  $amendeRoute = route::getAmende($id_citoyen);
  $retraitRoute = route::getRetrait($id_citoyen);
  $prisonCasier = Casier::getPrison($id_citoyen);
  $prisonRoute = route::getPrison($id_citoyen);
  $total = traitement_amende($amendeCasier, $amendeRoute, $prisonCasier, $prisonRoute);

  // Traitement de l'information date
  if ($civil->permis == 0) {
    $value = traitementDate($civil->date_permis);
    $civil->date_permis = $value[0]; // On modifie la valeur de date permis
    $civil->date_trad = $value[1];
  }

  Flight::view()->display('fiche/montant_amende.twig', array(
    'civil' => $civil,
    'prison' => $total[1],
    'amende' => $total[0],
    'prisonAnnée' => $total[2],
    'prisonAnnée2' => $total[3],
    'retrait' => $retraitRoute
  ));
});

Flight::route('/civil/@id_citoyen/close-casiers', function($id_citoyen) {
  verif_connecter();
  $agent = Agent::getInfoAgent();

  if ($agent->editer == 0) {
    Flight::redirect("/civil/$id_citoyen/calcul-amende");
    exit();
  }

  $Casier = Casier::getCasierEnCours($id_citoyen);
  $Route = Route::getRouteEnCours($id_citoyen);
  foreach ($Casier as $variable) {
    closeCasier($variable->id_delit, 3, $agent->lspd_id);
    addHistorique($agent->matricule, "5¤0¤0¤" . $variable->id_delit . "¤" . 2);
  }

  foreach ($Route as $variable) {
    closeRoute($variable->delit_id, 3, $agent->lspd_id);

    /* Protocole de perte de point pour le civil */
    $MyDelit = Route::getRoute($variable->delit_id); // Récupération du délit
    $civil = Personne::getinfoPersonne($MyDelit->conducteur_id);
    $diff = $civil->permis - $MyDelit->retrait;
    if ($diff < 0) { $diff = 0; } // On ne va pas dans le négatif
    editCivil2($civil->id, $civil->phone, $diff, time(), $civil->ppa);

    addHistorique($agent->matricule, "5¤0¤1¤" . $variable->delit_id . "¤" . 2);
  }
  Flight::redirect("/civil/$id_citoyen");
});

Flight::route('/civil/@id_citoyen/edit', function($id_citoyen) {
  verif_connecter();
  $civil = Personne::getinfoPersonne($id_citoyen);

  Flight::view()->display('edit/civil.twig', array(
    'perso' => $civil, // Information sur le civil
    'voitures' => Voiture::getListCarPersonne($id_citoyen), // Liste des véhicules
    'routes' => Route::getListDelitByC($id_citoyen), // Liste délits routiers
    'casiers' => Casier::getListCasier($id_citoyen), // Liste casier judiciaire
    'plaintes' => Plainte::getListPlainte($id_citoyen), // Liste des plaintes
    'policier' => Agent::getInfoAgentIDUser($civil->id) // Liste des plaintes
  ));
});

Flight::route('/civil/@id_civil/modification', function($id_civil) {
  verif_connecter();
  /* Variable récupérée dans le get */
  $phone = $_POST['telephone'];
  if (isset($_POST['metier'])) {
    $job = $_POST['metier'];
  }
  $drive = $_POST['permis'];
  $ppa = $_POST['ppa'];
  /* Variable récupérée dans le get */

  $oldinfo = Personne::getinfoPersonne((int)$id_civil); // Ancienne info du civil
  $agent = Agent::getInfoAgent();

  /* Ajout dans l'historique */
  if ($oldinfo->phone != $phone) {
    addHistorique($agent->matricule, "3¤1¤0¤" . $id_civil . "¤" . $oldinfo->phone . "¤" . $phone);
  }

  if (isset($_POST['metier']) && ($oldinfo->job != $job)) {
    addHistorique($agent->matricule, "3¤1¤1¤" . $id_civil . "¤" . $oldinfo->job . "¤" . $job);
  }

  if ($oldinfo->permis != $drive) {
    addHistorique($agent->matricule, "3¤1¤2¤" . $id_civil . "¤" . $oldinfo->permis . "¤" . $drive);
    $time = time();
  }
  else {
    $time = $oldinfo->date_permis;
  }

  if (isset($_POST['ppa']) && ($oldinfo->ppa != $ppa)) {
    addHistorique($agent->matricule, "3¤1¤P1¤" . $id_civil . "¤" . $oldinfo->ppa . "¤" . $ppa);
  }

  if ($agent->editer == 0) {
    Flight::redirect("/civil/$id_civil");
    exit();
  }

  if (isset($_POST['metier'])) {
    editCivil((int)$id_civil, $phone, $job, $drive, $time, $ppa);
  }
  else {
    editCivil2((int)$id_civil, $phone, $drive, $time, $ppa);
  }

  Flight::redirect("/civil/$id_civil");
});

Flight::route('/civil/@id_citoyen/impression', function($id_citoyen) {
  verif_connecter();
  $impression = new generatePDF();
  $civil = Personne::getinfoPersonne($id_citoyen);
  if (!$civil) {
    Flight::redirect("/civil/$id_citoyen");
    return;
  }
  $impression->civil($civil, $id_citoyen);
});
?>
