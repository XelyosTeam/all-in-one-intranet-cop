<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Plainte extends Model {
  public static $_table = 'lspd_plainte'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getListPlainte($user_id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
    return Plainte::where('plainte_sur', $user_id)
                  ->order_by_asc('etat')
                  ->find_many();
  }
  
  public static function getPlainte($plainte_id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
    return Plainte::where('id', $plainte_id)
                  ->find_one();
  }
  
  public static function getIDPlainte($deposeur, $citoyen, $agent) { // On récupère la liste du casier judiciaire avec l'ID de la personne
    return Plainte::where(
                      array(
                        'deposeur' => $deposeur,
                        'plainte_sur' => $citoyen,
                        'enregistrer_par' => $agent
                    ))
                   ->order_by_desc('id')
                   ->find_one();
  }
  
  public static function getPlainteOpen($id_cop) {
    return Plainte::where('enregistrer_par', $id_cop)
                  ->count();
  }
  
  public static function getPlainteClose($id_cop) {
    return Plainte::where('fermer_par', $id_cop)
                  ->count();
  }
}
?>
