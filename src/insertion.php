<?php
/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/

function addRapportVu($lspd_id, $rapport_id) {
  $casier = Model::factory('RapportVu')->create();
  $casier->set(array(
                'policier_id' => $lspd_id,
                'rapport_id' => $rapport_id
              )
            );
  $casier->save();
}

function addCasier($delit, $rapport, $proprio, $agent) {
  $casier = Model::factory('Casier_t')->create();
  $casier->set(array(
                'personne' => $proprio,
                'numero_delit' => $delit,
                'enregistrer_par' => $agent,
                'enregistrer_le' => date("Y-m-d"),
                'remarque' => $rapport
              )
            );
  $casier->save();
}

function addDelit($nom, $montant, $prison, $type, $retrait) {
  $casier = Model::factory('Delit')->create();
  $casier->set(array(
    'intitule' => $nom,
    'amende' => $montant,
    'temps_prison' => $prison,
    'type_delit' => $type,
    'retrait' => $retrait
  )
              );
  $casier->save();
}

function addHistorique($matricule, $message) {
  $vehicule = Model::factory('Historique_LSPD')->create();
  $vehicule->set(array(
                'matricule' => $matricule,
                'contenu' => $message,
                'date_even' => date("Y-m-d H:i:s")
              )
            );
  $vehicule->save();
}

function addImgCar($nom, $fichier) {
  $ImgCar = Model::factory('ModelesV')->create();
  $ImgCar->set(array(
                'nom' => $nom,
                'lien' => $fichier
              )
            );
  $ImgCar->save();
}

function addPersonne($nom, $prenom, $dob, $phone, $nationality, $sexe, $permis, $photo, $job) {
  $person = Model::factory('Personne')->create();
  $person->set(array(
                'nom' => $nom,
                'prenom' => $prenom,
                'DOB' => $dob,
                'phone' => $phone,
                'nationality' => $nationality,
                'sexe' => $sexe,
                'job' => serveurIni('Par_defaut', 'emploi'),
                'permis' => $permis,
                'photo' => $photo,
                'date_permis' => 1590414879 // On met une valeur par défaut afin d'éviter d'avoir l'affichage de la période de retrait
              )
            );
  $person->save();
}

function addPlainte($deposeur, $citoyen, $detail, $matricule) {
  $plainte = Model::factory('Plainte')->create();
  $plainte->set(array(
                'deposeur' => $deposeur,
                'plainte_sur' => $citoyen,
                'detail_plainte' => $detail,
                'enregistrer_par' => $matricule,
                'enregistrer_le' => date("Y-m-d")
              )
            );
  $plainte->save();
}

function addRapport($depo, $titre, $rapport_t, $matricule) {
  $rapport = Model::factory('Rapport')->create();
  $rapport->set(array(
                'concerne' => $depo,
                'editeur' => $matricule,
                'titre' => $titre,
                'contenu' => $rapport_t,
                'ecrit_le' => date("Y-m-d")
              )
            );
  $rapport->save();
}

function addRoutier($conducteur, $vehicle, $delit, $rapport, $matricule) {
  $routier = Model::factory('Route_t')->create();
  $routier->set(array(
                'vehicule' => $vehicle,
                'conducteur' => $conducteur,
                'numero_delit' => $delit,
                'enregistrer_par' => $matricule,
                'enregistrer_le' => date("Y-m-d"),
                'remarque' => $rapport,
              )
            );
  $routier->save();
}

function addVoiture($model, $color, $plaque, $proprio, $enregistreur) {
  $vehicule = Model::factory('Vehicules')->create();
  $vehicule->set(array(
                'modeles' => $model,
                'couleur' => $color,
                'plaque' => $plaque,
                'proprietaire' => $proprio,
                'enregistre_par' => $enregistreur
              )
            );
  $vehicule->save();
}

function addWeapon($serie, $model, $proprio, $agent) {
  $weaponCategorie = Model::factory('listeArme')->create();
  $weaponCategorie->set(array(
                        'numero' => $serie,
                        'type' => $model,
                        'proprio' => $proprio,
                        'enregistrer_par' => $agent
                      )
                    );
  $weaponCategorie->save();
}

function addWeaponCategorie($cat, $pref) {
  $weaponCategorie = Model::factory('categorieArme')->create();
  $weaponCategorie->set(array(
                        'nom' => $cat,
                        'prefix' => $pref
                      )
                    );
  $weaponCategorie->save();
}

function addWeaponList($name, $cat, $photo) {
  $weaponList = Model::factory('modelArme')->create();
  $weaponList->set(array(
                        'nom' => $name,
                        'categorie' => $cat,
                        'photo' => $photo
                      )
                    );
  $weaponList->save();
}

function create_autorisation($id) {
  $autorisation = Model::factory('Autorisation')->create();
  $autorisation->set('matricule', $id);
  $autorisation->save();
}

function create_habilitation($id) {
  $habilitation = Model::factory('Habilitation')->create();
  $habilitation->set('matricule', $id);
  $habilitation->save();
}

function insertCop($id, $matricule_cop, $salt, $mdp_temp) {
  /* Insertion dans la BDD */
  $policier = Model::factory('Policier_t')->create();
  $policier->set(array(
                'matricule' => $matricule_cop,
                'Passwd' => $mdp_temp,
                'Sel_de_table' => $salt,
                'personne' => $id,
                'rang' => '2'
              )
            );
  $policier->save();
}

/* Si on veut écrire au contraire, il faut analyser les paramètres envoyés en POST et les sauver dans la base de données */
function postMessage() {
  $agent = Agent::getInfoAgent();

  $content = $_POST['content'];

  $chat = Model::factory('Chat')->create();
  $chat->set(array(
      'author' => "$agent->nom " .  $agent->prenom[0] .".",
      'message' => urlencode($content),
      'side' => "cop",
      'send_time' => date("Y-m-d H:i:s")
    )
  );
  $chat->save();

  // 3. Donner un statut de succes ou d'erreur au format JSON
  Flight::json(["status" => "success"]);
  Flight::redirect("/discussion-interne");
}

function update_metier($id) {
  $change_job = Model::factory('Personne')->where('id', $id)->find_one();
  $change_job->set('job', serveurIni('Faction', 'metierBDD'));
  $change_job->save();
}
?>
