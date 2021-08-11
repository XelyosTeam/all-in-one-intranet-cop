<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Arme extends Model {
  public static $_table = 'info_arme'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getListIDUser($id) {
    return Arme::where('proprio', $id)
               ->order_by_asc(array('prefix', 'numero'))
               ->find_many();
  }
  
  public static function getInfoNumero($numero) {
    return Arme::where('numero', $numero)
               ->find_one();
  }
}
?>
