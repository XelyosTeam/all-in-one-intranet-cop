<?php
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

Flight::route('/recrutement/etat', function() {
  header("Access-Control-Allow-Origin: *");
  $data = [
    'etat' => serveurIni('Faction', 'etatRecrutement')
  ];

  Flight::json($data);
});

?>
