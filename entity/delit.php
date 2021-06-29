<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Delit extends Model {
  public static $_table = 'lspd_delit'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getListDelit($type) { // On récupère les infos d'un délit
    return Delit::where('type_delit', $type)
                ->order_by_asc('intitule')
                ->find_many();
  }
  public static function getinfoDelit($delit_id) { // On récupère les infos d'un délit
    return Delit::where('id', $delit_id)
                ->find_one();
  }
}
?>
