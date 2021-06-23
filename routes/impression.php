<?php
use Josantonius\Session\Session;

Flight::route('/impression/@type/@numero', function($type, $numero) {
  verif_connecter();
  $impression = new generatePDF();

  switch ($type) {
    case 'civil':
        $civil = Personne::getinfoPersonne($numero);
        if (!$civil) {
          Flight::redirect("/civil/$numero");
          return;
        }
        $impression->civil($civil, $numero);
      break;
    default:
      Flight::redirect("/connexion");
      break;
  }
});
?>
