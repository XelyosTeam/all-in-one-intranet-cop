<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Agent extends Model {
  public static $_table = 'info_lspd'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getInfoAgent() { // Informations d'un agent de police avec le matricule
    return Agent::where('matricule', Session::get('matricule'))
                ->find_one();
  }
  public static function getInfoAgentMatricule($matricule) { // Informations d'un agent de police avec le matricule
    return Agent::where('matricule', $matricule)
                ->find_one();
  }
  public static function getInfoAgentID($id_police) { // Informations d'un agent de police avec l'ID
    return Agent::where('lspd_id', $id_police)
                ->find_one();
  }
  public static function getInfoAgentIDUser($id_police) { // Informations d'un agent de police avec l'ID
    return Agent::where('user_id', $id_police)
                ->find_one();
  }
  public static function getListAgentTri($nom, $prenom, $matricule) {
    return Agent::where_like(array(
                     'nom' => "%$nom%",
                     'prenom' => "%$prenom%",
                     'matricule' => "%$matricule%"
                   ))
                   ->where_not_equal(array('grade_id' => 1, 'lspd_id' => serveurIni('Par_defaut', 'id_lspd'))) // On retire l'agent n'étant pas censé être visible dans les listes
                   ->order_by_asc(array('nom', 'prenom'))
                   ->find_many();
  }
  public static function getListAgent() {
    return Agent::where_not_equal(array('grade_id' => 1, 'lspd_id' => serveurIni('Par_defaut', 'id_lspd'))) // On retire l'agent n'étant pas censé être visible dans les listes
                ->order_by_asc(array('nom', 'prenom'))
                ->find_many();
  }
  public static function getListOldAgent() {
    return Agent::where_like('grade_id', 1)
                ->where_not_equal('lspd_id', serveurIni('Par_defaut', 'id_lspd')) // On retire l'agent n'étant pas censé être visible dans les listes
                ->order_by_asc(array('nom', 'prenom'))
                ->find_many();
  }
  public static function getListNonProf() {
    return Agent::where_not_equal(array('grade_id' => 1, 'hab_1' => 3))
                ->find_many();
  }
}
?>
