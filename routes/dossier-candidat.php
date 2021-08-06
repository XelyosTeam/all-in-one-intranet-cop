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

  $linsting = array('en_attente', 'refusee', 'accepte');

  foreach ($linsting as $element) {
    $vardin = $element;
    foreach ($$vardin as $liste) {
      $liste->nom = urldecode($liste->nom);
      $liste->prenom = urldecode($liste->prenom);
    }
  }

  $contact = new ArrayObject();
  $path = dirname(dirname(__FILE__));
  $struct = getStructure($path, 'candidature');

  if ($identifiant != NULL) {
    $personne = Candidature::getCandidature($identifiant);
    $personne->discord_id = urldecode($personne->discord_id);
    $personne->nom = urldecode($personne->nom);
    $personne->prenom = urldecode($personne->prenom);
    $personne->phone = urldecode($personne->phone);
    $personne->detail_flic = urldecode($personne->detail_flic);
    $personne->objectif_lspd = urldecode($personne->objectif_lspd);
    $personne->motivation_lspd = renderHTMLFromMarkdown(htmlspecialchars(strip_tags(urldecode($personne->motivation_lspd))));
    $personne->attachments = explode(",", $personne->attachments);

    /* Traitement concaténation */
    $personne->reponse_concat = urldecode($personne->reponse_concat);
    $personne->reponse_concat = explode('¤', $personne->reponse_concat);
    foreach ($personne->reponse_concat as $reponse) {
      $contact->append(urldecode($reponse));
    }

    /* Période de disponibilité */
    $ecole = explode('-', $personne->detail_ecole);
    $vacance = explode('-', $personne->detail_vacance);
    $travail = explode('-', $personne->detail_travail);
  }

  Flight::view()->display('ecole/dossier_candidat.twig', array(
    'attente' => $en_attente,
    'refuser' => $refusee,
    'accepter' => $accepte,
    'personne' => $personne,
    'ecole' => $ecole,
    'vacance' => $vacance,
    'travail' => $travail,
    'reponses' => (array)$contact,
    'questions' => $struct->questions
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
