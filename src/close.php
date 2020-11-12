<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */

  function closeCasier($id, $etat, $matricule) {
    $modification = Model::factory('Casier_t')->where('id', $id)->find_one();
    $modification->set(array(
                    'acquite' => $etat,
                    'acquite_par' => $matricule,
                    'acquite_le' => date("Y-m-d")
                  ));
    $modification->save();
  }

  function closePlainte($id, $etat, $matricule) {
    $modification = Model::factory('Plainte')->where('id', $id)->find_one();
    $modification->set(array(
                    'etat' => $etat,
                    'fermer_par' => $matricule,
                    'fermer_le' => date("Y-m-d")
                  ));
    $modification->save();
  }

  function closeRoute($id, $etat, $matricule) {
    $modification = Model::factory('Route_t')->where('id', $id)->find_one();
    $modification->set(array(
                    'acquite' => $etat,
                    'acquite_par' => $matricule,
                    'acquite_le' => date("Y-m-d")
                  ));
    $modification->save();
  }

  function deleteIP($ip) {
    $adresse = Model::factory('Historique')->where_equal(array('adresse_ip' => $ip, 'etat' => 'Echec'))->delete_many();
  }

?>
