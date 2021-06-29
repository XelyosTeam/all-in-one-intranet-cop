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
  $identifiant = $_GET['identifiant'];
  /* Variable récupéré dans le get */
  $en_attente = Candidature::getListCandidature(1);
  $refusee = Candidature::getListCandidature(2);
  $accepte = Candidature::getListCandidature(3);
  $personne = NULL;
  $ecole = NULL;
  $vacance = NULL;
  $travail = NULL;
  $concat = NULL;

  if ($identifiant != NULL) {
    $personne = Candidature::getCandidature($identifiant);
    $personne->motivation_lspd = renderHTMLFromMarkdown(htmlspecialchars(strip_tags($personne->motivation_lspd)));
    $ecole = explode('-', $personne->detail_ecole);
    $vacance = explode('-', $personne->detail_vacance);
    $travail = explode('-', $personne->detail_travail);
    $concat = explode('-', $personne->concat);
  }

  Flight::view()->display('ecole/dossier_candidat.twig', array(
    'attente' => $en_attente,
    'refuser' => $refusee,
    'accepter' => $accepte,
    'personne' => $personne,
    'ecole' => $ecole,
    'vacance' => $vacance,
    'travail' => $travail,
    'concat' => $concat
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
