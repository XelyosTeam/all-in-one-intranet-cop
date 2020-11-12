<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */
  use Josantonius\Session\Session; // Pour utiliser les variables de sessions

  class Agent extends Model {
    public static $_table = 'info_lspd'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getInfoAgent() { // Informations d'un agent de police avec le matricule
      return Agent::where('matricule', Session::get('matricule'))
                  ->find_one();
    }
    public static function getInfoAgentMatricule($matricule) { // Informations d'un agent de police avec le matricule
      return Agent::where('matricule', $matricule)
                  ->find_one();
    }
    public static function getInfoAgentID($id_police) { // Informations d'un agent de police avec l'ID
      return Agent::where('lspd_id', $id_police)
                  ->find_one();
    }
    public static function getInfoAgentIDUser($id_police) { // Informations d'un agent de police avec l'ID
      return Agent::where('user_id', $id_police)
                  ->find_one();
    }
    public static function getListAgentTri($nom, $prenom, $matricule) {
      return Agent::where_like(array(
                       'nom' => "%$nom%",
                       'prenom' => "%$prenom%",
                       'matricule' => "%$matricule%"
                     ))
                     ->where_not_equal(array('grade_id' => 1, 'lspd_id' => serveurIni('Par_defaut', 'id_lspd'))) // On retire l'agent n'étant pas censé être visible dans les listes
                     ->order_by_asc(array('nom', 'prenom'))
                     ->find_many();
    }
    public static function getListAgent() {
      return Agent::where_not_equal(array('grade_id' => 1, 'lspd_id' => serveurIni('Par_defaut', 'id_lspd'))) // On retire l'agent n'étant pas censé être visible dans les listes
                  ->order_by_asc(array('nom', 'prenom'))
                  ->find_many();
    }
    public static function getListOldAgent() {
      return Agent::where_like('grade_id', 1)
                  ->where_not_equal('lspd_id', serveurIni('Par_defaut', 'id_lspd')) // On retire l'agent n'étant pas censé être visible dans les listes
                  ->order_by_asc(array('nom', 'prenom'))
                  ->find_many();
    }
    public static function getListNonProf() {
      return Agent::where_not_equal(array('grade_id' => 1, 'hab_1' => 3))
                  ->find_many();
    }
  }

  class categorieArme extends Model {
    public static $_table = 'lspd_categorie_arme'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getList() {
      return categorieArme::order_by_asc('nom')
                          ->find_many();
    }
  }

  class modelArme extends Model {
    public static $_table = 'lspd_liste_arme'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getList() {
      return modelArme::order_by_asc('nom')
                      ->find_many();
    }
    public static function getInfo($modele) { // On récupère l'image du véhicule en fonction du modèle
      return modelArme::where('id', $modele)
                      ->find_one();
    }
  }

  class listeArme extends Model {
    public static $_table = 'lspd_arme'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getNumSerie($modele) { // On récupère l'image du véhicule en fonction du modèle
      return listeArme::where('numero', $modele)
                      ->find_one();
    }
  }

  class Arme extends Model {
    public static $_table = 'info_arme'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListIDUser($id) {
      return Arme::where('proprio', $id)
                 ->order_by_asc(array('prefix', 'numero'))
                 ->find_many();
    }
    public static function getInfoNumero($numero) {
      return Arme::where('numero', $numero)
                 ->find_one();
    }
  }

  class Autorisation extends Model {
    public static $_table = 'lspd_autorisation'; // Liaison avec la table
  }

  class Candidature extends Model {
    public static $_table = 'lspd_candidature'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListCandidature($etat) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Candidature::where('etat_act', $etat)
                        ->order_by_asc(array('nom', 'prenom'))
                        ->find_many();
    }
    public static function getCandidature($id_candid) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Candidature::where('id', $id_candid)
                        ->find_one();
    }
  }

  class Casier extends Model {
    public static $_table = 'info_casier'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListCasier($user_id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Casier::where('id_personne', $user_id)
                   ->order_by_asc(array('etat', 'nom'))
                   ->find_many();
    }
    public static function getDelit($delit_id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Casier::where('id_delit', $delit_id)
                   ->find_one();
    }
    public static function getIDCasier($id, $matricule) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return Casier::where(array('id_personne' => $id, 'enregistrer_par' => $matricule, 'etat' => '0'))
                   ->order_by_desc('id_delit')
                   ->find_one();
    }
    public static function getAmende($id) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return Casier::where(array('id_personne' => $id, 'etat' => '0'))
                   ->sum('amende');
    }
    public static function getPrison($id) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return Casier::where(array('id_personne' => $id, 'etat' => '0'))
                   ->sum('prison');
    }
    public static function getCasierEnCours($id) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return Casier::where(array('id_personne' => $id, 'etat' => '0'))
                   ->find_many();
    }
  }

  class Casier_t extends Model {
    public static $_table = 'lspd_casier'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getCasierOpen($id_cop) {
      return Casier_t::where('enregistrer_par', $id_cop)
                     ->count();
    }
    public static function getCasierClose($id_cop) {
      return Casier_t::where('acquite_par', $id_cop)
                      ->count();
    }
  }

  class Chat extends Model {
    public static $_table = 'chat'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getList() { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Chat::order_by_desc('send_time')
                 ->limit(25)
                 ->find_many();
    }
  }

  class Delit extends Model {
    public static $_table = 'lspd_delit'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListDelit($type) { // On récupère les infos d'un délit
      return Delit::where('type_delit', $type)
                  ->order_by_asc('intitule')
                  ->find_many();
    }
    public static function getinfoDelit($delit_id) { // On récupère les infos d'un délit
      return Delit::where('id', $delit_id)
                  ->find_one();
    }
  }

  class Grade extends Model {
    public static $_table = 'lspd_grade'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getList() { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Grade::order_by_desc('position')
                  ->find_many();
    }
    public static function getGrade($id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Grade::where('id', $id)
                  ->find_one();
    }
  }

  class Habilitation extends Model {
    public static $_table = 'lspd_habilitation'; // Liaison avec la table
  }

  class Historique extends Model {
    public static $_table = 'lspd_historique_connexion'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getEchec() {
      return Historique::select_many('adresse_ip')
                       ->select_expr('COUNT(*)', 'NbEchecs')
                       ->where('etat', 'echec')
                       ->group_by('adresse_ip')
                       ->find_many();
    }
    public static function getNbConnect($matricule) {
      return Historique::where(array('etat' => 'Réussite', 'matricule_utilise' => $matricule))
                       ->count();
    }
    public static function getNbEchec($ip) {
      return Historique::where(array('etat' => 'echec', 'adresse_ip' => $ip))
                       ->count();
    }
    public static function getEchecAdresse($ip) {
      return Historique::where(array('etat' => 'echec', 'adresse_ip' => $ip))
                       ->find_many();
    }
    public static function getListMatricule($matricule) {
      return Historique::where('matricule_utilise', $matricule)
                       ->order_by_desc('id')
                       ->find_many();
    }
  }

  class Historique_LSPD extends Model {
    public static $_table = 'lspd_historique'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getAction($matricule) {
      return Historique_LSPD::where('matricule', $matricule)
                            ->order_by_desc('id')
                            ->find_many();
    }
    public static function getNbAction($matricule) {
      return Historique_LSPD::where('matricule', $matricule)
                            ->count();
    }
  }

  class InfoPlainte extends Model {
    public static $_table = 'info_plainte'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListPlainte($nom, $prenom) { // On récupère les plaintes désposées en tout
      return InfoPlainte::where('etat', 1)
                    ->where_like(array(
                      'nom_1' => "%$nom%",
                      'prenom_1' => "%$prenom%"
                    ))
                    ->find_many();
    }
  }

  class Personne extends Model {
    public static $_table = 'personnes'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getinfoPersonne($id_user) { // Information d'une personne
      return Personne::where('id', $id_user)
                     ->find_one();
    }
    public static function getIDPersonne($nom, $prenom) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return Personne::where(array('nom' => $nom, 'prenom' => $prenom))
                     ->order_by_desc('id')
                     ->find_one();
    }
    public static function getListPersonne($etat) {
      return Personne::where('present', $etat)
                     ->order_by_asc(array('nom', 'prenom'))
                     ->find_many();
    }
    public static function getListPersonneTri($nom, $prenom, $phone, $job) {
      return Personne::where('present', 2)
                     ->where_like(array(
                       'nom' => "%$nom%",
                       'prenom' => "%$prenom%",
                       'phone' => "%$phone%",
                       'job' => "%$job%"
                     ))
                     ->order_by_asc(array('nom', 'prenom'))
                     ->find_many();
    }
    public static function getPPA($info) {
      return Personne::where(array(
                       'ppa' => $info,
                       'present' => 2
                      ))
                      ->order_by_asc(array('nom', 'prenom'))
                      ->find_many();
    }
    public static function getSansEmploi() {
      return Personne::where(array('present' => 2, 'job' => serveurIni('Par_defaut', 'emploi')))
                     ->order_by_asc(array('nom', 'prenom'))
                     ->find_many();
    }
    public static function nonPhoto() {
      return Personne::where(
                        array(
                          'present' => 2,
                          'photo' => 'temp.png'
                      ))
                     ->order_by_asc(array('nom', 'prenom'))
                     ->find_many();
    }
    public static function OldCop() {
      return Personne::raw_query('SELECT * FROM personnes WHERE id NOT IN (SELECT user_id FROM info_lspd) AND id <> :lspd_id AND job = :job  ORDER BY nom, prenom;', array('lspd_id' => serveurIni('Par_defaut', 'id_lspd'), 'job' => serveurIni('Par_defaut', 'emploi')))
                     ->find_many();
    }
  }

  class Plainte extends Model {
    public static $_table = 'lspd_plainte'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListPlainte($user_id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Plainte::where('plainte_sur', $user_id)
                    ->order_by_asc('etat')
                    ->find_many();
    }
    public static function getPlainte($plainte_id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Plainte::where('id', $plainte_id)
                    ->find_one();
    }
    public static function getIDPlainte($deposeur, $citoyen, $agent) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Plainte::where(
                        array(
                          'deposeur' => $deposeur,
                          'plainte_sur' => $citoyen,
                          'enregistrer_par' => $agent
                      ))
                     ->order_by_desc('id')
                     ->find_one();
    }
    public static function getPlainteOpen($id_cop) {
      return Plainte::where('enregistrer_par', $id_cop)
                    ->count();
    }
    public static function getPlainteClose($id_cop) {
      return Plainte::where('fermer_par', $id_cop)
                    ->count();
    }
  }

  class Policier_t extends Model {
    public static $_table = 'lspd_policier'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getInfoAgentMatricule($matricule) {
      return Policier_t::where('matricule', $matricule)
                       ->find_one();
    }
  }

  class ModelesV extends Model {
    public static $_table = 'modeles_vehicules'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListCarModele() {
      return ModelesV::order_by_asc('nom')
                     ->find_many();
    }
    public static function getImage($modele) { // On récupère l'image du véhicule en fonction du modèle
      return ModelesV::where('id', $modele)
                     ->find_one();
    }
  }

  class Rapport extends Model {
    public static $_table = 'lspd_rapport'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListRapportID($lspd_id) {
      return Rapport::where('concerne', $lspd_id)
                    ->find_many();
    }
    public static function getRapportID($raport_id) {
      return Rapport::where('id', $raport_id)
                    ->find_one();
    }
    public static function liste_non_vu($lspd_id) {
      return Rapport::raw_query('SELECT * FROM info_rapport WHERE id NOT IN (SELECT rapport_id FROM lspd_rapport_vu WHERE policier_id = :lspd_id) AND lspd_id <> :lspd_id', array('lspd_id' => $lspd_id))
                    ->find_many();
    }
  }

  class RapportVu extends Model {
    public static $_table = 'lspd_rapport_vu'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function non_vu($lspd_id, $rapport_id) {
      return RapportVu::where(array('policier_id' => $lspd_id, 'rapport_id' => $rapport_id))
                      ->find_one();
    }
  }

  class Route extends Model {
    public static $_table = 'info_route'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListDelitByV($vehicule_id) { // On récupère la liste des délits routier avec l'ID d'un véhicule
      return Route::where('v_id', $vehicule_id)
                  ->order_by_asc('etat')
                  ->find_many();
    }
    public static function getListDelitByC($conducteur_id) { // On récupère la liste des délits routier avec l'ID du conducteur
      return Route::where('conducteur_id', $conducteur_id)
                  ->order_by_asc('etat')
                  ->find_many();
    }
    public static function getRoute($delit_id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Route::where('delit_id', $delit_id)
                  ->find_one();
    }
    public static function getIDRoute($vehicle, $conducteur, $enregistreur) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return Route::where(array(
                    'v_id' => $vehicle,
                    'conducteur_id' => $conducteur,
                    'etat' => '0'
                  ))
                  ->order_by_desc('delit_id')
                  ->find_one();
    }
    public static function getAmende($id) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return Route::where(array('conducteur_id' => $id, 'etat' => '0'))
                  ->sum('amende');
    }
    public static function getPrison($id) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return Route::where(array('conducteur_id' => $id, 'etat' => '0'))
                  ->sum('prison');
    }
    public static function getRouteEnCours($id) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return Route::where(array('conducteur_id' => $id, 'etat' => '0'))
                  ->find_many();
    }
  }

  class Route_t extends Model {
    public static $_table = 'lspd_route'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getRouteOpen($id_cop) {
      return Route_t::where('enregistrer_par', $id_cop)
                    ->count();
    }
    public static function getRouteClose($id_cop) {
      return Route_t::where('acquite_par', $id_cop)
                    ->count();
    }
  }

  class Vehicules extends Model {
    public static $_table = "vehicules"; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getCarAdd($id_cop) {
      return Vehicules::where('enregistre_par', $id_cop)
                      ->count();
    }
  }

  class Voiture extends Model {
    public static $_table = "info_voiture"; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListCarPersonne($personne) { // Liste des voitures d'une personne
      return Voiture::where(array('proprio' => $personne, 'circulation' => '1'))
                    ->where_not_equal('couleur', serveurIni('Faction', 'couleurVehiculeBDD'))
                    ->order_by_asc('plaque')
                    ->find_many();
    }
    public static function getListCarPolice($personne) { // Liste des voitures d'une personne
      return Voiture::where('proprio', $personne)
                    ->order_by_asc('plaque')
                    ->find_many();
    }
    public static function getListCarTri($modele, $plaque, $couleur) { // Liste des voitures en fonction d'un tri
      return Voiture::where_like(array(
                      'nom' => "%$modele%",
                      'plaque' => "%$plaque%",
                      'couleur' => "%$couleur%"
                    ))
                    ->where_not_equal('couleur', serveurIni('Faction', 'couleurVehiculeBDD'))
                    ->order_by_asc('nom')
                    ->order_by_asc('plaque')
                    ->find_many();
    }
    public static function getCar($plaque) { // Information d'une voiture avec la plaque
      return Voiture::where('plaque', $plaque)->find_one();
    }
    public static function getListCar() { // Information d'une voiture avec la plaque
      return Voiture::order_by_asc(array('plaque', 'nom'))->find_many();
    }
    public static function getCarID($id_v) { // Information d'une voiture avec l(ID)
      return Voiture::where('v_id', $id_v)->find_one();
    }
  }
?>
