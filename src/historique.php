<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */

function decryptHistorique($code) {
  $code = explode("¤", $code);
  $prefix = $code[0] . "¤" . $code[1] . "¤" . $code[2];

  switch ($prefix) {
    /* Mode Admin */
    // Modification Paramètre Serveur
    case '0¤0¤0':
      return "ADMIN : Modification Paramètre Serveur - Echec Maximum - " . $code[3];
      break;
    case '0¤0¤1':
      return "ADMIN : Modification Paramètre serveur - Période permis - " . decryptPeriod($code[3]);
      break;
    case '0¤0¤2':
      return "ADMIN : Modification Paramètre serveur - Temps Permis - " . $code[3];
      break;
    case '0¤0¤3':
      return "ADMIN : Modification Paramètre serveur - Grade Censure - " . $code[3];
      break;
    case '0¤0¤4':
      return "ADMIN : Modification Paramètre serveur - Etat Recrutement - " . decryptRecrutement($code[3]);
      break;

    // Ajout
    case '0¤1¤1':
      return "ADMIN : Ajout Délit Routier - " . $code[3];
      break;
    case '0¤1¤2':
      return "ADMIN : Ajout Délit Léger - " . $code[3];
      break;
    case '0¤1¤3':
      return "ADMIN : Ajout Délit Moyen - " . $code[3];
      break;
    case '0¤1¤4':
      return "ADMIN : Ajout Délit Grave - " . $code[3];
      break;
    case '0¤1¤5':
      return "ADMIN : Ajout Véhicule - " . $code[3];
      break;
    case '0¤1¤6':
      return "ADMIN : Ajout Photo - " . $code[3] . " => " . getCivilName($code[3]);
      break;
    case '0¤1¤7':
      return "ADMIN : Ajout Catégorie Arme - " . $code[3] . " => " . $code[4];
      break;
    case '0¤1¤8':
      return "ADMIN : Ajout Modèle Arme - " . $code[3];
      break;

    // Modification
    case '0¤2¤0':
      return "ADMIN : Modification mot de passe - " . $code[3] . " =>" . getCopName($code[3]);
      break;
    case '0¤2¤1':
      return "ADMIN : Déblocage IP - " . $code[3];
      break;
    case '0¤2¤2':
      return "ADMIN : Suppression registre - " . $code[3] . " => " . getCivilName($code[3]);
      break;
    case '0¤2¤3':
      return "ADMIN : Réintégration registre - " . $code[3] . " => " . getCivilName($code[3]);
      break;

    // Modification Délit
    case '0¤3¤0':
      return "ADMIN : Modification Délit n°" . $code[3] . " - Amende : " . $code[4] . " >>> " . $code[5];
      break;
    case '0¤3¤1':
      return "ADMIN : Modification Délit n°" . $code[3] . " - Temps prison : " . $code[4] . " >>> " . $code[5];
      break;

    // Modification effectif
    case '0¤4¤0':
      return "ADMIN : Nouveau " . serveurIni('HABILITATION', 'hab_1') . " => " . $code[3] . " =>" . getCopName($code[3]);
      break;

    /* Ecole */

    // PPA
    case '1¤0¤0':
      return "Ecole : Suppression PPA - " . $code[3] . " => " . getCivilName($code[3]);
      break;
    case '1¤0¤1':
      return "Ecole : Ajout PPA - " . $code[3] . " => " . getCivilName($code[3]);
      break;

    // Recrutement
    case '1¤1¤0':
      return "Ecole : Ajout " . serveurIni('Faction', 'metierBDD') . " - " . $code[3] . " => " . getCopName($code[3]);
      break;
    case '1¤1¤1':
      return "Ecole : Licenciement " . serveurIni('Faction', 'metierBDD') . " - " . $code[3] . " => " . getCopName($code[3]);
      break;
    case '1¤1¤2':
      return "Ecole : Réhabilitation " . serveurIni('Faction', 'metierBDD') . " - " . $code[3] . " => " . getCopName($code[3]);
      break;
    case '1¤1¤3':
      return "Ecole : Candidature n°" . $code[3] . " acceptée";
      break;
    case '1¤1¤4':
      return "Ecole : Candidature n°" . $code[3] . " refusée";
      break;

    // Modification Fiche Policier
    case '1¤3¤0':
      return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement grade - " . $code[3] . " => "
            . getCopName($code[3]) . " || " . getGrade($code[4]) . " >>> " . getGrade($code[5]);
      break;
    case '1¤3¤1':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_1') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤2':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_2') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤3':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_3') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤4':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_4') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤5':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_5') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤6':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_6') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤7':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_7') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤8':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_8') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤9':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_9') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤10':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_10') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤11':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_11') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤12':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_12') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤13':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_13') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤14':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_14') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤15':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_15') . " - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '1¤3¤16':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement Note - " . $code[3] . " => "
          . getCopName($code[3]) . " || " . $code[4] . " >>> " . $code[5];
      break;

    /* Changement Info Perso */
    case '2¤0¤0':
      return "Changement du mot de passe";
      break;

    /* Changement Information */
    // Casier Civil
    case '3¤0¤0':
      return "Modification Casier Civil - Délit n°" . $code[3] . " || " . $code[4] . " >>> " . $code[5];
      break;

    // Fichie Civil
    case '3¤1¤0':
      return "Modification Fiche - Civil n°" . $code[3] . " - Téléphone => " . getCivilName($code[3]) . " || " . $code[4] . " >>> " . $code[5];
      break;
    case '3¤1¤1':
      return "Modification Fiche - Civil n°". $code[3] . " - Métier => " . getCivilName($code[3]) . " || " . $code[4] . " >>> " . $code[5];
      break;
    case '3¤1¤2':
      return "Modification Fiche - Civil n°" . $code[3] . " - Permis => " . getCivilName($code[3]) . " || " . decryptPermis($code[4]) . " >>> " . decryptPermis($code[5]);
      break;

    // Fichie Véhicule
    case '3¤2¤0':
      return "Modification Fiche - Véhicule n°" . $code[3] . " - Couleur => " . $code[4] . " >>> " . $code[5];
      break;
    case '3¤2¤1':
      return "Modification Fiche - Véhicule n°". $code[3] . " - Etat de circulation => " . getCirculation($code[4]) . " >>> " . getCirculation($code[5]);
      break;
    case '3¤2¤2': // v1.6.1
      return "Modification Fiche - Véhicule n°". $code[3] . " - Propriétaire => " . getCivilName($code[4]) . " >>> " . getCivilName($code[5]);
      break;

    // Casier Routier
    case '3¤3¤0':
      return "Modification Casier Routier - Délit n°" . $code[3] . " || " . $code[4] . " >>> " . $code[5];
      break;

    // Détails Plainte
    case '3¤4¤0':
      return "Modification Détails Plainte - Plainte n°" . $code[3] . " - Plainte sur " . getCivilName($code[4]) . " || " . $code[5] . " >>> " . $code[6];
      break;

    /* Ajout dans les registres */
    // Casier Judiciaire
    case '4¤0¤0':
      return "Ajout Casier Civil - Délit : " . $code[3] . " - Civil : " . getCivilName($code[4]);
      break;
    case '4¤0¤1':
      return "Ajout Civil : " . $code[3];
      break;
    case '4¤0¤2':
      return "Dépot plainte : de " . getCivilName($code[3]) . " sur " . getCivilName($code[4]);
      break;
    case '4¤0¤3':
      return "Dépot Rapprot Interne : de " . getCopName($code[3]) . " sur " . getCopName($code[4]);
      break;
    case '4¤0¤4':
      return "Ajout Délit Routier - Délit : " . $code[3] . " - Civil : " . getCivilName($code[4]) . " - Civil : " . $code[5];
      break;
    case '4¤0¤5':
      return "Ajout Véhicule : " . $code[3] . " - Propriétaire : " . getCivilName($code[4]);
      break;
    case '4¤0¤6':
      return "Ajout Arme : " . getArmeModel($code[3]) . " - Propriétaire : " . getCivilName($code[4]);
      break;

    /* Fermeture */
    case '5¤0¤0':
      return "Casier n°" . $code[3] . " - " . getEtatDelit($code[4]);
      break;
    case '5¤0¤1':
      return "Délit routier n°" . $code[3] . " - " . getEtatDelit($code[4]);
      break;
    case '5¤0¤2':
      return "Plainte n°" . $code[3] . " - " . getEtatDelit($code[4]);
      break;

    default:
      return "Erreur dans le décryptage de la solution";
      break;
  }
}

function getCivilName($id) {
  $personne = Personne::getinfoPersonne($id);
  if ($personne) {
    return $personne->nom . " " . $personne->prenom;
  }
  else {
    return "Civil n°$id inexistant";
  }

}

function getCopName($matricule) {
  $personne = Agent::getInfoAgentMatricule($matricule);
  if ($personne) {
    return $personne->nom . " " . $personne->prenom;
  }
  else {
    return "Agent matricule n° $matricule inexistant";
  }
}

function getArmeModel($type) {
  $arme = modelArme::getInfo($type);
  if ($arme) {
    return $arme->nom;
  }
  else {
    return "Model arme n° $type inexistant";
  }
}

function getGrade($grade) {
  $name = Grade::getGradePosition($grade)->nom;
  if ($name) {
    return $name;
  }
  else {
    return "Grade n°$grade inexistant";
  }
}

function decryptPeriod($type) {
  switch ($type) {
    case 'w':
      return "Semaine";
      break;
    case 'j':
      return "Jour";
      break;
    case 'h':
      return "Heure";
      break;
    case 'm':
      return "Minute";
      break;
    case 's':
      return "Seconde";
      break;
    default:
      return "Erreur dans le décryptage de la solution";
      break;
  }
}

function decryptRecrutement($type) {
  switch ($type) {
    case 0:
      return "Fermé";
      break;
    case 1:
      return "Ouvert";
      break;
    default:
      return "Erreur dans le décryptage de la solution";
      break;
  }
}

function decryptPermis($type) {
  switch ($type) {
    case 0:
      return "Retiré";
      break;
    case 1:
      return "Validé";
      break;
    default:
      return "Erreur dans le décryptage de la solution";
      break;
  }
}

function getEtat($etat) {
  switch ($etat) {
    case 0:
      return "Non";
      break;
    case 1:
      return "Formation";
      break;
    case 2:
      return "Validé";
      break;
    default:
      return "Erreur dans le décryptage de la solution";
      break;
  }
}

function getEtatDelit($etat) {
  switch ($etat) {
    case 1:
      return "Charges Abandonnées";
      break;
    case 2:
      return "Fermer";
      break;
    default:
      return "Erreur dans le décryptage de la solution";
      break;
  }
}

function getCirculation($etat) {
  switch ($etat) {
    case 0:
      return "Hors circulation";
      break;
    case 1:
      return "En circulation";
      break;
    default:
      return "Erreur dans le décryptage de la solution";
      break;
  }
}

/* Longue Fonction Ajout Historique */
function AddHistoriqueModifAgent($id_cop, $matricule_policier, $grade, $hab1, $hab2, $hab3, $hab4, $hab5, $hab6, $hab7, $hab8, $hab9, $hab10, $hab11, $hab12, $hab13, $hab14, $hab15, $note) {

  $agent = Agent::getInfoAgent();

  if ($id_cop->grade_id != $grade) {
    addHistorique($agent->matricule, "1¤3¤0¤" . $matricule_policier . "¤" . $id_cop->grade_id . "¤" . $grade);
  }

  if (serveurIni('HABILITATION', 'hab_1') != "") {
    if ($id_cop->hab_1 != $hab1) {
      addHistorique($agent->matricule, "1¤3¤1¤" . $matricule_policier . "¤" . $id_cop->hab_1 . "¤" . $hab1);
    }
  }

  if (serveurIni('HABILITATION', 'hab_2') != "") {
    if ($id_cop->hab_2 != $hab2) {
      addHistorique($agent->matricule, "1¤3¤2¤" . $matricule_policier . "¤" . $id_cop->hab_2 . "¤" . $hab2);
    }
  }

  if (serveurIni('HABILITATION', 'hab_3') != "") {
    if ($id_cop->hab_3 != $hab3) {
      addHistorique($agent->matricule, "1¤3¤3¤" . $matricule_policier . "¤" . $id_cop->hab_3 . "¤" . $hab3);
    }
  }

  if (serveurIni('HABILITATION', 'hab_4') != "") {
    if ($id_cop->hab_4 != $hab4) {
      addHistorique($agent->matricule, "1¤3¤4¤" . $matricule_policier . "¤" . $id_cop->hab_4 . "¤" . $hab4);
    }
  }

  if (serveurIni('HABILITATION', 'hab_5') != "") {
    if ($id_cop->hab_5 != $hab5) {
      addHistorique($agent->matricule, "1¤3¤5¤" . $matricule_policier . "¤" . $id_cop->hab_5 . "¤" . $hab5);
    }
  }

  if (serveurIni('HABILITATION', 'hab_6') != "") {
    if ($id_cop->hab_6 != $hab6) {
      addHistorique($agent->matricule, "1¤3¤6¤" . $matricule_policier . "¤" . $id_cop->hab_6 . "¤" . $hab6);
    }
  }

  if (serveurIni('HABILITATION', 'hab_7') != "") {
    if ($id_cop->hab_7 != $hab7) {
      addHistorique($agent->matricule, "1¤3¤7¤" . $matricule_policier . "¤" . $id_cop->hab_7 . "¤" . $hab7);
    }
  }

  if (serveurIni('HABILITATION', 'hab_8') != "") {
    if ($id_cop->hab_8 != $hab8) {
      addHistorique($agent->matricule, "1¤3¤8¤" . $matricule_policier . "¤" . $id_cop->hab_8 . "¤" . $hab8);
    }
  }

  if (serveurIni('HABILITATION', 'hab_9') != "") {
    if ($id_cop->hab_9 != $hab9) {
      addHistorique($agent->matricule, "1¤3¤9¤" . $matricule_policier . "¤" . $id_cop->hab_9 . "¤" . $hab9);
    }
  }

  if (serveurIni('HABILITATION', 'hab_10') != "") {
    if ($id_cop->hab_10 != $hab10) {
      addHistorique($agent->matricule, "1¤3¤10¤" . $matricule_policier . "¤" . $id_cop->hab_10 . "¤" . $hab10);
    }
  }

  if (serveurIni('HABILITATION', 'hab_11') != "") {
    if ($id_cop->hab_11 != $hab11) {
      addHistorique($agent->matricule, "1¤3¤11¤" . $matricule_policier . "¤" . $id_cop->hab_11 . "¤" . $hab11);
    }
  }

  if (serveurIni('HABILITATION', 'hab_12') != "") {
    if ($id_cop->hab_12 != $hab12) {
      addHistorique($agent->matricule, "1¤3¤12¤" . $matricule_policier . "¤" . $id_cop->hab_12 . "¤" . $hab12);
    }
  }

  if (serveurIni('HABILITATION', 'hab_13') != "") {
    if ($id_cop->hab_13 != $hab13) {
      addHistorique($agent->matricule, "1¤3¤13¤" . $matricule_policier . "¤" . $id_cop->hab_13 . "¤" . $hab13);
    }
  }

  if (serveurIni('HABILITATION', 'hab_14') != "") {
    if ($id_cop->hab_14 != $hab14) {
      addHistorique($agent->matricule, "1¤3¤14¤" . $matricule_policier . "¤" . $id_cop->hab_14 . "¤" . $hab14);
    }
  }

  if (serveurIni('HABILITATION', 'hab_15') != "") {
    if ($id_cop->hab_15 != $hab15) {
      addHistorique($agent->matricule, "1¤3¤15¤" . $matricule_policier . "¤" . $id_cop->hab_15 . "¤" . $hab15);
    }
  }

  if ($id_cop->note != $note) {
    addHistorique($agent->matricule, "1¤3¤16¤" . $matricule_policier . "¤" . $id_cop->note . "¤" . $note);
  }
}
?>
