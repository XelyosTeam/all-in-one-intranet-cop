<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/casier-judiciaire/@id_delit', function($id_delit) {
  verif_connecter();
  $delit = Casier::getDelit($id_delit);
  if (!$delit) {
    Flight::redirect("/ajouter/casier");
    return;
  }
  $personne = Personne::getinfoPersonne($delit->id_personne);

  // Traitement de l'information date
  if ($personne->permis == 0) {
    $value = traitementDate($personne->date_permis);
    $personne->date_permis = $value[0]; // On modifie la valeur de date permis
    $personne->date_trad = $value[1];
  }

  // Traitement inforamtion texte
  $delit->remarque = renderHTMLFromMarkdown(htmlspecialchars(strip_tags(urldecode($delit->remarque))));

  Flight::view()->display('fiche/detail_casier.twig', array(
    'delit' => $delit, // Info du casier
    'civil' => $personne, // Informations sur la personne
    'ouvert' => Agent::getInfoAgentID($delit->enregistrer_par), // Policier ayant incrit le casier
    'fermer' => Agent::getInfoAgentID($delit->acquite_par), // Policier ayant fermé le casier
    'policier' => Agent::getInfoAgentIDUser($personne->id)
  ));
});

Flight::route('/casier-judiciaire/@id_delit/edit', function($id_delit) {
  verif_connecter();
  $delit = Casier::getDelit($id_delit);
  $personne = Personne::getinfoPersonne($delit->id_personne);
  $agent = Agent::getInfoAgent();

  if ($delit->etat != 0) {
    Flight::redirect("/casier-judiciaire/$id_delit");
    exit();
  }

  if ($agent->hab_1 != 2) {
    if ($agent->matricule != $ouvert->matricule) {
      Flight::redirect("/casier-judiciaire/$id_delit");
      exit();
    }
  }

  // Traitement inforamtion texte
  $delit->remarque = urldecode($delit->remarque);

  Flight::view()->display('edit/casier.twig', array(
    'delit' => $delit, // Info du casier
    'civil' => $personne, // Informations sur la personne
    'detail' => Delit::getinfoDelit($delit->type_d), // Détail du délit
    'ouvert' => Agent::getInfoAgentID($delit->enregistrer_par), // Policier ayant incrit le casier
    'fermer' => Agent::getInfoAgentID($delit->acquite_par), // Policier ayant fermé le casier
    'policier' => Agent::getInfoAgentIDUser($personne->id)
  ));
});

Flight::route('/casier-judiciaire/@id_delit/modification', function($id_delit) {
  verif_connecter();
  /* Variable récupéré dans le get */
  $rapport = urlencode($_POST['rapport']);
  /* Variable récupéré dans le get */
  $agent = Agent::getInfoAgent();
  $act = Casier::getDelit($id_delit);

  if ($act->remarque != $rapport) {
    echo "Ici";
    addHistorique($agent->matricule, "3¤0¤0¤" . $id_delit . "¤" . $act->remarque . "¤" . $rapport);
  }

  if ($agent->editer == 0) {
    echo "La";
    Flight::redirect("/casier-judiciaire/$id_delit/edit");
    exit();
  }

  editRapportCasier((int)$id_delit, $rapport);

  Flight::redirect("/casier-judiciaire/$id_delit");
});

Flight::route('/casier-judiciaire/@id_delit/@etat', function($id_delit, $etat) {
  verif_connecter();
  $agent = Agent::getInfoAgent();

  if ($agent->editer == 0) {
    Flight::redirect("/casier-judiciaire/$id_delit");
    exit();
  }

  closeCasier($id_delit, $etat, $agent->lspd_id);
  addHistorique($agent->matricule, "5¤0¤0¤" . $id_delit . "¤" . $etat);

  Flight::redirect("/casier-judiciaire/$id_delit");
});
?>
