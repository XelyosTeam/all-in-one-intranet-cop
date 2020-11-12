<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */

  /* -------------------------------------------------------------- */
  /*                            Cryptage                            */
  /* -------------------------------------------------------------- */

  function cryptage_mdp($mdp_init) {
    $mdp_init = hash("sha512", $mdp_init); // On crypt une première fois le mot de passe
    $mdp_hashed = hash("sha512", $mdp_init); // On recrypt le tout

    return $mdp_hashed;
  }

  function program_crypt($mdp, $salt) {
    $mdp = cryptage_mdp($mdp); // On passe par un algo de cryptage
    $mdp = salage_mdp($mdp, $salt); // On applique un salage sur le mot de passe
    $mdp = cryptage_mdp($mdp); // On passe par un algo de cryptage

    return $mdp;
  }

  function salage_mdp($mdp_init, $salt) {
    /* Division du salt */
    return $salt . $mdp_init . $salt;
  }
?>
