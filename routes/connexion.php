<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/connexion', function() {
  Session::destroy(); // Destruction de session
  Flight::view()->display('connect.twig', array());
});

Flight::route('/ppa', function() {
  verif_connecter();
  verif_enseignant(); // Vérifie que la personne soit bien un enseignant

  Flight::view()->display('ecole/liste_ppa.twig', array(
    'ppa' => Personne::getPPA(1),
    'nonppa' => Personne::getPPA(2)
  ));
});

Flight::route('/add/cop', function() {
  verif_connecter();
  /* Variable de POST */
  $id_civil = $_POST['nom_civil'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  if ($agent->hab_1 != 2) { // N'est pas autorisé à ajouter
    Flight::redirect("/recrutement");
  }
  else {
    $matricule = addCop($id_civil);
    addHistorique($agent->matricule, "1¤1¤0¤" . $matricule);
    Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$matricule"); // Redirection vers la page de l'agent
  }
});

Flight::route('/nouveau-mot-de-passe', function() {
  verif_connecter();
  Flight::view()->display('new_mdp.twig');
});

Flight::route('/connect', function() {
  header("Access-Control-Allow-Origin: *");
  /* Variable de POST */
  $matricule = $_POST['user_matricule'];
  $mdp = $_POST['user_mdp'];
  $ip_user = $_SERVER['REMOTE_ADDR'];
  /* Variable de POST */

  // protocole_connexion($matricule, $mdp, $ip_user);
  $etat = protocole_connexion($matricule, $mdp, $ip_user);
  switch ($etat) {
    case 1:
      $message = "Tentative de connexion trop importante !";
      break;
    case 2:
      $message = "Le matricule saisi n'est pas valide";
      break;
    case 3:
      $message = "Le matricule saisi n'est plus en service";
      break;
    case 4:
      $message = "Le mot de passe entré n'est pas valide";
      break;
    default:
      $message = $etat;
      $etat = 0;
      break;
  }

  $data = [
    'etat' => $etat,
    'message' => $message
  ];

  Flight::json($data);
});
?>
