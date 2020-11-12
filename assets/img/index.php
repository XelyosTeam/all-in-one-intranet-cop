<?php
  use Josantonius\Session\Session;
  Session::destroy(); // Desturction de session

  header('Location: /test'); // Renvoi vers la page de connexion
?>
