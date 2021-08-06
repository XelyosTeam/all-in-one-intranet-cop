<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/administration/ajout', function() {
  verif_connecter();
  verif_admin(); // Vérifie si l'utilisateur fait partie des administrateurs

  Flight::view()->display('administration/ajout_systeme.twig', array(
    'agents' => Agent::getListNonProf(),
    'personnes' => Personne::nonPhoto(),
    'armes' => categorieArme::getList()
  ));
});

Flight::route('/administration/details/echec', function() {
  verif_connecter();
  verif_admin(); // Vérifie si l'utilisateur fait partie des administrateurs
  if (!isset($_POST['adresse'])) {
    Flight::redirect("/administration/modification");
    exit();
  }
  else {
    $ip = $_POST['adresse'];
  }

  Flight::view()->display('administration/detail_echec.twig', array(
    'ip' => $ip,
    'listes' => Historique::getEchecAdresse($ip)
  ));
});

Flight::route('/administration/modification', function() {
  verif_connecter();
  verif_admin(); // Vérifie si l'utilisateur fait partie des administrateurs

  Flight::view()->display('administration/modification_systeme.twig', array(
    'agents' => Agent::getListAgent(),
    'echecs' => Historique::getEchec(),
    'visibles' => Personne::getListPersonne(2),
    'invisibles' => Personne::getListPersonne(1),
    'legers' => Delit::getListDelit(2),
    'moyens' => Delit::getListDelit(3),
    'graves' => Delit::getListDelit(4),
    'routes' => Delit::getListDelit(1)
  ));
});

Flight::route('/administration/parametre-serveur', function() {
  verif_connecter();
  verif_admin(); // Vérifie si l'utilisateur fait partie des administrateurs

  Flight::view()->display('administration/server_param.twig', array(
    'fail' => serveurIni('Parametre', 'echec_maximum'),
    'grade' => serveurIni('Faction', 'gradeLevelBiffure')
  ));
});

Flight::route('/administration/parametre-serveur/modification', function() {
  verif_connecter();
  verif_admin(); // Vérifie si l'utilisateur fait partie des administrateurs

  $matricule = Session::get('matricule');

  if ($_POST['failed_connexion'] != '') {
    editserveurIni('Parametre', 'echec_maximum', $_POST['failed_connexion']);
    addHistorique($matricule, "0¤0¤0¤" . $_POST['failed_connexion']);
  }

  if ($_POST['periode_drive'] != '') {
    editserveurIni('Parametre', 'permis_type', $_POST['periode_drive']);
    addHistorique($matricule, "0¤0¤1¤" . $_POST['periode_drive']);
  }

  if ($_POST['time_dirve'] != '') {
    editserveurIni('Parametre', 'permis_time', $_POST['time_dirve']);
    addHistorique($matricule, "0¤0¤2¤" . $_POST['time_dirve']);
  }

  if ($_POST['level_censure'] != '') {
    editserveurIni('Faction', 'gradeLevelBiffure', $_POST['level_censure']);
    addHistorique($matricule, "0¤0¤3¤" . $_POST['level_censure']);
  }

  if ($_POST['etat_recrut'] != '') {
    editserveurIni('Faction', 'etatRecrutement', $_POST['etat_recrut']);
    addHistorique($matricule, "0¤0¤4¤" . $_POST['etat_recrut']);
  }
});

Flight::route('/administration/historique', function() {
  verif_connecter();
  verif_admin();

  Flight::view()->display('administration/historique.twig', array(
    'cops' => Agent::getListAgent(),
    'oldcops' => Agent::getListOldAgent()
  ));
});

Flight::route('/administration/historique/get_info', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  verif_admin();

  $agent = Agent::getInfoAgentMatricule($_POST['matricule_cop']);
  $liste = Historique_LSPD::getAction($_POST['matricule_cop']);

  if ($agent == NULL) { // L'agent n'existe pas
    $data = [
      'etat' => 0,
      'nom' => "Erreur dans l'envoi du matricule"
    ];
  }
  else { // L'agent existe
    // On récupère le nom de l'individu
    if ($agent->grade_id != 1) {
      $name = $agent->grade . " " . $agent->nom;
    }
    else {
      $name = $agent->nom . " " . $agent->prenom;
    }

    if ($liste == NULL) { // L'agent n'a pas envore fait d'action
      $data = [
        'etat' => 0,
        'nom' => $name
      ];
    }
    else {
      $i = 0;
      foreach ($liste as $key => $action) {

        try {
          $event = decryptHistorique($action->contenu);
        } catch (\Exception $e) {
          $event = $action->contenu;
        }

        $data[$key] = [
          'id' => $action->id,
          'date' => $action->date_even,
          'event' => $event
        ];
        $i++;
      }

      $data[$i] = ['etat' => $name];
    }
  }

  Flight::json($data);
});

Flight::route('/administration/historique_connexion/get_info', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  verif_admin();

  $agent = Agent::getInfoAgentMatricule($_POST['matricule_cop']);
  $liste = Historique::getListMatricule($_POST['matricule_cop']);

  if ($agent == NULL) { // L'agent n'existe pas
    $data = [
      'etat' => 0,
      'nom' => "Erreur dans l'envoi du matricule"
    ];
  }
  else { // L'agent existe
    // On récupère le nom de l'individu
    if ($agent->grade_id != 1) {
      $name = $agent->grade . " " . $agent->nom;
    }
    else {
      $name = $agent->nom . " " . $agent->prenom;
    }

    if ($liste == NULL) { // L'agent n'a pas envore fait d'action
      $data = [
        'etat' => 0,
        'nom' => $name
      ];
    }
    else {
      $i = 0;
      foreach ($liste as $key => $action) {
        $data[$key] = [
          'id' => $action->id,
          'date' => $action->date_connexion,
          'content' => $action->commentaire
        ];
        $i++;
      }

      $data[$i] = ['etat' => $name];
    }
  }

  Flight::json($data);
});

Flight::route('/administration/historique/get_stats', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  verif_admin();

  $agent = Agent::getInfoAgentMatricule($_POST['matricule_cop']);

  if ($agent) {
    $data = [
      'etat' => 1,
      'login' => Historique::getNbConnect($agent->matricule),
      'action' => Historique_LSPD::getNbAction($agent->matricule),
      'open' => (Casier_t::getCasierOpen($agent->lspd_id)) + (Plainte::getPlainteOpen($agent->lspd_id)) + (Route_t::getRouteOpen($agent->lspd_id)),
      'close' => (Casier_t::getCasierClose($agent->lspd_id)) + (Plainte::getPlainteClose($agent->lspd_id)) + (Route_t::getRouteClose($agent->lspd_id)),
      'car' => Vehicules::getCarAdd($agent->lspd_id)
    ];
  }
  else {
    $data = [
      'etat' => 0
    ];
  }

  Flight::json($data);
});

Flight::route('/add/prof', function() {
  verif_connecter();
  verif_admin();
  /* Variable de POST */
  $id_cop = $_POST['nom_cop'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  updateProf($id_cop);
  addHistorique($agent->matricule, "0¤4¤0¤". Agent::getInfoAgentID($id_cop)->matricule);
  Flight::redirect("/administration/ajout");
});

Flight::route('/delete/@adress_ip', function($adresse_ip) {
  verif_connecter();
  verif_admin();

  $adresse_ip = str_replace('-', '.', $adresse_ip);
  deleteIP($adresse_ip);
  addHistorique(Agent::getInfoAgent()->matricule, "0¤2¤1¤$adresse_ip");
  Flight::redirect("/administration/modification"); // Redirige vers la page
});
?>
