<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Vehicules extends Model {
  public static $_table = "vehicules"; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getCarAdd($id_cop) {
    return Vehicules::where('enregistre_par', $id_cop)
                    ->count();
  }
}
?>
