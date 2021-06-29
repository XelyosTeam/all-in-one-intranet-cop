<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
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
