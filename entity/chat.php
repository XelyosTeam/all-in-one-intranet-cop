<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Chat extends Model {
  public static $_table = 'chat'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getList() { // On récupère la liste du casier judiciaire avec l'ID de la personne
    return Chat::order_by_desc('send_time')
               ->limit(25)
               ->find_many();
  }
}
?>
