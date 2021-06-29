<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class RapportVu extends Model {
  public static $_table = 'lspd_rapport_vu'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function non_vu($lspd_id, $rapport_id) {
    return RapportVu::where(array('policier_id' => $lspd_id, 'rapport_id' => $rapport_id))
                    ->find_one();
  }
}
?>
