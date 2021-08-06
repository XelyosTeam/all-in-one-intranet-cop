<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Folder extends Model {
  public static $_table = 'lspd_folder'; // Liaison avec la table

  public static function getFoldersById($foldersId) {
    return Folder::where('id', $foldersId)->find_one();
  }

  public static function getSubfolderById($foldersId) {
    return Folder::where('parent', $foldersId)
                  ->order_by_asc('title')
                  ->find_many();
  }

  public static function createSubfolder($parentID, $title, $description, $habilitations, $grades, $matricule) {
    Folder::create()->set([
      'title' => $title,
      'description' => $description,
      'parent' => $parentID,
      'habilitations' => implode(",", $habilitations),
      'grades' => implode(",", $grades),
      'createdby' => $matricule
    ])->save();
  }
}
?>
