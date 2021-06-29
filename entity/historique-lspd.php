<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Historique_LSPD extends Model {
  public static $_table = 'lspd_historique'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getAction($matricule) {
    return Historique_LSPD::where('matricule', $matricule)
                          ->order_by_desc('id')
                          ->find_many();
  }
  public static function getNbAction($matricule) {
    return Historique_LSPD::where('matricule', $matricule)
                          ->count();
  }
}
?>
