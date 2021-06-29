<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/recherche/civil', function() {
  verif_connecter();
  /* Variable récupéré dans le get */
  $nom = $_GET['civil_name'];
  $prenom = $_GET['civil_firstname'];
  $phone = $_GET['civil_phone'];
  $job = $_GET['civil_job'];
  /* Variable récupéré dans le get */

  Flight::view()->display('recherche/research_civil.twig', array(
    'civils' => Personne::getListPersonneTri($nom, $prenom, $phone, $job),
    'nom' => $nom,
    'prenom' => $prenom,
    'phone' => $phone,
    'job' => $job
  ));
});

Flight::route('/recherche/validiter/plaque', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  $plaque = $_POST["plaque"];

  // Récupération de l'info image véhicule
  $car = Voiture::getCar($plaque);
  if ($car == NULL) {
    // La plaque n'est pas enregistrer
    $data = ['etat' => 0];
  }
  else {
    // La plaque est enregistré
    $proprio = Personne::getinfoPersonne($car->proprio);
    $data = [
      'etat' => 1,
      'nom' => $proprio->nom,
      'prenom' => $proprio->prenom
    ];
  }

  Flight::json($data);
});

Flight::route('/recherche/doublon/civil', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  $nom = $_POST["nom"];
  $prenom = $_POST["prenom"];

  // Récupération de l'info image véhicule

  $info = Personne::getIDPersonne($nom, $prenom);
  if ($info == NULL) {
    // La personne n'est enregistrée à ce nom
    $data = ['etat' => 0];
  }
  else {
    // La personne est enregistrée
    $data = ['etat' => 1];
  }

  Flight::json($data);
});

Flight::route('/recherche/info_delit', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  verif_admin();
  $nom = $_POST["id_delit"];

  // Récupération de l'info image véhicule

  $info = Delit::getinfoDelit($nom);

  $data = [
    'amende' => $info->amende,
    'prison' => $info->temps_prison
  ];

  Flight::json($data);
});

Flight::route('/recherche/photo/arme', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  $modele = $_POST["type"];

  // Récupération de l'info image véhicule
  $info = modelArme::getInfo($modele);
  $data = [
    'couleur' => $info->photo,
    'modele' => $info->nom
  ];

  Flight::json($data);
});

Flight::route('/recherche/photo/cop', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  $id = $_POST["id"];

  // Récupération de l'info image véhicule
  $info = Agent::getInfoAgentID($id);
  $data = [
    'grade' => $info->grade,
    'nom' => $info->nom,
    'photo' => $info->photo
  ];

  Flight::json($data);
});

Flight::route('/recherche/photo/personne', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  $id = $_POST["id"];

  // Récupération de l'info image véhicule
  $info = Personne::getinfoPersonne($id);
  $data = [
    'nom' => $info->nom,
    'prenom' => $info->prenom,
    'photo' => $info->photo
  ];

  Flight::json($data);
});

Flight::route('/recherche/photo/vehicule', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  $modele = $_POST["type"];

  // Récupération de l'info image véhicule
  $info = ModelesV::getImage($modele);
  $data = [
    'couleur' => $info->lien,
    'modele' => $info->nom
  ];

  Flight::json($data);
});

Flight::route('/recherche/photo/plaque', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  $plaque = $_POST["type"];

  // Récupération de l'info image véhicule
  $info = Voiture::getCarID($plaque);
  $data = [
    'couleur' => $info->lien,
    'modele' => $info->nom
  ];

  Flight::json($data);
});

Flight::route('/recherche/plainte', function() {
  verif_connecter();
  /* Variable récupéré dans le get */
  $nom = $_GET['civil_name'];
  $prenom = $_GET['civil_firstname'];
  /* Variable récupéré dans le get */

  Flight::view()->display('recherche/research_plainte.twig', array(
    'plaintes' => InfoPlainte::getListPlainte($nom, $prenom),
    'nom' => $nom,
    'prenom' => $prenom
  ));
});

Flight::route('/recherche/vehicule', function() {
  verif_connecter();
  /* Variable récupéré dans le get */
  $modele = $_GET['modele'];
  $plaque = $_GET['plaque'];
  $couleur = $_GET['couleur'];
  /* Variable récupéré dans le get */

  Flight::view()->display('recherche/research_vehicule.twig', array(
    'voitures' => Voiture::getListCarTri($modele, $plaque, $couleur),
    'modele' => $modele,
    'plaque' => $plaque,
    'couleur' => $couleur
  ));
});

?>
