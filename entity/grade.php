<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Grade extends Model {
  public static $_table = 'lspd_grade'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getList() {
    return Grade::order_by_desc('position')
                ->find_many();
  }
  
  public static function getGrade($id) {
    return Grade::where('id', $id)
                ->find_one();
  }
  
  public static function getGradePosition($pos) {
    return Grade::where('position', $pos)
                ->find_one();
  }
}
?>
