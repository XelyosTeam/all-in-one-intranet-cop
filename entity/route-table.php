<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Route_t extends Model {
  public static $_table = 'lspd_route'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getRouteOpen($id_cop) {
    return Route_t::where('enregistrer_par', $id_cop)
                  ->count();
  }
  
  public static function getRouteClose($id_cop) {
    return Route_t::where('acquite_par', $id_cop)
                  ->count();
  }
}
?>
