<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/discussion-interne', function() {
  verif_connecter();
  Flight::view()->display('tchat.twig');
});

Flight::route('/chat', function() {
  verif_connecter();
  /* On doit analyser la demande faite via l'URL (GET) afin de déterminer
  si on souhaite récupérer les messages ou en écrire un  */
  $task = "list";

  if(array_key_exists("task", $_GET)) {
    $task = $_GET['task'];
  }

  if($task == "write") {
    postMessage();
  } else {
    getMessages();
  }
});
?>
