<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class InfoPlainte extends Model {
  public static $_table = 'info_plainte'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getListPlainte($nom, $prenom) { // On récupère les plaintes désposées en tout
    return InfoPlainte::where('etat', 1)
                  ->where_like(array(
                    'nom_1' => "%$nom%",
                    'prenom_1' => "%$prenom%"
                  ))
                  ->find_many();
  }
}
?>
