<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/detail-plainte/@id_plainte', function($id_plainte) {
  verif_connecter();
  $plainte = Plainte::getPlainte($id_plainte);
  if (!$plainte) {
    Flight::redirect("/recherche/plainte?civil_name=&civil_firstname=");
    return;
  }
  $personne = Personne::getinfoPersonne($plainte->plainte_sur);

  if (!($plainte->plainte_sur))
  {
    $personne = inconnu($plainte->plainte_sur_2); // On intègre des valeurs par défauts si nécessaires
  }

  // Traitement de l'information date
  if ($personne->permis == 0) {
    $value = traitementDate($personne->date_permis);
    $personne->date_permis = $value[0]; // On modifie la valeur de date permis
    $personne->date_trad = $value[1];
  }

  // Traitement du détails de la plainte
  $plainte->detail_plainte = urldecode($plainte->detail_plainte);
  $plainte->detail_plainte = renderHTMLFromMarkdown(htmlspecialchars(strip_tags($plainte->detail_plainte)));

  Flight::view()->display('fiche/detail_plainte.twig', array(
    'civil' => $personne, // Infotmation sur la personne
    'plainte' => $plainte, // Détails de la plainte
    'deposeur' => Personne::getinfoPersonne($plainte->deposeur), // Information sur le déposeur
    'ouvert' => Agent::getInfoAgentID($plainte->enregistrer_par), // Policier ayant pris la plainte
    'fermer' => Agent::getInfoAgentID($plainte->fermer_par), // Policier ayant fermé la plainte
    'policier' => Agent::getInfoAgentIDUser($personne->id)
  ));
});

Flight::route('/detail-plainte/@id_plainte/edit', function($id_plainte) {
  verif_connecter();
  $plainte = Plainte::getPlainte($id_plainte);
  $personne = Personne::getinfoPersonne($plainte->plainte_sur);
  $ouvert = Agent::getInfoAgentID($plainte->enregistrer_par);
  $agent = Agent::getInfoAgent();

  if ($plainte->etat != 0) {
    Flight::redirect("/detail-plainte/$id_plainte");
    exit();
  }

  if ($agent->hab_1 != 2) {
    if ($agent->matricule != $ouvert->matricule) {
      Flight::redirect("/detail-plainte/$id_plainte");
      exit();
    }
  }

  if (!($plainte->plainte_sur)) {
    $personne = inconnu($plainte->plainte_sur_2); // On intègre des valeurs par défauts si nécessaires
  }

  // Traitement texte
  $plainte->detail_plainte = urldecode($plainte->detail_plainte);


  Flight::view()->display('edit/plainte.twig', array(
    'personne' => $personne, // Infotmation sur la personne
    'plainte' => $plainte, // Détails de la plainte
    'deposeur' => Personne::getinfoPersonne($plainte->deposeur), // Information sur le déposeur
    'ouvert' => $ouvert, // Policier ayant pris la plainte
    'fermer' => Agent::getInfoAgentID($plainte->fermer_par), // Policier ayant fermé la plainte
    'policier' => Agent::getInfoAgentIDUser($personne->id)
  ));
});

Flight::route('/detail-plainte/@id_plainte/modification', function($id_plainte) {
  verif_connecter();
  $agent = Agent::getInfoAgent();

  /* Variable récupéré dans le get */
  $rapport = urlencode($_POST['rapport']);
  /* Variable récupéré dans le get */

  if ($agent->editer == 0) {
    Flight::redirect("/detail-plainte/$id_plainte/edit");
    exit();
  }

  $oldinfo = Plainte::getPlainte($id_plainte);

  if ($oldinfo->detail_plainte != $rapport) {
    addHistorique($agent->matricule, "3¤4¤0¤" . $id_plainte . "¤" . $oldinfo->plainte_sur . "¤" . $oldinfo->detail_plainte . "¤" . $rapport);
  }

  editRapportPlainte((int)$id_plainte, $rapport);

  Flight::redirect("/detail-plainte/$id_plainte");
});

Flight::route('/detail-plainte/@id_plainte/@etat', function($id_plainte, $etat) {
  verif_connecter();
  $agent = Agent::getInfoAgent();

  if ($agent->editer == 0) {
    Flight::redirect("/detail-plainte/$id_plainte");
    exit();
  }

  closePlainte($id_plainte, $etat, $agent);
  addHistorique($agent->matricule, "5¤0¤2¤" . $id_plainte . "¤" . $etat);

  Flight::redirect("/detail-plainte/$id_plainte");
});
?>
