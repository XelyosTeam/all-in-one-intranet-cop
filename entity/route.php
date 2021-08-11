<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Route extends Model {
  public static $_table = 'info_route'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getListDelitByV($vehicule_id) { // On récupère la liste des délits routier avec l'ID d'un véhicule
    return Route::where('v_id', $vehicule_id)
                ->order_by_asc('etat')
                ->find_many();
  }
  
  public static function getListDelitByC($conducteur_id) { // On récupère la liste des délits routier avec l'ID du conducteur
    return Route::where('conducteur_id', $conducteur_id)
                ->order_by_asc('etat')
                ->find_many();
  }
  
  public static function getRoute($delit_id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
    return Route::where('delit_id', $delit_id)
                ->find_one();
  }
  
  public static function getIDRoute($vehicle, $conducteur, $enregistreur) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
    return Route::where(array(
                  'v_id' => $vehicle,
                  'conducteur_id' => $conducteur,
                  'etat' => '0'
                ))
                ->order_by_desc('delit_id')
                ->find_one();
  }
  
  public static function getAmende($id) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
    return Route::where(array('conducteur_id' => $id, 'etat' => '0'))
                ->sum('amende');
  }
  
  public static function getPrison($id) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
    return Route::where(array('conducteur_id' => $id, 'etat' => '0'))
                ->sum('prison');
  }
  
  public static function getRouteEnCours($id) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
    return Route::where(array('conducteur_id' => $id, 'etat' => '0'))
                ->find_many();
  }
}
?>
