<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */
  use Josantonius\Session\Session; // Pour utiliser les variables de sessions

  function addCop($id) {
    do {
      $matricule_cop = rand(100000, 999999);
    }
    while(Agent::getInfoAgentMatricule($matricule_cop));

    $salt = bin2hex(random_bytes(15)); // Génération du sault
    $mdp_temp = program_crypt($matricule_cop, $salt);

    insertCop($id, $matricule_cop, $salt, $mdp_temp); // Insertion dans la table policier
    update_metier($id); // update du métier dans la table
    $info = Policier_t::getInfoAgentMatricule($matricule_cop); // Récupératoin de l'ID en fonction du matricule
    create_habilitation($info->id); // Ajout des habilitations
    create_autorisation($info->id); // Ajout des autorisations

    return $matricule_cop;
  }

  function biffage($message) {
    $etat = 0;
    for ($i=0; $i < strlen($message) ; $i++) {
      if ($message[$i] == '[') {
        $etat = 1;
        $message = decalement($message, $i, 5);
        $message[$i] = "<";
        $message[$i+1] = "s";
        $message[$i+2] = "p";
        $message[$i+3] = "a";
        $message[$i+4] = "n";
        $message[$i+5] = ">";
        $i = $i+6;
      }

      if ($message[$i] == ']') {
        $etat = 0;
        $message = decalement($message, $i, 6);
        $message[$i] = "<";
        $message[$i+1] = "/";
        $message[$i+2] = "s";
        $message[$i+3] = "p";
        $message[$i+4] = "a";
        $message[$i+5] = "n";
        $message[$i+6] = ">";
        $i = $i+7;
      }

      if ($etat == 1) {
        $message[$i] = "\32";
      }
    }

    return $message;
  }

  function decalement($message, $pos, $taille) {
    $lng = strlen($message) - 1;

    /* Elongaution de la phrase*/
    for ($i=0; $i < $taille; $i++) {
      $message = $message . "0";
    }

    $lng2 = strlen($message) - 1;

    for ($j=0; $j < ($lng - $pos); $j++) {
      $temp = $message[$lng2 - $j];
      $message[$lng2 - $j] = $message[$lng - $j];
      $message[$lng - $j] = $temp;
    }

    return $message;
  }

  function inconnu($nom_temp) {
    $inconnu['nom'] = $nom_temp;
    $inconnu['prenom'] = $nom_temp;
    $inconnu['photo'] = "temp.png";
    $inconnu['DOB'] = date('y-m-d');
    $inconnu['phone'] = "000000";
    $inconnu['nationality'] = "Inconnu";
    $inconnu['sexe'] = "Inconnu";
    $inconnu['job'] = "Inconnu";
    $inconnu['ppa'] = 0;
    $inconnu['permis'] = 0;

    return $inconnu;
  }

  function traitement_amende($ac, $ar, $pc, $pr) {
    $info[0] = $ac + $ar; // Montant de l'amende total
    $info[1] = $pc + $pr; // Temps de prison total
    $info[2] = ($info[1] - ($info[1]%12))/12 . " ans et " . $info[1]%12 . " mois"; // Temps de prison en années
    $info[3] = ($info[1] - ($info[1]%12))/6 . " ans et " . ($info[1]%12)*serveurIni('PRISON', 'multiplicateur_prison') . " mois"; // Temps de prison en années

    return $info;
  }

  function traitementDate($date) {
    switch (serveurIni('Parametre', 'permis_type')) {
      case 'w': // Semaine
        $retour[0] = (int)((time() - $date)/604800);
        $retour[1] = $retour[0] . " semaines";
        break;
      case 'j': // Jour
        $retour[0] = (int)((time() - $date)/86400);
        $retour[1] = $retour[0] . " jours";
        break;
      case 'h': // Heure
        $retour[0] = (int)((time() - $date)/3600);
        $retour[1] = $retour[0] . " heures";
        break;
      case 'm': // Minute
        $retour[0] = (int)((time() - $date)/60);
        $retour[1] = $retour[0] . " minutes";
        break;
      case 's': // seconde
        $retour[0] = (int)(time() - $date);
        $retour[1] = $retour[0] . " secondes";
        break;
      default: // On met par défaut en semaine
        $retour[0] = (int)((time() - $date)/3600);
        $retour[1] = $retour[0] . " heures";
        break;
    }

    return $retour;
  }

  function verif_admin() {
    $info = Agent::getInfoAgentMatricule(Session::get('matricule'));
    if ($info->admin != 1) {
      Flight::redirect("/");
      exit();
    }
  }

  function verif_enseignant() {
    $agent = Agent::getInfoAgent();
    if ($agent->hab_1 < 1) {
      Flight::redirect("/");
      exit();
    }
  }

  function verif_connecter() {
    if (Session::get('matricule') == NULL) {
      Flight::redirect("/connexion");
      exit();
    }
  }

  /* Si on veut récupérer, il faut envoyer du JSON */
  function getMessages() {
    $messages = Chat::getList();
    foreach ($messages as $key => $msg) {
      $data[$key] = [
        'id' => $msg->id,
        'author' => $msg->author,
        'message'=>$msg->message,
        'side'=> $msg->side,
        'send_time'=> $msg->send_time
      ];
    }
    Flight::json($data);
  }

  function traitementRapport($rapport) {
    $etat = 0;
    for ($i=0; $i < strlen($rapport); $i++) {
      if ($rapport[$i] == "<" or $rapport[$i] == ">") {
        $rapport = isolationNonSouhaiter($rapport, $i, $rapport[$i]);
        $i++;
      }
    }
    return $rapport;
  }

  function isolationNonSouhaiter($rapport, $pos, $char) {
    $lng = strlen($rapport) - 1;

    for ($i=0; $i < 2; $i++) {
      $rapport = $rapport . "0";
    }

    $lng2 = strlen($rapport) - 1;

    for ($j=0; $j < ($lng - $pos); $j++) {
      $temp = $rapport[$lng2 - $j];
      $rapport[$lng2 - $j] = $rapport[$lng - $j];
      $rapport[$lng - $j] = $temp;
    }

    $rapport[$pos] = "'";
    $rapport[$pos+1] = $char;
    $rapport[$pos+2] = "'";

    return $rapport;
  }

?>
