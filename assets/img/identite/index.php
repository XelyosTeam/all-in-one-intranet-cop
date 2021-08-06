<?php
  session_start();
  session_destroy(); // Supprimer les variables de sessions

  header('Location: ../'); // Renvoi vers la page de connexion
?>
