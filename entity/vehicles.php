<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Voiture extends Model {
  public static $_table = "info_voiture"; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getListCarPersonne($personne) { // Liste des voitures d'une personne
    return Voiture::where(array('proprio' => $personne, 'circulation' => '1'))
                  ->where_not_equal('couleur', serveurIni('Faction', 'couleurVehiculeBDD'))
                  ->order_by_asc('plaque')
                  ->find_many();
  }
  public static function getListCarPolice($personne) { // Liste des voitures d'une personne
    return Voiture::where(array(
                      'proprio' => $personne,
                      'couleur' => serveurIni('Faction', 'couleurVehiculeBDD')
                    ))
                  ->order_by_asc('plaque')
                  ->find_many();
  }
  public static function getListCarTri($modele, $plaque, $couleur) { // Liste des voitures en fonction d'un tri
    return Voiture::where_like(array(
                    'nom' => "%$modele%",
                    'plaque' => "%$plaque%",
                    'couleur' => "%$couleur%"
                  ))
                  ->where_not_equal('couleur', serveurIni('Faction', 'couleurVehiculeBDD'))
                  ->order_by_asc('nom')
                  ->order_by_asc('plaque')
                  ->find_many();
  }
  public static function getCar($plaque) { // Information d'une voiture avec la plaque
    return Voiture::where('plaque', $plaque)->find_one();
  }
  public static function getListCar() { // Information d'une voiture avec la plaque
    return Voiture::order_by_asc(array('plaque', 'nom'))->find_many();
  }
  public static function getCarID($id_v) { // Information d'une voiture avec l(ID)
    return Voiture::where('v_id', $id_v)->find_one();
  }
}
?>
