<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class modelArme extends Model {
  public static $_table = 'lspd_liste_arme'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getList() {
    return modelArme::order_by_asc('nom')
                    ->find_many();
  }
  
  public static function getInfo($modele) { // On récupère l'image du véhicule en fonction du modèle
    return modelArme::where('id', $modele)
                    ->find_one();
  }
}
?>
