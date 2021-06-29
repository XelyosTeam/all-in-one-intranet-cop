<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/ajouter/casier', function() {
  Flight::view()->display('add/add_casier.twig', array(
    'personnes' => Personne::getListPersonne(2),
    'leger' => Delit::getListDelit(2),
    'moyen' => Delit::getListDelit(3),
    'grave' => Delit::getListDelit(4)
  ));
});

Flight::route('/ajouter/casier-routier', function() {
  verif_connecter();

  Flight::view()->display('add/add_delit_route.twig', array(
    'personnes' => Personne::getListPersonne(2),
    'routes' => Delit::getListDelit(1),
    'voitures' => Voiture::getListCar()
  ));
});

Flight::route('/ajouter/civil', function() {
  verif_connecter();
  Flight::view()->display('add/add_civil.twig');
});

Flight::route('/ajouter/plainte', function() {
  verif_connecter();

  Flight::view()->display('add/add_plainte.twig', array(
    'personnes' => Personne::getListPersonne(2)
  ));
});

Flight::route('/ajouter/arme', function() {
  verif_connecter();

  Flight::view()->display('add/add_weapon.twig', array(
    'armes' => modelArme::getList(),
    'personnes' => Personne::getListPersonne(2)
  ));
});

Flight::route('/ajouter/vehicule', function() {
  verif_connecter();

  Flight::view()->display('add/add_car.twig', array(
    'voitures' => ModelesV::getListCarModele(),
    'personnes' => Personne::getListPersonne(2)
  ));
});
?>
