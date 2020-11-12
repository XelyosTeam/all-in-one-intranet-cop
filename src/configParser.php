<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */
  use NoiseLabs\ToolKit\ConfigParser\ConfigParser;

  function serveurIni($categorie, $param) {
    $params = new ConfigParser();
    $params->read('server.ini');
    return $params[$categorie][$param];

  }

  function editserveurIni($categorie, $param, $value) {
    $params = new ConfigParser();
    $params->read('server.ini');
    $params->set($categorie, $param, $value);
    $params->save();
  }

?>
