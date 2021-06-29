<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/delit-routier/@id_delit', function($id_delit) {
  verif_connecter();
  $delit = Route::getRoute($id_delit);
  if (!$delit) {
    Flight::redirect("/ajouter/casier-routier");
    return;
  }
  $personne = Personne::getinfoPersonne($delit->conducteur_id);
  $voiture = Voiture::getCarID($delit->v_id);

  // Traitement de l'information date
  if ($personne->permis == 0) {
    $value = traitementDate($personne->date_permis);
    $personne->date_permis = $value[0]; // On modifie la valeur de date permis
    $personne->date_trad = $value[1];
  }

  $delit->remarque = renderHTMLFromMarkdown(htmlspecialchars(strip_tags($delit->remarque)));
  Flight::view()->display('fiche/detail_route.twig', array(
    'delit' => $delit, // Info du delit
    'civil' => $personne, // Informations sur la personne
    'voiture' => $voiture, // Informations sur le véhicule
    'proprio' => Personne::getinfoPersonne($voiture->proprio), // Propriétaire du véhicule
    'ouvert' => Agent::getInfoAgentID($delit->enregistreur_id), // Policier ayant inscrit le casier
    'fermer' => Agent::getInfoAgentID($delit->acquite_par), // Policier ayant fermé le casier
    'policier' => Agent::getInfoAgentIDUser($personne->id)
  ));
});

Flight::route('/delit-routier/@id_delit/edit', function($id_delit) {
  verif_connecter();
  $delit = Route::getRoute($id_delit);
  $personne = Personne::getinfoPersonne($delit->conducteur_id);
  $voiture = Voiture::getCarID($delit->v_id);
  $ouvert = Agent::getInfoAgentID($delit->enregistreur_id);
  $agent = Agent::getInfoAgent();

  if ($delit->etat != 0) {
    Flight::redirect("/delit-routier/$id_delit");
    exit();
  }

  if ($agent->hab_1 != 2) {
    if ($agent->matricule != $ouvert->matricule) {
      Flight::redirect("/delit-routier/$id_delit");
      exit();
    }
  }

  Flight::view()->display('edit/route.twig', array(
    'delit' => $delit, // Info du delit
    'civil' => $personne, // Informations sur la personne
    'voiture' => $voiture, // Informations sur le véhicule
    'detail' => Delit::getinfoDelit($delit->delit_ref), // Détail du délit
    'proprio' => Personne::getinfoPersonne($voiture->proprio), // Propriétaire du véhicule
    'ouvert' => $ouvert, // Policier ayant inscrit le casier
    'fermer' => Agent::getInfoAgentID($delit->acquite_par), // Policier ayant fermé le casier
    'policier' => Agent::getInfoAgentIDUser($personne->id)
  ));
});

Flight::route('/delit-routier/@id_delit/modification', function($id_delit) {
  verif_connecter();
  /* Variable récupéré dans le get */
  $rapport = $_POST['rapport'];
  /* Variable récupéré dans le get */
  $agent = Agent::getInfoAgent();

  if ($agent->editer == 0) {
    Flight::redirect("/delit-routier/$id_delit/edit");
    exit();
  }

  $oldinfo = Route::getRoute($id_delit);

  if ($oldinfo->remarque != $rapport) {
    addHistorique($agent->matricule, "3¤3¤0¤" . $id_delit . "¤" . $oldinfo->remarque . "¤" . $rapport);
  }

  editRapportRoute((int)$id_delit, $rapport);

  Flight::redirect("/delit-routier/$id_delit");
});

Flight::route('/delit-routier/@id_delit/@etat', function($id_delit, $etat) {
  verif_connecter();
  $agent = Agent::getInfoAgent();

  if ($agent->editer == 0) {
    Flight::redirect("/delit-routier/$id_delit");
    exit();
  }
  closeRoute($id_delit, $etat, $agent->lspd_id);
  addHistorique($agent->matricule, "5¤0¤1¤" . $id_delit . "¤" . $etat);

  Flight::redirect("/delit-routier/$id_delit");
});

?>
