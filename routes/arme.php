<?php
use Josantonius\Session\Session;

Flight::route('/arme/@numero_serie', function($numero_serie) {
  verif_connecter();
  $arme = Arme::getInfoNumero($numero_serie);
  if (!$arme) {
    Flight::redirect("/ajouter/arme");
    return;
  }
  $personne = Personne::getinfoPersonne($arme->proprio);

  // Traitement de l'information date
  if ($personne->permis == 0) {
    $value = traitementDate($personne->date_permis);
    $personne->date_permis = $value[0]; // On modifie la valeur de date permis
    $personne->date_trad = $value[1];
  }

  Flight::view()->display('fiche/detail_arme.twig', array(
    'arme' => $arme, // Info du casier
    'civil' => $personne, // Informations sur la personne
    'cop' => Agent::getInfoAgentID($arme->matricule), // Policier ayant incrit le casier
    'policier' => Agent::getInfoAgentIDUser($personne->id)
  ));
});
?>
