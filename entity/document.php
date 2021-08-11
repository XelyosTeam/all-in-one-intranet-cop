<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Document extends Model {
  public static $_table = 'lspd_document'; // Liaison avec la table

  public static function uploadFile($parentID, $title, $description, $type, $content, $habilitations, $grades, $createdby) {
    return Document::create()->set([
      'title' => $title,
      'description' => $description,
      'parent' => $parentID,
      'type' => $type,
      'content' => $content,
      'habilitations' => implode(",", $habilitations),
      'grades' => implode(",", $grades),
      'createdby' => $createdby
    ])->save();
  }

  public static function getDocuments($parentID) {
    return Document::where(array('parent' => $parentID, 'isdeleted' => False))
                  ->order_by_asc('title')
                  ->find_many();
  }

  public static function getDocumentsDeleted($parentID) {
    return Document::where(array('parent' => $parentID, 'isdeleted' => True))
                  ->order_by_asc('title')
                  ->find_many();
  }

  public static function getDocument($docID) {
    return Document::where('id', $docID)->find_one();
  }

  public static function getLastIdInRep($parentID, $type) {
    return Document::where(array('parent' => $parentID, 'type' => $type))
                 ->order_by_desc('id')
                 ->find_one();
  }
}
?>
