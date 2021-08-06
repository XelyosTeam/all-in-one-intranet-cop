<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/recrutement', function() {
  verif_connecter();
  verif_enseignant();
  Flight::view()->display('ecole/recrutement_police.twig', array(
    'personnes' => Personne::OldCop(),
    'cops' => Agent::getListAgent(),
    'oldcops' => Agent::getListOldAgent()
  ));
});

/* Utilisé pour afficher l'état des recrutements */
Flight::route('/recrutement/etat', function() {
  header("Access-Control-Allow-Origin: *");
  $data = [
    'etat' => serveurIni('Faction', 'etatRecrutement')
  ];

  Flight::json($data);
});
?>
