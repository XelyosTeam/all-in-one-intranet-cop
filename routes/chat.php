<?php
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
