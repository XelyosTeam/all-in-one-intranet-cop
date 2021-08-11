<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Rapport extends Model {
  public static $_table = 'lspd_rapport'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getListRapportID($lspd_id) {
    return Rapport::where('concerne', $lspd_id)
                  ->find_many();
  }
  
  public static function getRapportID($raport_id) {
    return Rapport::where('id', $raport_id)
                  ->find_one();
  }
  
  public static function liste_non_vu($lspd_id) {
    return Rapport::raw_query('SELECT * FROM info_rapport WHERE id NOT IN (SELECT rapport_id FROM lspd_rapport_vu WHERE policier_id = :lspd_id) AND lspd_id <> :lspd_id', array('lspd_id' => $lspd_id))
                  ->find_many();
  }
}
?>
