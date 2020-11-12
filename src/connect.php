<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */
  use Josantonius\Session\Session; // Pour utiliser les variables de sessions

  function add_historique_connexion($matricule, $mdp, $message, $etat, $ip) {
    $vehicule = Model::factory('Historique')->create();
    $vehicule->set(array(
                  'matricule_utilise' => $matricule,
                  'mpd_utilise' => $mdp,
                  'adresse_ip' => $ip,
                  'etat' => $etat,
                  'commentaire' => $message,
                  'date_connexion' => date("Y-m-d H:i:s")
                )
              );
    $vehicule->save();
  }

  function nb_echec($ip) {
    $nb_connexion_max = serveurIni('Parametre', 'echec_maximum');
    $nb_echec = Historique::getNbEchec($ip); // Récupération du nombre d'échec de l'utilisateur
    if ($nb_echec > $nb_connexion_max) {
      return TRUE;
    }
    return FALSE;
  }

  function protocole_connexion($matricule, $mdp, $ip) {
    if (nb_echec($ip)) { // Vérifie le nombre d'échec de l'ip
      $message = "Tentative de connexion trop importante";
      $etat = "Echec";
      add_historique_connexion($matricule, $mdp, $message, $etat, $ip);
      return 1;
    }

    $info = Agent::getInfoAgentMatricule($matricule);

    /* Vérifie que le matricule existe */
    if (!$info) {
      $message = "Le matricule saisi n'est pas correcte";
      $etat = "Echec";
      add_historique_connexion($matricule, $mdp, $message, $etat, $ip);
      return 2;
    }

    /* Vérifie le matricule est toujours en service */
    if ($info->grade_id == 1) {
      $message = "Le matricule n'est plus en service";
      $etat = "Echec";
      add_historique_connexion($matricule, $mdp, $message, $etat, $ip);
      return 3;
    }

    /* Vérification du mot de passe */
    $info_p = Policier_t::getInfoAgentMatricule($info->matricule);
    $mdp_hashed = program_crypt($mdp, $info_p->Sel_de_table);

    if ($mdp_hashed == $info_p->Passwd) {
      $message = "Connexion du " . serveurIni('Faction', 'membre');
      $etat = "Réussite";
      add_historique_connexion($matricule, $mdp_hashed, $message, $etat, $ip);
      variable_session($info->matricule); // Enregistrement du matricule en variables de sessions
      return $info->grade . " " . $info->nom;
    }
    else if ($mdp_hashed != $info_p->Passwd) {
      $message = "Le mot de passe entré n'est pas valide";
      $etat = "Echec";
      add_historique_connexion($matricule, $mdp, $message, $etat, $ip);
      return 4;
    }
  }

  function variable_session($matricule) {
    Session::set("matricule", $matricule);
  }

?>
