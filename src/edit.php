<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */
  function addImgCivil($id, $fichier) {
    $ImgCivil = Model::factory('Personne')->where('id', $id)->find_one();
    $ImgCivil->set('photo', $fichier);
    $ImgCivil->save();
  }

  function editCandidature($id, $etat) {
    $candid = Model::factory('Candidature')->where('id', $id)->find_one();
    $candid->set('etat_act', $etat);
    $candid->save();
  }

  function editCivil($id_civil, $phone, $job, $drive, $time, $ppa) {
    $civil = Model::factory('Personne')->where('id', $id_civil)->find_one();
    $civil->set(array(
                'phone' => $phone,
                'job' => $job,
                'permis' => $drive,
                'date_permis' => $time,
                'ppa' => $ppa
    ));
    $civil->save();
  }

  function editCivil2($id_civil, $phone, $drive, $time, $ppa) {
    $civil = Model::factory('Personne')->where('id', $id_civil)->find_one();
    $civil->set(array(
                'phone' => $phone,
                'permis' => $drive,
                'date_permis' => $time,
                'ppa' => $ppa
    ));
    $civil->save();
  }

  function editCop($id, $grade, $hab1, $hab2, $hab3, $hab4, $hab5, $hab6, $hab7, $hab8, $hab9, $hab10, $hab11, $hab12, $hab13, $hab14, $hab15, $note) {
    /* Upgrade des autorisations */
    $autorisation = Model::factory('Autorisation')->where('matricule', $id)->find_one();
    $autorisation->set(array(
              'add_perm' => '1',
              'edit_perm' => '1',
              'delete_perm' => '1'
            ));
    $autorisation->save();

    /* Modification dans la table cop */
    $cop = Model::factory('Policier_t')->where('id', $id)->find_one();
    $cop->set(array(
          'rang' => $grade,
          'note' => $note,
        ));
    $cop->save();

    /* Upgrade des habilitations */
    $habilitation = Model::factory('Habilitation')->where('matricule', $id)->find_one();
    $habilitation->set(array(
              'hab_1' => $hab1,
              'hab_2' => $hab2,
              'hab_3' => $hab3,
              'hab_4' => $hab4,
              'hab_5' => $hab5,
              'hab_6' => $hab6,
              'hab_7' => $hab7,
              'hab_8' => $hab8,
              'hab_9' => $hab9,
              'hab_10' => $hab10,
              'hab_11' => $hab11,
              'hab_12' => $hab12,
              'hab_13' => $hab13,
              'hab_14' => $hab14,
              'hab_15' => $hab15,
            ));
    $habilitation->save();
  }

  function editDelitAmende($id, $amd_delit) {
    $delit = Model::factory('Delit')->where('id', $id)->find_one();
    $delit->set('amende', $amd_delit);
    $delit->save();
  }

  function editDelitPrison($id, $tps_delit) {
    $delit = Model::factory('Delit')->where('id', $id)->find_one();
    $delit->set('temps_prison', $tps_delit);
    $delit->save();
  }

  function editDelitRetrait($id, $retrait) {
    $delit = Model::factory('Delit')->where('id', $id)->find_one();
    $delit->set('retrait', $retrait);
    $delit->save();
  }

  function editPPA($id, $value) {
    $person = Model::factory('Personne')->where('id', $id)->find_one();
    $person->set('ppa', $value);
    $person->save();
  }

  function editPresence($id, $value) {
    $person = Model::factory('Personne')->where('id', $id)->find_one();
    $person->set('present', $value);
    $person->save();
  }

  function editLicenciement($id) {
    /* Suppression des autorisations */
    $bye = Model::factory('Autorisation')->where('matricule', $id)->find_one();
    $bye->set(array(
              'add_perm' => '0',
              'edit_perm' => '0',
              'delete_perm' => '0'
            ));
    $bye->save();

    /* Bloquage et suppression du grade */
    $bye2 = Model::factory('Policier_t')->where('id', $id)->find_one();
    $bye2->set('rang', 1);
    $bye2->save();

    /* Déclaration de l'individu comme sans emploi */
    $id_civil = Agent::getInfoAgentID($id);
    $bye3 = Model::factory('Personne')->where('id', $id_civil->user_id)->find_one();
    $bye3->set('job', serveurIni('Par_defaut', 'emploi'));
    $bye3->save();
  }

  function editRapportCasier($id, $rapport) {
    $modification = Model::factory('Casier_t')->where('id', $id)->find_one();
    $modification->set('remarque', $rapport);
    $modification->save();
  }

  function editRapportPlainte($id, $rapport) {
    $modification = Model::factory('Plainte')->where('id', $id)->find_one();
    $modification->set('detail_plainte', $rapport);
    $modification->save();
  }

  function editRapportRoute($id, $rapport) {
    $modification = Model::factory('Route_t')->where('id', $id)->find_one();
    $modification->set('remarque', $rapport);
    $modification->save();
  }

  function editVehicule($plaque, $color, $road, $proprio) {
    $voiture = Model::factory('Vehicules')->where('plaque', $plaque)->find_one();
    $voiture->set(array(
                'couleur' => $color,
                'en_circulation' => $road,
                'proprietaire' => $proprio // v1.6.1
    ));
    $voiture->save();
  }

  function mise_a_jour_mdp($matricule, $new_mdp_hashed, $salt) {
    $bye2 = Model::factory('Policier_t')->where('matricule', $matricule)->find_one();
    $bye2->set(array(
              'Passwd' => $new_mdp_hashed,
              'Sel_de_table' => $salt
            ));
    $bye2->save();
  }

  function updateProf($id_cop) {
    $habilitation = Model::factory('Habilitation')->where('matricule', $id_cop)->find_one();
    $habilitation->set('hab_1', 3);
    $habilitation->save();
  }


?>
