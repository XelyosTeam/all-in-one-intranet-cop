<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/rapport', function() {
  verif_connecter();
  verif_enseignant(); // Vérifie que la personne soit bien un enseignant
  $info = Agent::getInfoAgent();

  Flight::view()->display('ecole/recherche_rapport.twig', array(
    'listes' => Rapport::liste_non_vu($info->lspd_id)
  ));
});

Flight::route('/rapport/add', function() {
  verif_connecter();
  Flight::view()->display('add/add_rapport.twig', array(
    'agents' => Agent::getListAgent()
  ));
});

Flight::route('/rapport/@id_rapport', function($id_rapport) {
  verif_connecter();
  $rapport = Rapport::getRapportID($id_rapport);

  $agent = Agent::getInfoAgent();

  // On indique comme quoi l'agent à déjà vu le rapport
  if (RapportVU::non_vu($agent->lspd_id, $rapport->id) == NULL) {
    addRapportVu($agent->lspd_id, $rapport->id);
  }

  if ($agent->grade_id < serveurIni('Faction', 'gradeLevelBiffure')) {
    $rapport->contenu = renderHTMLFromMarkdown(htmlspecialchars(strip_tags(urldecode($rapport->contenu))));
    $rapport->contenu = biffage($rapport->contenu);
  }

  $rapport->titre = urldecode($rapport->titre);

  Flight::view()->display('fiche/rapport.twig', array(
    'rapport' => $rapport, // Information sur le rapport
    'agent' => Agent::getInfoAgentID($rapport->concerne), // Information sur l'agent
    'deposeur' => Agent::getInfoAgentID($rapport->editeur) // Information sur le déposeur
  ));
});
?>
