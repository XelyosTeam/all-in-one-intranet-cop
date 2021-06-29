<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Historique extends Model {
  public static $_table = 'lspd_historique_connexion'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getEchec() {
    return Historique::select_many('adresse_ip')
                     ->select_expr('COUNT(*)', 'NbEchecs')
                     ->where('etat', 'echec')
                     ->group_by('adresse_ip')
                     ->find_many();
  }
  public static function getNbConnect($matricule) {
    return Historique::where(array('etat' => 'Réussite', 'matricule_utilise' => $matricule))
                     ->count();
  }
  public static function getNbEchec($ip) {
    return Historique::where(array('etat' => 'echec', 'adresse_ip' => $ip))
                     ->count();
  }
  public static function getEchecAdresse($ip) {
    return Historique::where(array('etat' => 'echec', 'adresse_ip' => $ip))
                     ->find_many();
  }
  public static function getListMatricule($matricule) {
    return Historique::where('matricule_utilise', $matricule)
                     ->order_by_desc('id')
                     ->find_many();
  }
}
?>
