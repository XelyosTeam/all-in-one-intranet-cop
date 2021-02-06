<?php
  use Josantonius\Session\Session;
  Session::destroy(); // Desturction de session

  header('Location: /'); // Renvoi vers la page de connexion
?>
