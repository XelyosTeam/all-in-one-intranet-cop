<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route("/" . serveurIni('Faction', 'membre'), function() {
  verif_connecter();
  verif_enseignant(); // Vérifie que la personne soit bien un enseignant
  /* Variable récupéré dans le get */
  $nom = $_GET['flic_name'];
  $prenom = $_GET['flic_firstname'];
  $mat = $_GET['flic_matricule'];
  /* Variable récupéré dans le get */

  Flight::view()->display('recherche/liste_policier.twig', array(
    'policiers' => Agent::getListAgentTri($nom, $prenom, $mat),
    'nom' => $nom,
    'prenom' => $prenom,
    'matricule' => $mat
  ));
});

Flight::route("/" . serveurIni('Faction', 'membre') . "/@matricule_policier", function($matricule_policier) {
  verif_connecter();
  $agent = Agent::getInfoAgentMatricule($matricule_policier);
  if (!$agent) {
    Flight::redirect("/");
    return;
  }

  Flight::view()->display('fiche/policier.twig', array(
    'agent' => $agent,
    'voitures' => Voiture::getListCarPolice($agent->user_id), // Liste des voitures
    'rapports' => Rapport::getListRapportID($agent->lspd_id), // Liste des rapports
    'armes' => Arme::getListIDUser($agent->user_id)
  ));
});

Flight::route("/" . serveurIni('Faction', 'membre') . "/@matricule_policier/edit", function($matricule_policier) {
  verif_connecter();
  verif_enseignant(); // Vérifie que l'agent est enseignant

  Flight::view()->display('edit/policier.twig', array(
    'agent' => Agent::getInfoAgentMatricule($matricule_policier),
    'grades' => Grade::getList()
  ));
});

Flight::route("/" . serveurIni('Faction', 'membre') . "/@matricule_policier/modification", function($matricule_policier) {
  verif_connecter();
  verif_enseignant(); // Vérifie que l'agent est enseignant

  /* Variable récupéré dans le get */
  $grade = $_POST['grade'];
  $note = $_POST['note'];
  if (isset($_POST['hab1'])) {
    $hab1 = $_POST['hab1'];
  }
  else {
    $hab1 = 1;
  }

  if (isset($_POST['hab2'])) {
    $hab2 = $_POST['hab2'];
  }
  else {
    $hab2 = 1;
  }

  if (isset($_POST['hab3'])) {
    $hab3 = $_POST['hab3'];
  }
  else {
    $hab3 = 1;
  }

  if (isset($_POST['hab4'])) {
    $hab4 = $_POST['hab4'];
  }
  else {
    $hab4 = 1;
  }

  if (isset($_POST['hab5'])) {
    $hab5 = $_POST['hab5'];
  }
  else {
    $hab5 = 1;
  }

  if (isset($_POST['hab6'])) {
    $hab6 = $_POST['hab6'];
  }
  else {
    $hab6 = 1;
  }

  if (isset($_POST['hab7'])) {
    $hab7 = $_POST['hab7'];
  }
  else {
    $hab7 = 1;
  }

  if (isset($_POST['hab8'])) {
    $hab8 = $_POST['hab8'];
  }
  else {
    $hab8 = 1;
  }

  if (isset($_POST['hab9'])) {
    $hab9 = $_POST['hab9'];
  }
  else {
    $hab9 = 1;
  }

  if (isset($_POST['hab10'])) {
    $hab10 = $_POST['hab10'];
  }
  else {
    $hab10 = 1;
  }

  if (isset($_POST['hab11'])) {
    $hab11 = $_POST['hab11'];
  }
  else {
    $hab11 = 1;
  }

  if (isset($_POST['hab12'])) {
    $hab12 = $_POST['hab12'];
  }
  else {
    $hab12 = 1;
  }

  if (isset($_POST['hab13'])) {
    $hab13 = $_POST['hab13'];
  }
  else {
    $hab13 = 1;
  }

  if (isset($_POST['hab14'])) {
    $hab14 = $_POST['hab14'];
  }
  else {
    $hab14 = 1;
  }

  if (isset($_POST['hab15'])) {
    $hab15 = $_POST['hab15'];
  }
  else {
    $hab15 = 1;
  }
  /* Variable récupéré dans le get */

  $id_cop = Agent::getInfoAgentMatricule($matricule_policier);

  if ($grade == 1) {
    editLicenciement($id_cop->lspd_id);
    addHistorique(Agent::getInfoAgent()->matricule, "1¤1¤1¤" . $matricule_policier);
  }
  else {
    AddHistoriqueModifAgent($id_cop, $matricule_policier, $grade, $hab1, $hab2, $hab3, $hab4, $hab5, $hab6, $hab7, $hab8, $hab9, $hab10, $hab11, $hab12, $hab13, $hab14, $hab15, $note);
    editCop($id_cop->lspd_id, $grade, $hab1, $hab2, $hab3, $hab4, $hab5, $hab6, $hab7, $hab8, $hab9, $hab10, $hab11, $hab12, $hab13, $hab14, $hab15, $note);
  }
  Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$matricule_policier");
});

?>
