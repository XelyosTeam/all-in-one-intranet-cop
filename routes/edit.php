<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/edit/ppa/@etat', function($etat) {
  verif_connecter();
  /* Variable de POST */
  $personne = $_POST['nom_civil'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  if ($agent->hab_1 != 2) { // N'est pas autorisé à faire la manipulation
    Flight::redirect("/");
  }
  else {
    editPPA($personne, $etat);
    switch ($etat) {
      case '0':
        $message = "1¤0¤0¤$personne";
        break;
      case '1':
        $message = "1¤0¤1¤$personne";
        break;
      default:
        $message = "1¤0¤0¤$personne";
        break;
    }
    addHistorique($agent->matricule, $message);

    Flight::redirect("/ppa"); // Redirige vers la page
  }
});

Flight::route('/edit/rehabilitaton', function() {
  verif_connecter();
  /* Variable de POST */
  $id_cop = $_POST['id_cop'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  if ($agent->hab_1 != 2) { // N'est pas autorisé à ajouter
    Flight::redirect("/recrutement");
  }
  else {
    editCop($id_cop, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, "");
    $old = Agent::getInfoAgentID($id_cop);
    addHistorique($agent->matricule, "1¤1¤2¤$old->matricule");
    Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$old->matricule");
  }
});

Flight::route('/edit/registre/@etat', function($etat) {
  verif_connecter();
  verif_admin();
  /* Variable de POST */
  $personne = $_POST['nom_civil'];
  /* Variable de POST */

  editPresence($personne, $etat);
  switch ($etat) {
    case '0':
      $message = "0¤2¤2¤$personne";
      break;
    case '1':
      $message = "0¤2¤3¤$personne";
      break;
    default:
      $message = "0¤2¤2¤$personne";
      break;
  }
  addHistorique(Agent::getInfoAgent()->matricule, $message);

  Flight::redirect("/administration/modification"); // Redirige vers la page
});

Flight::route('/edit/licenciement', function() {
  verif_connecter();
  /* Variable de POST */
  $id_cop = $_POST['id_cop_li'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  if ($agent->hab_1 != 2) {
    Flight::redirect("/");
  }
  else {
    editLicenciement($id_cop);
    addHistorique($agent->matricule, "1¤1¤1¤" . Agent::getInfoAgentID($id_cop)->matricule);

    Flight::redirect("/recrutement"); // Redirige vers la page
  }
});

Flight::route('/edit/mdp', function() {
  verif_connecter();
  /* Variable de POST */
  $ancien = $_POST['ancien_mdp'];
  $new = $_POST['new_mdp'];
  $new2 = $_POST['confirm_mdp'];
  /* Variable de POST */

  if (($new != $new2) or ($ancien == $new2)) {
    Flight::redirect("/nouveau-mot-de-passe");
    exit();
  }

  $agent = Agent::getInfoAgent();
  $policier = Policier_t::getInfoAgentMatricule($agent->matricule);

  $ancien_ashed = program_crypt($ancien, $policier->Sel_de_table);

  if ($policier->Passwd != $ancien_ashed) { // L'ancien mot de passe n'est pas identique au nouveau
    Flight::redirect("/nouveau-mot-de-passe");
    exit();
  }

  $salt = bin2hex(random_bytes(15)); // Génération  du sault
  $new_mdp_hashed = program_crypt($new, $salt); // On génère le mot de passe avec le nouveau salt
  mise_a_jour_mdp($agent->matricule, $new_mdp_hashed, $salt);
  addHistorique($agent->matricule, "2¤0¤0");

  Flight::redirect("/connexion"); // Redirige vers la page
});

Flight::route('/edit/mdp/admin', function() {
  verif_connecter();
  verif_admin();
  /* Variable de POST */
  $id_lspd = $_POST['id'];
  $new_mdp = $_POST['dscpt'];
  /* Variable de POST */

  $salt = bin2hex(random_bytes(15)); // On génère un nouveau salt
  $new_mdp_hashed = program_crypt($new_mdp, $salt);
  $agent = Agent::getInfoAgentID($id_lspd);
  mise_a_jour_mdp($agent->matricule, $new_mdp_hashed, $salt);
  addHistorique(Agent::getInfoAgent()->matricule, "0¤2¤0¤$agent->matricule");
});

Flight::route('/delit/edit/value', function() {
  verif_connecter();
  verif_admin();

  $id = $_POST['id_delit'];
  $amd_delit = $_POST['amd_delit'];
  $tps_delit = $_POST['tps_delit'];

  $info = Delit::getinfoDelit($id);

  if (($info->amende != $amd_delit) and ($amd_delit != '')) {
    editDelitAmende($id, $amd_delit);
    addHistorique(Agent::getInfoAgent()->matricule, "0¤3¤0¤" . $id . "¤" . $info->amende . "¤" . $amd_delit);
  }

  if (($info->temps_prison != $tps_delit) and ($amd_delit != '')) {
    editDelitPrison($id, $tps_delit);
    addHistorique(Agent::getInfoAgent()->matricule, "0¤3¤1¤" . $id . "¤" . $info->temps_prison . "¤" . $tps_delit);
  }
});

?>
