<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Historique_Document extends Model {
  public static $_table = 'lspd_document_historique'; // Liaison avec la table

  public static function logModif($documentID, $old, $new, $agent) {
    Historique_Document::create()->set([
      'id_document' => $documentID,
      'old_content' => $old,
      'new_content' => $new,
      'updateby' => $agent->lspd_id
    ])->save();
  }
}
?>
