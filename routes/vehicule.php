<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/vehicule/@plaque', function($plaque) {
  verif_connecter();
  $voiture = Voiture::getCar($plaque);
  if (!$voiture) {
    Flight::redirect("/recherche/vehicule?modele=&plaque=&couleur=");
    return;
  }

  $enregistreur = Agent::getInfoAgentID($voiture->enregistreur);

  Flight::view()->display('fiche/voiture.twig', array(
    'proprio' => Personne::getinfoPersonne($voiture->proprio), // Information sur le propriétaire véhicule
    'voiture' => $voiture, // Information sur le véhicule
    'policier' => Agent::getInfoAgentMatricule($enregistreur->matricule), // Information sur le policier qui a enregistrer le véhicule
    'delits' => Route::getListDelitByV($voiture->v_id) // Liste des délits routier
  ));
});

Flight::route('/vehicule/@plaque/edit', function($plaque) {
  verif_connecter();
  $voiture = Voiture::getCar($plaque);
  $enregistreur = Agent::getInfoAgentID($voiture->enregistreur);

  Flight::view()->display('edit/vehicule.twig', array(
    'proprio' => Personne::getinfoPersonne($voiture->proprio), // Information sur le propriétaire véhicule
    'voiture' => $voiture, // Information sur le véhicule
    'policier' => Agent::getInfoAgentMatricule($enregistreur->matricule), // Information sur le policier qui a enregistrer le véhicule
    'delits' => Route::getListDelitByV($voiture->v_id), // Liste des délits routier
    'civils' => Personne::getListPersonne(2) // v1.6.1
  ));
});

Flight::route('/vehicule/@plaque/modification', function($plaque) {
  verif_connecter();
  /* Variable récupéré dans le get */
  $color = $_POST['couleur'];
  $road = $_POST['circu'];
  $proprio = $_POST['proprio']; // v1.6.1
  /* Variable récupéré dans le get */

  $agent = Agent::getInfoAgent();
  if ($agent->editer == 0) {
    Flight::redirect("/vehicule/$plaque");
    exit();
  }

  $oldinfo = Voiture::getCar($plaque);

  if ($oldinfo->couleur != $color) {
    addHistorique($agent->matricule, "3¤2¤0¤" . $plaque . "¤" . $oldinfo->couleur . "¤" . $color);
  }

  if ($oldinfo->circulation != $road) {
    addHistorique($agent->matricule, "3¤2¤1¤" . $plaque . "¤" . $oldinfo->circulation . "¤" . $road);
  }

  if ($oldinfo->proprio != $proprio) {  // v1.6.1
    addHistorique($agent->matricule, "3¤2¤2¤" . $plaque . "¤" . $oldinfo->proprio . "¤" . $proprio);
  }

  editVehicule($plaque, $color, $road, $proprio); // v1.6.1

  Flight::redirect("/vehicule/$plaque");
});

?>
