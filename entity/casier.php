<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Casier extends Model {
  public static $_table = 'info_casier'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getListCasier($user_id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
    return Casier::where('id_personne', $user_id)
                 ->order_by_asc(array('etat', 'nom'))
                 ->find_many();
  }
  public static function getDelit($delit_id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
    return Casier::where('id_delit', $delit_id)
                 ->find_one();
  }
  public static function getIDCasier($id, $matricule) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
    return Casier::where(array('id_personne' => $id, 'enregistrer_par' => $matricule, 'etat' => '0'))
                 ->order_by_desc('id_delit')
                 ->find_one();
  }
  public static function getAmende($id) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
    return Casier::where(array('id_personne' => $id, 'etat' => '0'))
                 ->sum('amende');
  }
  public static function getPrison($id) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
    return Casier::where(array('id_personne' => $id, 'etat' => '0'))
                 ->sum('prison');
  }
  public static function getCasierEnCours($id) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
    return Casier::where(array('id_personne' => $id, 'etat' => '0'))
                 ->find_many();
  }
}
?>
