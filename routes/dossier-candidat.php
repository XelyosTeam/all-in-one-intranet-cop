<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/dossier-candidat', function() {
  verif_connecter();
  verif_enseignant(); // Vérifie que la personne soit bien un enseignant

  /* Variable récupéré dans le get */
  $identifiant = NULL;
  if (isset($_GET['identifiant'])) {
    $identifiant = $_GET['identifiant'];
  }

  /* Variable récupéré dans le get */
  $en_attente = Candidature::getListCandidature(1);
  $refusee = Candidature::getListCandidature(2);
  $accepte = Candidature::getListCandidature(3);
  $personne = NULL;

  $listing = array('en_attente', 'refusee', 'accepte');
  foreach ($listing as $element) {
    $vardin = $element;
    foreach ($$vardin as $liste) {
      $liste->info = urldecode(array_values(json_decode($liste->formulaires, true))[0]);
    }
  }

  $contact = new ArrayObject();
  $path = dirname(dirname(__FILE__));
  $struct = getStructure($path, 'candidature');

  if ($identifiant != NULL) {
    $personne = Candidature::getCandidature($identifiant);
    $personne->formulaires = json_decode($personne->formulaires, true);
    $personne->info = urldecode(array_values($personne->formulaires)[0]);
    $array = new ArrayObject();
    foreach ($personne->formulaires as $key => $value) {
      $array[$key] = urldecode($value);
    }
    $personne->formulaires = $array;
    $personne->attachments = explode(",", $personne->attachments);
  }

  Flight::view()->display('ecole/dossier_candidat.twig', array(
    'attente' => $en_attente,
    'refuser' => $refusee,
    'accepter' => $accepte,
    'personne' => $personne,
    'formulaires' => $struct
  ));
});

Flight::route('/dossier-candidat/@decision/@num', function($decision, $num) {
  verif_connecter();
  verif_enseignant();

  switch ($decision) {
    case 'accepter':
      editCandidature($num, 3);
      addHistorique(Agent::getInfoAgent()->matricule, "1¤1¤3¤" . $num);
      break;
    case 'refuser':
      editCandidature($num, 2);
      addHistorique(Agent::getInfoAgent()->matricule, "1¤1¤4¤" . $num);
      break;
    default:
      editCandidature($num, 2);
      addHistorique(Agent::getInfoAgent()->matricule, "1¤1¤4¤" . $num);
      break;
  }
  Flight::redirect("/dossier-candidat?identifiant=");
});
?>
