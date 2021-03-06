<?php
  require "vendor/autoload.php";

  /* Initialisation des variables de sessions */
  use Josantonius\Session\Session;

  Session::init();
  /* Initialisation des variables de sessions */

  /* Associer Flight à Twig */
  $loader = new \Twig\Loader\FilesystemLoader(dirname(__FILE__) . '/page');
  $twigConfig = array(
      // 'cache' => './cache/twig/',
      // 'cache' => false,
      'debug' => true,
  );

  /* Version 2.1.1 */ 
  Flight::register('view', '\Twig\Environment', array($loader, $twigConfig), function ($twig) {
      $twig->addExtension(new \Twig\Extension\DebugExtension()); // Add the debug extension
      $twig->addGlobal('_agent', Agent::getInfoAgent());

      /* Paramètre de page server.ini */
      // Faction
      $twig->addGlobal('_membreFaction', serveurIni('Faction', 'membre'));
      $twig->addGlobal('_nomFaction', serveurIni('Faction', 'nom'));
      $twig->addGlobal('_nomcompletFaction', serveurIni('Faction', 'nomcomplet'));
      $twig->addGlobal('_BDDFaction', serveurIni('Faction', 'metierBDD'));
      $twig->addGlobal('_Recrutement', serveurIni('Faction', 'etatRecrutement'));
      //Serveur
      $twig->addGlobal('_nomServeur', serveurIni('Serveur', 'nom'));
      $twig->addGlobal('_jeuServeur', serveurIni('Serveur', 'jeu'));
      // Paramètre
      $twig->addGlobal('_typePermis', serveurIni('Parametre', 'permis_type'));
      $twig->addGlobal('_tpsPermis', serveurIni('Parametre', 'permis_time'));
      $twig->addGlobal('_Version', serveurIni('Serveur', 'version'));
      // Habilitation
      $twig->addGlobal('_Hab1', serveurIni('HABILITATION', 'hab_1'));
      $twig->addGlobal('_Hab2', serveurIni('HABILITATION', 'hab_2'));
      $twig->addGlobal('_Hab3', serveurIni('HABILITATION', 'hab_3'));
      $twig->addGlobal('_Hab4', serveurIni('HABILITATION', 'hab_4'));
      $twig->addGlobal('_Hab5', serveurIni('HABILITATION', 'hab_5'));
      $twig->addGlobal('_Hab6', serveurIni('HABILITATION', 'hab_6'));
      $twig->addGlobal('_Hab7', serveurIni('HABILITATION', 'hab_7'));
      $twig->addGlobal('_Hab8', serveurIni('HABILITATION', 'hab_8'));
      $twig->addGlobal('_Hab9', serveurIni('HABILITATION', 'hab_9'));
      $twig->addGlobal('_Hab10', serveurIni('HABILITATION', 'hab_10'));
      $twig->addGlobal('_Hab11', serveurIni('HABILITATION', 'hab_11'));
      $twig->addGlobal('_Hab12', serveurIni('HABILITATION', 'hab_12'));
      $twig->addGlobal('_Hab13', serveurIni('HABILITATION', 'hab_13'));
      $twig->addGlobal('_Hab14', serveurIni('HABILITATION', 'hab_14'));
      $twig->addGlobal('_Hab15', serveurIni('HABILITATION', 'hab_15'));
      // Cacul amende
      $twig->addGlobal('_multPrison', serveurIni('PRISON', 'multiplicateur_prison'));
      $twig->addGlobal('_divAmende', serveurIni('PRISON', 'diviseur_amende'));
      $twig->addGlobal('_tpsFederal', serveurIni('PRISON', 'tps_federal'));
      $twig->addGlobal('_tpsFederalVie', serveurIni('PRISON', 'tps_federal_vie'));
  });
  /* Associaiton Flight Twig */

  /* Association à la base de donnée */
  Flight::before('start', function(&$params, &$output) {
    $host = serveurIni('BDD', 'host');
    $name = serveurIni('BDD', 'name');
    $user = serveurIni('BDD', 'user');
    $mdp = serveurIni('BDD', 'mdp');
    ORM::configure("mysql:host=$host;dbname=$name;charset=utf8");
    ORM::configure('username', "$user");
    ORM::configure('password', "$mdp");
  });
  /* Association à la base de donnée */

  Flight::route('/', function()
  {
    verif_connecter();
    $agent = Agent::getInfoAgent();
    $voiture = Voiture::getListCarPolice($agent->user_id);
    Flight::view()->display('fiche/policier.twig', array(
      'agent' => $agent,
      'voitures' => $voiture, // Liste des voitures
      'armes' => Arme::getListIDUser($agent->user_id)
    ));
  });

  Flight::route('/administration/ajout', function()
  {
    verif_connecter();
    verif_admin(); // Vérifie si l'utilisateur fait partie des administrateurs

    Flight::view()->display('administration/ajout_systeme.twig', array(
      'agents' => Agent::getListNonProf(),
      'personnes' => Personne::nonPhoto(),
      'armes' => categorieArme::getList()
    ));
  });

  Flight::route('/administration/details/echec', function()
  {
    verif_connecter();
    verif_admin(); // Vérifie si l'utilisateur fait partie des administrateurs
    if (!isset($_POST['adresse'])) {
      Flight::redirect("/administration/modification");
      exit();
    }
    else {
      $ip = $_POST['adresse'];
    }

    Flight::view()->display('administration/detail_echec.twig', array(
      'ip' => $ip,
      'listes' => Historique::getEchecAdresse($ip)
    ));
  });

  Flight::route('/administration/modification', function()
  {
    verif_connecter();
    verif_admin(); // Vérifie si l'utilisateur fait partie des administrateurs

    Flight::view()->display('administration/modification_systeme.twig', array(
      'agents' => Agent::getListAgent(),
      'echecs' => Historique::getEchec(),
      'visibles' => Personne::getListPersonne(2),
      'invisibles' => Personne::getListPersonne(1),
      'legers' => Delit::getListDelit(2),
      'moyens' => Delit::getListDelit(3),
      'graves' => Delit::getListDelit(4),
      'routes' => Delit::getListDelit(1)
    ));
  });

  Flight::route('/administration/parametre-serveur', function()
  {
    verif_connecter();
    verif_admin(); // Vérifie si l'utilisateur fait partie des administrateurs

    Flight::view()->display('administration/server_param.twig', array(
      'fail' => serveurIni('Parametre', 'echec_maximum'),
      'grade' => serveurIni('Faction', 'gradeLevelBiffure')
    ));
  });

  Flight::route('/administration/parametre-serveur/modification', function()
  {
    verif_connecter();
    verif_admin(); // Vérifie si l'utilisateur fait partie des administrateurs

    $matricule = Session::get('matricule');

    if ($_POST['failed_connexion'] != '') {
      editserveurIni('Parametre', 'echec_maximum', $_POST['failed_connexion']);
      addHistorique($matricule, "0¤0¤0¤" . $_POST['failed_connexion']);
    }

    if ($_POST['periode_drive'] != '') {
      editserveurIni('Parametre', 'permis_type', $_POST['periode_drive']);
      addHistorique($matricule, "0¤0¤1¤" . $_POST['periode_drive']);
    }

    if ($_POST['time_dirve'] != '') {
      editserveurIni('Parametre', 'permis_time', $_POST['time_dirve']);
      addHistorique($matricule, "0¤0¤2¤" . $_POST['time_dirve']);
    }

    if ($_POST['level_censure'] != '') {
      editserveurIni('Faction', 'gradeLevelBiffure', $_POST['level_censure']);
      addHistorique($matricule, "0¤0¤3¤" . $_POST['level_censure']);
    }

    if ($_POST['etat_recrut'] != '') {
      editserveurIni('Faction', 'etatRecrutement', $_POST['etat_recrut']);
      addHistorique($matricule, "0¤0¤4¤" . $_POST['etat_recrut']);
    }
  });

  Flight::route('/administration/historique', function()
  {
    verif_connecter();
    verif_admin();

    Flight::view()->display('administration/historique.twig', array(
      'cops' => Agent::getListAgent(),
      'oldcops' => Agent::getListOldAgent()
    ));
  });

  Flight::route('/administration/historique/get_info', function()
  {
    header("Access-Control-Allow-Origin: *");
    verif_connecter();
    verif_admin();

    $agent = Agent::getInfoAgentMatricule($_POST['matricule_cop']);
    $liste = Historique_LSPD::getAction($_POST['matricule_cop']);

    if ($agent == NULL) { // L'agent n'existe pas
      $data = [
        'etat' => 0,
        'nom' => "Erreur dans l'envoi du matricule"
      ];
    }
    else { // L'agent existe
      // On récupère le nom de l'individu
      if ($agent->grade_id != 1) {
        $name = $agent->grade . " " . $agent->nom;
      }
      else {
        $name = $agent->nom . " " . $agent->prenom;
      }

      if ($liste == NULL) { // L'agent n'a pas envore fait d'action
        $data = [
          'etat' => 0,
          'nom' => $name
        ];
      }
      else {
        $i = 0;
        foreach ($liste as $key => $action) {

        try {
          $event = decryptHistorique($action->contenu);
        } catch (\Exception $e) {
          $event = $action->contenu;
        }

        $data[$key] = [
          'id' => $action->id,
          'date' => $action->date_even,
          'event' => $event
        ];
        $i++;
      }

        $data[$i] = ['etat' => $name];
      }
    }

    Flight::json($data);
  });

  Flight::route('/administration/historique_connexion/get_info', function()
  {
    header("Access-Control-Allow-Origin: *");
    verif_connecter();
    verif_admin();

    $agent = Agent::getInfoAgentMatricule($_POST['matricule_cop']);
    $liste = Historique::getListMatricule($_POST['matricule_cop']);

    if ($agent == NULL) { // L'agent n'existe pas
      $data = [
        'etat' => 0,
        'nom' => "Erreur dans l'envoi du matricule"
      ];
    }
    else { // L'agent existe
      // On récupère le nom de l'individu
      if ($agent->grade_id != 1) {
        $name = $agent->grade . " " . $agent->nom;
      }
      else {
        $name = $agent->nom . " " . $agent->prenom;
      }

      if ($liste == NULL) { // L'agent n'a pas envore fait d'action
        $data = [
          'etat' => 0,
          'nom' => $name
        ];
      }
      else {
        $i = 0;
        foreach ($liste as $key => $action) {
          $data[$key] = [
            'id' => $action->id,
            'date' => $action->date_connexion,
            'content' => $action->commentaire
          ];
          $i++;
        }

        $data[$i] = ['etat' => $name];
      }
    }

    Flight::json($data);
  });

  Flight::route('/administration/historique/get_stats', function()
  {
    header("Access-Control-Allow-Origin: *");
    verif_connecter();
    verif_admin();

    $agent = Agent::getInfoAgentMatricule($_POST['matricule_cop']);

    if ($agent) {
      $data = [
        'etat' => 1,
        'login' => Historique::getNbConnect($agent->matricule),
        'action' => Historique_LSPD::getNbAction($agent->matricule),
        'open' => (Casier_t::getCasierOpen($agent->lspd_id)) + (Plainte::getPlainteOpen($agent->lspd_id)) + (Route_t::getRouteOpen($agent->lspd_id)),
        'close' => (Casier_t::getCasierClose($agent->lspd_id)) + (Plainte::getPlainteClose($agent->lspd_id)) + (Route_t::getRouteClose($agent->lspd_id)),
        'car' => Vehicules::getCarAdd($agent->lspd_id)
      ];
    }
    else {
      $data = [
        'etat' => 0
      ];
    }

    Flight::json($data);
  });


  Flight::route('/ajouter/casier', function()
  {
    Flight::view()->display('add/add_casier.twig', array(
      'personnes' => Personne::getListPersonne(2),
      'leger' => Delit::getListDelit(2),
      'moyen' => Delit::getListDelit(3),
      'grave' => Delit::getListDelit(4)
    ));
  });

  Flight::route('/ajouter/casier-routier', function()
  {
    verif_connecter();

    Flight::view()->display('add/add_delit_route.twig', array(
      'personnes' => Personne::getListPersonne(2),
      'routes' => Delit::getListDelit(1),
      'voitures' => Voiture::getListCar()
    ));
  });

  Flight::route('/ajouter/civil', function()
  {
    verif_connecter();
    Flight::view()->display('add/add_civil.twig');
  });

  Flight::route('/ajouter/plainte', function()
  {
    verif_connecter();

    Flight::view()->display('add/add_plainte.twig', array(
      'personnes' => Personne::getListPersonne(2)
    ));
  });

  Flight::route('/ajouter/arme', function()
  {
    verif_connecter();

    Flight::view()->display('add/add_weapon.twig', array(
      'armes' => modelArme::getList(),
      'personnes' => Personne::getListPersonne(2)
    ));
  });

  Flight::route('/ajouter/vehicule', function()
  {
    verif_connecter();

    Flight::view()->display('add/add_car.twig', array(
      'voitures' => ModelesV::getListCarModele(),
      'personnes' => Personne::getListPersonne(2)
    ));
  });

  Flight::route('/casier-judiciaire/@id_delit', function($id_delit)
  {
    verif_connecter();
    $delit = Casier::getDelit($id_delit);
    if (!$delit) {
      Flight::redirect("/ajouter/casier");
      return;
    }
    $personne = Personne::getinfoPersonne($delit->id_personne);

    // Traitement de l'information date
    if ($personne->permis == 0) {
      $value = traitementDate($personne->date_permis);
      $personne->date_permis = $value[0]; // On modifie la valeur de date permis
      $personne->date_trad = $value[1];
    }

    $delit->remarque = renderHTMLFromMarkdown(htmlspecialchars(strip_tags($delit->remarque)));
    Flight::view()->display('fiche/detail_casier.twig', array(
      'delit' => $delit, // Info du casier
      'civil' => $personne, // Informations sur la personne
      'ouvert' => Agent::getInfoAgentID($delit->enregistrer_par), // Policier ayant incrit le casier
      'fermer' => Agent::getInfoAgentID($delit->acquite_par), // Policier ayant fermé le casier
      'policier' => Agent::getInfoAgentIDUser($personne->id)
    ));
  });

  Flight::route('/arme/@numero_serie', function($numero_serie)
  {
    verif_connecter();
    $arme = Arme::getInfoNumero($numero_serie);
    if (!$arme) {
      Flight::redirect("/ajouter/arme");
      return;
    }
    $personne = Personne::getinfoPersonne($arme->proprio);

    // Traitement de l'information date
    if ($personne->permis == 0) {
      $value = traitementDate($personne->date_permis);
      $personne->date_permis = $value[0]; // On modifie la valeur de date permis
      $personne->date_trad = $value[1];
    }

    Flight::view()->display('fiche/detail_arme.twig', array(
      'arme' => $arme, // Info du casier
      'civil' => $personne, // Informations sur la personne
      'cop' => Agent::getInfoAgentID($arme->matricule), // Policier ayant incrit le casier
      'policier' => Agent::getInfoAgentIDUser($personne->id)
    ));
  });

  Flight::route('/casier-judiciaire/@id_delit/edit', function($id_delit)
  {
    verif_connecter();
    $delit = Casier::getDelit($id_delit);
    $personne = Personne::getinfoPersonne($delit->id_personne);
    $agent = Agent::getInfoAgent();

    if ($delit->etat != 0) {
      Flight::redirect("/casier-judiciaire/$id_delit");
      exit();
    }

    if ($agent->hab_1 != 2) {
      if ($agent->matricule != $ouvert->matricule) {
        Flight::redirect("/casier-judiciaire/$id_delit");
        exit();
      }
    }

    Flight::view()->display('edit/casier.twig', array(
      'delit' => $delit, // Info du casier
      'civil' => $personne, // Informations sur la personne
      'detail' => Delit::getinfoDelit($delit->type_d), // Détail du délit
      'ouvert' => Agent::getInfoAgentID($delit->enregistrer_par), // Policier ayant incrit le casier
      'fermer' => Agent::getInfoAgentID($delit->acquite_par), // Policier ayant fermé le casier
      'policier' => Agent::getInfoAgentIDUser($personne->id)
    ));
  });

  Flight::route('/casier-judiciaire/@id_delit/modification', function($id_delit)
  {
    verif_connecter();
    /* Variable récupéré dans le get */
    $rapport = $_POST['rapport'];
    /* Variable récupéré dans le get */
    $agent = Agent::getInfoAgent();
    $act = Casier::getDelit($id_delit);

    if ($act->remarque != $rapport) {
      addHistorique($agent->matricule, "3¤0¤0¤" . $id_delit . "¤" . $act->remarque . "¤" . $rapport);
    }

    if ($agent->editer == 0) {
      Flight::redirect("/casier-judiciaire/$id_delit/edit");
      exit();
    }

    editRapportCasier((int)$id_delit, $rapport);

    Flight::redirect("/casier-judiciaire/$id_delit");
  });

  Flight::route('/casier-judiciaire/@id_delit/@etat', function($id_delit, $etat)
  {
    verif_connecter();
    $agent = Agent::getInfoAgent();

    if ($agent->editer == 0) {
      Flight::redirect("/casier-judiciaire/$id_delit");
      exit();
    }

    closeCasier($id_delit, $etat, $agent->lspd_id);
    addHistorique($agent->matricule, "5¤0¤0¤" . $id_delit . "¤" . $etat);

    Flight::redirect("/casier-judiciaire/$id_delit");
  });

  Flight::route('/civil/@id_citoyen', function($id_citoyen)
  {
    verif_connecter();
    $civil = Personne::getinfoPersonne($id_citoyen);
    if (!$civil) {
      Flight::redirect("/recherche/civil?civil_name=&civil_firstname=&civil_phone=&civil_job=");
      return;
    }

    // Traitement de l'information date
    if ($civil->permis == 0) {
      $value = traitementDate($civil->date_permis);
      $civil->date_permis = $value[0]; // On modifie la valeur de date permis
      $civil->date_trad = $value[1];
    }

    Flight::view()->display('fiche/civil.twig', array(
      'civil' => $civil, // Information sur le civil
      'voitures' => Voiture::getListCarPersonne($id_citoyen), // Liste des véhicules
      'routes' => Route::getListDelitByC($id_citoyen), // Liste délits routiers
      'casiers' => Casier::getListCasier($id_citoyen), // Liste casier judiciaire
      'plaintes' => Plainte::getListPlainte($id_citoyen), // Liste des plaintes
      'policier' => Agent::getInfoAgentIDUser($civil->id), // Si la personne est cops
      'armes' => Arme::getListIDUser($civil->id)
    ));
  });

  Flight::route('/civil/@id_citoyen/calcul-amende', function($id_citoyen)
  {
    verif_connecter();
    $civil = Personne::getinfoPersonne($id_citoyen);
    $amendeCasier = Casier::getAmende($id_citoyen);
    $amendeRoute = route::getAmende($id_citoyen);
    $prisonCasier = Casier::getPrison($id_citoyen);
    $prisonRoute = route::getPrison($id_citoyen);
    $total = traitement_amende($amendeCasier, $amendeRoute, $prisonCasier, $prisonRoute);

    // Traitement de l'information date
    if ($civil->permis == 0) {
      $value = traitementDate($civil->date_permis);
      $civil->date_permis = $value[0]; // On modifie la valeur de date permis
      $civil->date_trad = $value[1];
    }

    Flight::view()->display('fiche/montant_amende.twig', array(
      'civil' => $civil,
      'prison' => $total[1],
      'amende' => $total[0],
      'prisonAnnée' => $total[2],
      'prisonAnnée2' => $total[3]
    ));
  });

  Flight::route('/civil/@id_citoyen/close-casiers', function($id_citoyen)
  {
    verif_connecter();
    $agent = Agent::getInfoAgent();

    if ($agent->editer == 0) {
      Flight::redirect("/civil/$id_citoyen/calcul-amende");
      exit();
    }

    $Casier = Casier::getCasierEnCours($id_citoyen);
    $Route = Route::getRouteEnCours($id_citoyen);
    foreach ($Casier as $variable) {
      closeCasier($variable->id_delit, 3, $agent->lspd_id);
      addHistorique($agent->matricule, "5¤0¤0¤" . $variable->id_delit . "¤" . 2);
    }

    foreach ($Route as $variable) {
      closeRoute($variable->delit_id, 3, $agent->lspd_id);
      addHistorique($agent->matricule, "5¤0¤1¤" . $variable->delit_id . "¤" . 2);
    }
    Flight::redirect("/civil/$id_citoyen");
  });

  Flight::route('/civil/@id_citoyen/edit', function($id_citoyen)
  {
    verif_connecter();
    $civil = Personne::getinfoPersonne($id_citoyen);

    Flight::view()->display('edit/civil.twig', array(
      'perso' => $civil, // Information sur le civil
      'voitures' => Voiture::getListCarPersonne($id_citoyen), // Liste des véhicules
      'routes' => Route::getListDelitByC($id_citoyen), // Liste délits routiers
      'casiers' => Casier::getListCasier($id_citoyen), // Liste casier judiciaire
      'plaintes' => Plainte::getListPlainte($id_citoyen), // Liste des plaintes
      'policier' => Agent::getInfoAgentIDUser($civil->id) // Liste des plaintes
    ));
  });

  Flight::route('/civil/@id_civil/modification', function($id_civil)
  {
    verif_connecter();
    /* Variable récupéré dans le get */
    $phone = $_POST['telephone'];
    if (isset($_POST['metier'])) {
      $job = $_POST['metier'];
    }
    $drive = $_POST['permis'];
    /* Variable récupéré dans le get */

    $oldinfo = Personne::getinfoPersonne((int)$id_civil); // Ancienne info du civil
    $agent = Agent::getInfoAgent();

    /* Ajout dans l'historique */
    if ($oldinfo->phone != $phone) {
      addHistorique($agent->matricule, "3¤1¤0¤" . $id_civil . "¤" . $oldinfo->phone . "¤" . $phone);
    }

    if (isset($_POST['metier']) && ($oldinfo->job != $job)) {
      addHistorique($agent->matricule, "3¤1¤1¤" . $id_civil . "¤" . $oldinfo->job . "¤" . $job);
    }

    if ($oldinfo->permis != $drive) {
      addHistorique($agent->matricule, "3¤1¤2¤" . $id_civil . "¤" . $oldinfo->permis . "¤" . $drive);
      $time = time();
    }
    else {
      $time = $oldinfo->date_permis;
    }

    if ($agent->editer == 0) {
      Flight::redirect("/civil/$id_civil");
      exit();
    }

    if (isset($_POST['metier'])) {
      editCivil((int)$id_civil, $phone, $job, $drive, $time);
    }
    else {
      editCivil2((int)$id_civil, $phone, $drive, $time);
    }

    Flight::redirect("/civil/$id_civil");
  });

  Flight::route('/connexion', function()
  {
    Session::destroy(); // Destruction de session
    Flight::view()->display('connect.twig', array());
  });

  Flight::route('/delit-routier/@id_delit', function($id_delit)
  {
    verif_connecter();
    $delit = Route::getRoute($id_delit);
    if (!$delit) {
      Flight::redirect("/ajouter/casier-routier");
      return;
    }
    $personne = Personne::getinfoPersonne($delit->conducteur_id);
    $voiture = Voiture::getCarID($delit->v_id);

    // Traitement de l'information date
    if ($personne->permis == 0) {
      $value = traitementDate($personne->date_permis);
      $personne->date_permis = $value[0]; // On modifie la valeur de date permis
      $personne->date_trad = $value[1];
    }

    $delit->remarque = renderHTMLFromMarkdown(htmlspecialchars(strip_tags($delit->remarque)));
    Flight::view()->display('fiche/detail_route.twig', array(
      'delit' => $delit, // Info du delit
      'civil' => $personne, // Informations sur la personne
      'voiture' => $voiture, // Informations sur le véhicule
      'proprio' => Personne::getinfoPersonne($voiture->proprio), // Propriétaire du véhicule
      'ouvert' => Agent::getInfoAgentID($delit->enregistreur_id), // Policier ayant inscrit le casier
      'fermer' => Agent::getInfoAgentID($delit->acquite_par), // Policier ayant fermé le casier
      'policier' => Agent::getInfoAgentIDUser($personne->id)
    ));
  });

  Flight::route('/delit-routier/@id_delit/edit', function($id_delit)
  {
    verif_connecter();
    $delit = Route::getRoute($id_delit);
    $personne = Personne::getinfoPersonne($delit->conducteur_id);
    $voiture = Voiture::getCarID($delit->v_id);
    $ouvert = Agent::getInfoAgentID($delit->enregistreur_id);
    $agent = Agent::getInfoAgent();

    if ($delit->etat != 0) {
      Flight::redirect("/delit-routier/$id_delit");
      exit();
    }

    if ($agent->hab_1 != 2) {
      if ($agent->matricule != $ouvert->matricule) {
        Flight::redirect("/delit-routier/$id_delit");
        exit();
      }
    }

    Flight::view()->display('edit/route.twig', array(
      'delit' => $delit, // Info du delit
      'civil' => $personne, // Informations sur la personne
      'voiture' => $voiture, // Informations sur le véhicule
      'detail' => Delit::getinfoDelit($delit->delit_ref), // Détail du délit
      'proprio' => Personne::getinfoPersonne($voiture->proprio), // Propriétaire du véhicule
      'ouvert' => $ouvert, // Policier ayant inscrit le casier
      'fermer' => Agent::getInfoAgentID($delit->acquite_par), // Policier ayant fermé le casier
      'policier' => Agent::getInfoAgentIDUser($personne->id)
    ));
  });

  Flight::route('/delit-routier/@id_delit/modification', function($id_delit)
  {
    verif_connecter();
    /* Variable récupéré dans le get */
    $rapport = $_POST['rapport'];
    /* Variable récupéré dans le get */
    $agent = Agent::getInfoAgent();

    if ($agent->editer == 0) {
      Flight::redirect("/delit-routier/$id_delit/edit");
      exit();
    }

    $oldinfo = Route::getRoute($id_delit);

    if ($oldinfo->remarque != $rapport) {
      addHistorique($agent->matricule, "3¤3¤0¤" . $id_delit . "¤" . $oldinfo->remarque . "¤" . $rapport);
    }

    editRapportRoute((int)$id_delit, $rapport);

    Flight::redirect("/delit-routier/$id_delit");
  });

  Flight::route('/delit-routier/@id_delit/@etat', function($id_delit, $etat)
  {
    verif_connecter();
    $agent = Agent::getInfoAgent();

    if ($agent->editer == 0) {
      Flight::redirect("/delit-routier/$id_delit");
      exit();
    }
    closeRoute($id_delit, $etat, $agent->lspd_id);
    addHistorique($agent->matricule, "5¤0¤1¤" . $id_delit . "¤" . $etat);

    Flight::redirect("/delit-routier/$id_delit");
  });

  Flight::route('/detail-plainte/@id_plainte', function($id_plainte)
  {
    verif_connecter();
    $plainte = Plainte::getPlainte($id_plainte);
    if (!$plainte) {
      Flight::redirect("/recherche/plainte?civil_name=&civil_firstname=");
      return;
    }
    $personne = Personne::getinfoPersonne($plainte->plainte_sur);

    if (!($plainte->plainte_sur))
    {
      $personne = inconnu($plainte->plainte_sur_2); // On intègre des valeurs par défauts si nécessaires
    }

    // Traitement de l'information date
    if ($personne->permis == 0) {
      $value = traitementDate($personne->date_permis);
      $personne->date_permis = $value[0]; // On modifie la valeur de date permis
      $personne->date_trad = $value[1];
    }

    $plainte->detail_plainte = renderHTMLFromMarkdown(htmlspecialchars(strip_tags($plainte->detail_plainte)));
    Flight::view()->display('fiche/detail_plainte.twig', array(
      'civil' => $personne, // Infotmation sur la personne
      'plainte' => $plainte, // Détails de la plainte
      'deposeur' => Personne::getinfoPersonne($plainte->deposeur), // Information sur le déposeur
      'ouvert' => Agent::getInfoAgentID($plainte->enregistrer_par), // Policier ayant pris la plainte
      'fermer' => Agent::getInfoAgentID($plainte->fermer_par), // Policier ayant fermé la plainte
      'policier' => Agent::getInfoAgentIDUser($personne->id)
    ));
  });

  Flight::route('/detail-plainte/@id_plainte/edit', function($id_plainte)
  {
    verif_connecter();
    $plainte = Plainte::getPlainte($id_plainte);
    $personne = Personne::getinfoPersonne($plainte->plainte_sur);
    $ouvert = Agent::getInfoAgentID($plainte->enregistrer_par);
    $agent = Agent::getInfoAgent();

    if ($plainte->etat != 0) {
      Flight::redirect("/detail-plainte/$id_plainte");
      exit();
    }

    if ($agent->hab_1 != 2) {
      if ($agent->matricule != $ouvert->matricule) {
        Flight::redirect("/detail-plainte/$id_plainte");
        exit();
      }
    }

    if (!($plainte->plainte_sur)) {
      $personne = inconnu($plainte->plainte_sur_2); // On intègre des valeurs par défauts si nécessaires
    }

    Flight::view()->display('edit/plainte.twig', array(
      'personne' => $personne, // Infotmation sur la personne
      'plainte' => $plainte, // Détails de la plainte
      'deposeur' => Personne::getinfoPersonne($plainte->deposeur), // Information sur le déposeur
      'ouvert' => $ouvert, // Policier ayant pris la plainte
      'fermer' => Agent::getInfoAgentID($plainte->fermer_par), // Policier ayant fermé la plainte
      'policier' => Agent::getInfoAgentIDUser($personne->id)
    ));
  });

  Flight::route('/detail-plainte/@id_plainte/modification', function($id_plainte)
  {
    verif_connecter();
    /* Variable récupéré dans le get */
    $rapport = $_POST['rapport'];
    /* Variable récupéré dans le get */
    $agent = Agent::getInfoAgent();

    if ($agent->editer == 0) {
      Flight::redirect("/detail-plainte/$id_plainte/edit");
      exit();
    }

    $oldinfo = Plainte::getPlainte($id_plainte);

    if ($oldinfo->remarque != $rapport) {
      addHistorique($agent->matricule, "3¤4¤0¤" . $id_plainte . "¤" . $oldinfo->plainte_sur . "¤" . $oldinfo->detail_plainte . "¤" . $rapport);
    }

    editRapportPlainte((int)$id_plainte, $rapport);

    Flight::redirect("/detail-plainte/$id_plainte");
  });

  Flight::route('/detail-plainte/@id_plainte/@etat', function($id_plainte, $etat)
  {
    verif_connecter();
    $agent = Agent::getInfoAgent();

    if ($agent->editer == 0) {
      Flight::redirect("/detail-plainte/$id_plainte");
      exit();
    }

    closePlainte($id_plainte, $etat, $agent->lspd_id);
    addHistorique($agent->matricule, "5¤0¤2¤" . $id_plainte . "¤" . $etat);

    Flight::redirect("/detail-plainte/$id_plainte");
  });

  Flight::route('/discussion-interne', function()
  {
    verif_connecter();

    Flight::view()->display('tchat.twig');
  });

  Flight::route('/chat', function()
  {
    verif_connecter();
    /* On doit analyser la demande faite via l'URL (GET) afin de déterminer
    si on souhaite récupérer les messages ou en écrire un  */
    $task = "list";

    if(array_key_exists("task", $_GET)) {
      $task = $_GET['task'];
    }

    if($task == "write") {
      postMessage();
    } else {
      getMessages();
    }
  });

  Flight::route('/dossier-candidat', function()
  {
    verif_connecter();
    verif_enseignant(); // Vérifie que la personne soit bien un enseignant
    /* Variable récupéré dans le get */
    $identifiant = $_GET['identifiant'];
    /* Variable récupéré dans le get */
    $en_attente = Candidature::getListCandidature(1);
    $refusee = Candidature::getListCandidature(2);
    $accepte = Candidature::getListCandidature(3);
    $personne = NULL;
    $ecole = NULL;
    $vacance = NULL;
    $travail = NULL;
    $concat = NULL;

    if ($identifiant != NULL) {
      $personne = Candidature::getCandidature($identifiant);
      $personne->motivation_lspd = renderHTMLFromMarkdown(htmlspecialchars(strip_tags($personne->motivation_lspd)));
      $ecole = explode('-', $personne->detail_ecole);
      $vacance = explode('-', $personne->detail_vacance);
      $travail = explode('-', $personne->detail_travail);
      $concat = explode('-', $personne->concat);
    }

    Flight::view()->display('ecole/dossier_candidat.twig', array(
      'attente' => $en_attente,
      'refuser' => $refusee,
      'accepter' => $accepte,
      'personne' => $personne,
      'ecole' => $ecole,
      'vacance' => $vacance,
      'travail' => $travail,
      'concat' => $concat
    ));
  });

  Flight::route('/dossier-candidat/@decision/@num', function($decision, $num)
  {
    verif_connecter();
    verif_enseignant();

    switch ($decision) {
      case 'accepter':
        editCandidature($num, 3);
        addHistorique(Agent::getInfoAgent()->matricule, "1¤1¤3¤" . $num);
        break;
      case 'refuser':
        editCandidature($num, 2);
        addHistorique(Agent::getInfoAgent()->matricule, "1¤1¤4¤" . $num);
        break;
      default:
        editCandidature($num, 2);
        addHistorique(Agent::getInfoAgent()->matricule, "1¤1¤4¤" . $num);
        break;
    }
    Flight::redirect("/dossier-candidat?identifiant=");
  });

  Flight::route('/formation', function()
  {
    verif_connecter();
    Flight::view()->display('page_formation.twig');
  });

  Flight::route('/nouveau-mot-de-passe', function()
  {
    verif_connecter();
    Flight::view()->display('new_mdp.twig');
  });

  Flight::route("/" . serveurIni('Faction', 'membre'), function()
  {
    verif_connecter();
    verif_enseignant(); // Vérifie que la personne soit bien un enseignant
    /* Variable récupéré dans le get */
    $nom = $_GET['flic_name'];
    $prenom = $_GET['flic_firstname'];
    $mat = $_GET['flic_matricule'];
    /* Variable récupéré dans le get */

    Flight::view()->display('recherche/liste_policier.twig', array(
      'policiers' => Agent::getListAgentTri($nom, $prenom, $mat),
      'nom' => $nom,
      'prenom' => $prenom,
      'matricule' => $mat
    ));
  });

  Flight::route("/" . serveurIni('Faction', 'membre') . "/@matricule_policier", function($matricule_policier)
  {
    verif_connecter();
    $agent = Agent::getInfoAgentMatricule($matricule_policier);
    if (!$agent) {
      Flight::redirect("/");
      return;
    }

    Flight::view()->display('fiche/policier.twig', array(
      'agent' => $agent,
      'voitures' => Voiture::getListCarPolice($agent->user_id), // Liste des voitures
      'rapports' => Rapport::getListRapportID($agent->lspd_id), // Liste des rapports
      'armes' => Arme::getListIDUser($agent->user_id)
    ));
  });

  Flight::route("/" . serveurIni('Faction', 'membre') . "/@matricule_policier/edit", function($matricule_policier)
  {
    verif_connecter();
    verif_enseignant(); // Vérifie que l'agent est enseignant

    Flight::view()->display('edit/policier.twig', array(
      'agent' => Agent::getInfoAgentMatricule($matricule_policier),
      'grades' => Grade::getList()
    ));
  });

  Flight::route("/" . serveurIni('Faction', 'membre') . "/@matricule_policier/modification", function($matricule_policier)
  {
    verif_connecter();
    verif_enseignant(); // Vérifie que l'agent est enseignant

    /* Variable récupéré dans le get */
    $grade = $_POST['grade'];
    $note = $_POST['note'];
    if (isset($_POST['hab1'])) {
      $hab1 = $_POST['hab1'];
    }
    else {
      $hab1 = 1;
    }

    if (isset($_POST['hab2'])) {
      $hab2 = $_POST['hab2'];
    }
    else {
      $hab2 = 1;
    }

    if (isset($_POST['hab3'])) {
      $hab3 = $_POST['hab3'];
    }
    else {
      $hab3 = 1;
    }

    if (isset($_POST['hab4'])) {
      $hab4 = $_POST['hab4'];
    }
    else {
      $hab4 = 1;
    }

    if (isset($_POST['hab5'])) {
      $hab5 = $_POST['hab5'];
    }
    else {
      $hab5 = 1;
    }

    if (isset($_POST['hab6'])) {
      $hab6 = $_POST['hab6'];
    }
    else {
      $hab6 = 1;
    }

    if (isset($_POST['hab7'])) {
      $hab7 = $_POST['hab7'];
    }
    else {
      $hab7 = 1;
    }

    if (isset($_POST['hab8'])) {
      $hab8 = $_POST['hab8'];
    }
    else {
      $hab8 = 1;
    }

    if (isset($_POST['hab9'])) {
      $hab9 = $_POST['hab9'];
    }
    else {
      $hab9 = 1;
    }

    if (isset($_POST['hab10'])) {
      $hab10 = $_POST['hab10'];
    }
    else {
      $hab10 = 1;
    }

    if (isset($_POST['hab11'])) {
      $hab11 = $_POST['hab11'];
    }
    else {
      $hab11 = 1;
    }

    if (isset($_POST['hab12'])) {
      $hab12 = $_POST['hab12'];
    }
    else {
      $hab12 = 1;
    }

    if (isset($_POST['hab13'])) {
      $hab13 = $_POST['hab13'];
    }
    else {
      $hab13 = 1;
    }

    if (isset($_POST['hab14'])) {
      $hab14 = $_POST['hab14'];
    }
    else {
      $hab14 = 1;
    }

    if (isset($_POST['hab15'])) {
      $hab15 = $_POST['hab15'];
    }
    else {
      $hab15 = 1;
    }
    /* Variable récupéré dans le get */



    $id_cop = Agent::getInfoAgentMatricule($matricule_policier);

    if ($grade == 1) {
      editLicenciement($id_cop->lspd_id);
      addHistorique(Agent::getInfoAgent()->matricule, "1¤1¤1¤" . $matricule_policier);
    }
    else {
      AddHistoriqueModifAgent($id_cop, $matricule_policier, $grade, $hab1, $hab2, $hab3, $hab4, $hab5, $hab6, $hab7, $hab8, $hab9, $hab10, $hab11, $hab12, $hab13, $hab14, $hab15, $note);
      editCop($id_cop->lspd_id, $grade, $hab1, $hab2, $hab3, $hab4, $hab5, $hab6, $hab7, $hab8, $hab9, $hab10, $hab11, $hab12, $hab13, $hab14, $hab15, $note);
    }
    Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$matricule_policier");
  });

  Flight::route('/ppa', function()
  {
    verif_connecter();
    verif_enseignant(); // Vérifie que la personne soit bien un enseignant

    Flight::view()->display('ecole/liste_ppa.twig', array(
      'ppa' => Personne::getPPA(1),
      'nonppa' => Personne::getPPA(2)
    ));
  });

  Flight::route('/rapport', function()
  {
    verif_connecter();
    verif_enseignant(); // Vérifie que la personne soit bien un enseignant
    $info = Agent::getInfoAgent();

    Flight::view()->display('ecole/recherche_rapport.twig', array(
      'listes' => Rapport::liste_non_vu($info->lspd_id)
    ));
  });

  Flight::route('/rapport/add', function()
  {
    verif_connecter();
    Flight::view()->display('add/add_rapport.twig', array(
      'agents' => Agent::getListAgent()
    ));
  });

  Flight::route('/rapport/@id_rapport', function($id_rapport)
  {
    verif_connecter();
    $rapport = Rapport::getRapportID($id_rapport);

    $agent = Agent::getInfoAgent();

    // On indique comme quoi l'agent à déjà vu le rapport
    if (RapportVU::non_vu($agent->lspd_id, $rapport->id) == NULL) {
      addRapportVu($agent->lspd_id, $rapport->id);
    }

    if ($agent->grade_id < serveurIni('Faction', 'gradeLevelBiffure')) {
      $rapport->contenu = renderHTMLFromMarkdown(htmlspecialchars(strip_tags($rapport->contenu)));
      $rapport->contenu = biffage($rapport->contenu);
    }

    Flight::view()->display('fiche/rapport.twig', array(
      'rapport' => $rapport, // Information sur le rapport
      'agent' => Agent::getInfoAgentID($rapport->concerne), // Information sur l'agent
      'deposeur' => Agent::getInfoAgentID($rapport->editeur) // Information sur le déposeur
    ));
  });

  Flight::route('/recherche/civil', function()
  {
    verif_connecter();
    /* Variable récupéré dans le get */
    $nom = $_GET['civil_name'];
    $prenom = $_GET['civil_firstname'];
    $phone = $_GET['civil_phone'];
    $job = $_GET['civil_job'];
    /* Variable récupéré dans le get */

    Flight::view()->display('recherche/research_civil.twig', array(
      'civils' => Personne::getListPersonneTri($nom, $prenom, $phone, $job),
      'nom' => $nom,
      'prenom' => $prenom,
      'phone' => $phone,
      'job' => $job
    ));
  });

  Flight::route('/recherche/validiter/plaque', function()
  {
    header("Access-Control-Allow-Origin: *");
    verif_connecter();
    $plaque = $_POST["plaque"];

    // Récupération de l'info image véhicule
    $car = Voiture::getCar($plaque);
    if ($car == NULL) {
      // La plaque n'est pas enregistrer
      $data = ['etat' => 0];
    }
    else {
      // La plaque est enregistré
      $proprio = Personne::getinfoPersonne($car->proprio);
      $data = [
        'etat' => 1,
        'nom' => $proprio->nom,
        'prenom' => $proprio->prenom
      ];
    }

    Flight::json($data);
  });

  Flight::route('/recherche/doublon/civil', function()
  {
    header("Access-Control-Allow-Origin: *");
    verif_connecter();
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];

    // Récupération de l'info image véhicule

    $info = Personne::getIDPersonne($nom, $prenom);
    if ($info == NULL) {
      // La personne n'est enregistrée à ce nom
      $data = ['etat' => 0];
    }
    else {
      // La personne est enregistrée
      $data = ['etat' => 1];
    }

    Flight::json($data);
  });

  Flight::route('/recherche/info_delit', function()
  {
    header("Access-Control-Allow-Origin: *");
    verif_connecter();
    verif_admin();
    $nom = $_POST["id_delit"];

    // Récupération de l'info image véhicule

    $info = Delit::getinfoDelit($nom);

    $data = [
      'amende' => $info->amende,
      'prison' => $info->temps_prison
    ];

    Flight::json($data);
  });

  # On pourra tout rassembler en un
  Flight::route('/recherche/photo/arme', function()
  {
    header("Access-Control-Allow-Origin: *");
    verif_connecter();
    $modele = $_POST["type"];

    // Récupération de l'info image véhicule
    $info = modelArme::getInfo($modele);
    $data = [
      'couleur' => $info->photo,
      'modele' => $info->nom
    ];

    Flight::json($data);
  });

  Flight::route('/recherche/photo/cop', function()
  {
    header("Access-Control-Allow-Origin: *");
    verif_connecter();
    $id = $_POST["id"];

    // Récupération de l'info image véhicule
    $info = Agent::getInfoAgentID($id);
    $data = [
      'grade' => $info->grade,
      'nom' => $info->nom,
      'photo' => $info->photo
    ];

    Flight::json($data);
  });

  Flight::route('/recherche/photo/personne', function()
  {
    header("Access-Control-Allow-Origin: *");
    verif_connecter();
    $id = $_POST["id"];

    // Récupération de l'info image véhicule
    $info = Personne::getinfoPersonne($id);
    $data = [
      'nom' => $info->nom,
      'prenom' => $info->prenom,
      'photo' => $info->photo
    ];

    Flight::json($data);
  });

  Flight::route('/recherche/photo/vehicule', function()
  {
    header("Access-Control-Allow-Origin: *");
    verif_connecter();
    $modele = $_POST["type"];

    // Récupération de l'info image véhicule
    $info = ModelesV::getImage($modele);
    $data = [
      'couleur' => $info->lien,
      'modele' => $info->nom
    ];

    Flight::json($data);
  });

  Flight::route('/recherche/photo/plaque', function()
  {
    header("Access-Control-Allow-Origin: *");
    verif_connecter();
    $plaque = $_POST["type"];

    // Récupération de l'info image véhicule
    $info = Voiture::getCarID($plaque);
    $data = [
      'couleur' => $info->lien,
      'modele' => $info->nom
    ];

    Flight::json($data);
  });

  Flight::route('/recherche/plainte', function()
  {
    verif_connecter();
    /* Variable récupéré dans le get */
    $nom = $_GET['civil_name'];
    $prenom = $_GET['civil_firstname'];
    /* Variable récupéré dans le get */

    Flight::view()->display('recherche/research_plainte.twig', array(
      'plaintes' => InfoPlainte::getListPlainte($nom, $prenom),
      'nom' => $nom,
      'prenom' => $prenom
    ));
  });

  Flight::route('/recherche/vehicule', function()
  {
    verif_connecter();
    /* Variable récupéré dans le get */
    $modele = $_GET['modele'];
    $plaque = $_GET['plaque'];
    $couleur = $_GET['couleur'];
    /* Variable récupéré dans le get */

    Flight::view()->display('recherche/research_vehicule.twig', array(
      'voitures' => Voiture::getListCarTri($modele, $plaque, $couleur),
      'modele' => $modele,
      'plaque' => $plaque,
      'couleur' => $couleur
    ));
  });

  Flight::route('/recrutement', function()
  {
    verif_connecter();
    verif_enseignant();
    Flight::view()->display('ecole/recrutement_police.twig', array(
      'personnes' => Personne::OldCop(),
      'cops' => Agent::getListAgent(),
      'oldcops' => Agent::getListOldAgent()
    ));
  });

  /* Utilisé pour afficher l'état des recrutements */
  Flight::route('/recrutement/etat', function()
  {
    header("Access-Control-Allow-Origin: *");
    $data = [
      'etat' => serveurIni('Faction', 'etatRecrutement')
    ];

    Flight::json($data);
  });

  Flight::route('/vehicule/@plaque', function($plaque)
  {
    verif_connecter();
    $voiture = Voiture::getCar($plaque);
    if (!$voiture) {
      Flight::redirect("/recherche/vehicule?modele=&plaque=&couleur=");
      return;
    }

    $enregistreur = Agent::getInfoAgentID($voiture->enregistreur);

    Flight::view()->display('fiche/voiture.twig', array(
      'proprio' => Personne::getinfoPersonne($voiture->proprio), // Information sur le propriétaire véhicule
      'voiture' => $voiture, // Information sur le véhicule
      'policier' => Agent::getInfoAgentMatricule($enregistreur->matricule), // Information sur le policier qui a enregistrer le véhicule
      'delits' => Route::getListDelitByV($voiture->v_id) // Liste des délits routier
    ));
  });

  Flight::route('/vehicule/@plaque/edit', function($plaque)
  {
    verif_connecter();
    $voiture = Voiture::getCar($plaque);
    $enregistreur = Agent::getInfoAgentID($voiture->enregistreur);

    Flight::view()->display('edit/vehicule.twig', array(
      'proprio' => Personne::getinfoPersonne($voiture->proprio), // Information sur le propriétaire véhicule
      'voiture' => $voiture, // Information sur le véhicule
      'policier' => Agent::getInfoAgentMatricule($enregistreur->matricule), // Information sur le policier qui a enregistrer le véhicule
      'delits' => Route::getListDelitByV($voiture->v_id), // Liste des délits routier
      'civils' => Personne::getListPersonne(2) // v1.6.1
    ));
  });

  Flight::route('/delit/edit/value', function()
  {
    verif_connecter();
    verif_admin();

    $id = $_POST['id_delit'];
    $amd_delit = $_POST['amd_delit'];
    $tps_delit = $_POST['tps_delit'];

    $info = Delit::getinfoDelit($id);

    if (($info->amende != $amd_delit) and ($amd_delit != '')) {
      editDelitAmende($id, $amd_delit);
      addHistorique(Agent::getInfoAgent()->matricule, "0¤3¤0¤" . $id . "¤" . $info->amende . "¤" . $amd_delit);
    }

    if (($info->temps_prison != $tps_delit) and ($amd_delit != '')) {
      editDelitPrison($id, $tps_delit);
      addHistorique(Agent::getInfoAgent()->matricule, "0¤3¤1¤" . $id . "¤" . $info->temps_prison . "¤" . $tps_delit);
    }
  });

  Flight::route('/vehicule/@plaque/modification', function($plaque)
  {
    verif_connecter();
    /* Variable récupéré dans le get */
    $color = $_POST['couleur'];
    $road = $_POST['circu'];
    $proprio = $_POST['proprio']; // v1.6.1
    /* Variable récupéré dans le get */

    $agent = Agent::getInfoAgent();
    if ($agent->editer == 0) {
      Flight::redirect("/vehicule/$plaque");
      exit();
    }

    $oldinfo = Voiture::getCar($plaque);

    if ($oldinfo->couleur != $color) {
      addHistorique($agent->matricule, "3¤2¤0¤" . $plaque . "¤" . $oldinfo->couleur . "¤" . $color);
    }

    if ($oldinfo->circulation != $road) {
      addHistorique($agent->matricule, "3¤2¤1¤" . $plaque . "¤" . $oldinfo->circulation . "¤" . $road);
    }

    if ($oldinfo->proprio != $proprio) {  // v1.6.1
      addHistorique($agent->matricule, "3¤2¤2¤" . $plaque . "¤" . $oldinfo->proprio . "¤" . $proprio);
    }

    editVehicule($plaque, $color, $road, $proprio); // v1.6.1

    Flight::redirect("/vehicule/$plaque");
  });

  Flight::route('/add/cop', function()
  {
    verif_connecter();
    /* Variable de POST */
    $id_civil = $_POST['nom_civil'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    if ($agent->hab_1 != 2) { // N'est pas autorisé à ajouter
      Flight::redirect("/recrutement");
    }
    else {
      $matricule = addCop($id_civil);
      addHistorique($agent->matricule, "1¤1¤0¤" . $matricule);
      Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$matricule"); // Redirection vers la page de l'agent
    }
  });

  Flight::route('/add/prof', function()
  {
    verif_connecter();
    verif_admin();
    /* Variable de POST */
    $id_cop = $_POST['nom_cop'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    updateProf($id_cop);
    addHistorique($agent->matricule, "0¤4¤0¤". Agent::getInfoAgentID($id_cop)->matricule);
    Flight::redirect("/administration/ajout");
  });

  Flight::route('/insert/casier', function()
  {
    verif_connecter();
    /* Variable de POST */
    $delit = $_POST['delit_name'];
    $rapport = $_POST['rapport'];
    $proprio = $_POST['casier_owner'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    if ($agent->ajouter == 0 or $delit == 0) {
      Flight::redirect("/ajouter/casier");
    }
    else {
      addCasier($delit, $rapport, $proprio, $agent->lspd_id);
      addHistorique($agent->matricule, "4¤0¤0¤" . $delit . "¤" . $proprio);

      $info = Casier::getIDCasier($proprio, $agent->lspd_id);
      Flight::redirect("/casier-judiciaire/$info->id_delit");
    }
  });

  Flight::route('/insert/civil', function()
  {
    verif_connecter();
    /* Variable de POST */
    $nom = ucfirst($_POST['civil_name']);
    $prenom = ucfirst($_POST['civil_firstame']);
    $dob = $_POST['civil_dob'];
    if (!isset($_POST['civil_phone'])) {
      $phone = NULL;
    }
    else {
      $phone = $_POST['civil_phone'];
    }
    $nationality = ucfirst($_POST['civil_nationality']);
    $sexe = $_POST['civil_sex'];
    $permis = $_POST['civil_licence'];
    $job = $_POST['civil_job'];
    $photo = "temp.png";
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    if ($agent->ajouter == 0) { // N'est pas autorisé à ajouter
      Flight::redirect("/ajouter/civil");
    }
    else {
      addPersonne($nom, $prenom, $dob, $phone, $nationality, $sexe, $permis, $photo, $job);
      addHistorique($agent->matricule, "4¤0¤1¤$nom $prenom");

      $info = Personne::getIDPersonne($nom, $prenom);
      Flight::redirect("/civil/$info->id");
    }
  });

  Flight::route('/insert/delit', function()
  {
    verif_connecter();
    verif_admin();

    $nom = $_POST['nom'];
    $montant = $_POST['amende'];
    $prison = $_POST['temps'];
    $type = $_POST['type'];

    $agent = Agent::getInfoAgent();
    addDelit($nom, $montant, $prison, $type);
    addHistorique($agent->matricule, "0¤1¤" . ($type+1) . "¤" . $nom);
  });

  Flight::route('/insert/weapon/categorie', function()
  {
    verif_connecter();
    verif_admin();

    $cat = $_POST['categorie'];
    $pref = $_POST['prefix'];

    $agent = Agent::getInfoAgent();
    addWeaponCategorie($cat, $pref);
    addHistorique($agent->matricule, "0¤1¤7¤" . $cat . "¤" . $pref);
  });

  Flight::route('/insert/img/@type', function($type)
  {
    verif_connecter();
    verif_admin();
    /* Début des vérifications de sécurité et upload*/ // A vérifier si ça marche en ligne
    $dossier = "assets/img/$type/";
    $fichier = basename($_FILES['photo']['name']);
    $taille_maxi = 5000000; // Limitation de la taille à 5 Mo
    $taille = filesize($_FILES['photo']['tmp_name']);
    $extensions = array('.png', '.jpg', '.jpeg');
    $extension = strrchr($_FILES['photo']['name'], '.');

    if(!in_array($extension, $extensions)) { // Si l'extension n'est pas dans le tableau
      Flight::redirect("/administration/ajout");
      exit();
    }

    if($taille>$taille_maxi) {
      Flight::redirect("/administration/ajout");
      exit();
    }

    if(!isset($erreur)) { // S'il n'y a pas d'erreur, on upload
      // On formate le nom du fichier ici...
      $fichier = strtr($fichier,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
      $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier); // On remplace les caractères spéciaux par un tirets
      $_FILES['photo']['name'] = str_replace(' ', '-', $_FILES['photo']['name']); // On remplace les caractères spéciaux par un tirets

      move_uploaded_file($_FILES['photo']['tmp_name'], $dossier . $fichier);
    }
    /* Fin vérification de sécurité et upload */

    $agent = Agent::getInfoAgent();

    switch ($type) {
      case 'vehicule':
        $nom = $_POST['vehicule_name'];
        addImgCar($nom, $_FILES['photo']['name']);
        addHistorique($agent->matricule, "0¤1¤5¤" . $nom);
        Flight::redirect("/ajouter/vehicule");
        break;
      case 'identité':
        $id_civil = $_POST['nom'];
        addImgCivil($id_civil, $_FILES['photo']['name']);
        addHistorique($agent->matricule, "0¤1¤6¤" . $id_civil);
        Flight::redirect("/civil/$id_civil");
        break;
      case 'arme':
        $name = $_POST['nom_name'];
        $cat = $_POST['cat_weapon'];
        addWeaponList($name, $cat, $_FILES['photo']['name']);
        addHistorique($agent->matricule, "0¤1¤8¤" . $name);
        Flight::redirect("/administration/ajout");
        break;
      default:
        Flight::redirect("/administration/ajout");
        break;
    }
  });

  Flight::route('/insert/plainte', function()
  {
    verif_connecter();
    /* Variable de POST */
    $deposeur = $_POST['deposeur'];
    $citoyen = $_POST['citoyen_1'];
    $detail = $_POST['plainte'];
    /* Variable de POST */

    if ($citoyen == 0) {
      $citoyen = 1;
    }

    $agent = Agent::getInfoAgent();
    if ($agent->ajouter == 0 or $deposeur == 0) { // N'est pas autorisé à ajouter
      Flight::redirect("/ajouter/plainte");
    }
    else {
      addPlainte($deposeur, $citoyen, $detail, $agent->lspd_id);
      addHistorique($agent->matricule, "4¤0¤2¤" . $deposeur . "¤" . $citoyen);

      $info = Plainte::getIDPlainte($deposeur, $citoyen, $agent->lspd_id);
      Flight::redirect("/detail-plainte/$info->id");
    }
  });

  Flight::route('/insert/rapport', function()
  {
    verif_connecter();
    /* Variable de POST */
    $depo = $_POST['agent_vise'];
    $titre = $_POST['titre_rapport'];
    $rapport = traitementRapport($_POST['rapport']);
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    if ($agent->ajouter == 0) { // N'est pas autorisé à ajouter
      Flight::redirect("/rapport");
    }
    else {
      addRapport($depo, $titre, $rapport, $agent->lspd_id);
      addHistorique($agent->matricule, "4¤0¤3¤" . $agent->matricule . "¤" . Agent::getInfoAgentID($depo)->matricule);
      Flight::redirect("/");
    }
  });

  Flight::route('/insert/routier', function()
  {
    verif_connecter();
    /* Variable de POST */
    $conducteur = $_POST['casier_owner'];
    $vehicle = $_POST['vehicule_delit'];
    $delit = $_POST['delit_name'];
    $rapport = $_POST['rapport'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    if ($agent->ajouter == 0 or $delit == 0) { // N'est pas autorisé à ajouter
      Flight::redirect("/ajouter/casier-routier");
    }
    else {
      addRoutier($conducteur, $vehicle, $delit, $rapport, $agent->lspd_id);
      addHistorique($agent->matricule, "4¤0¤4¤" . $delit . "¤" . $conducteur . "¤" . Voiture::getCarID($vehicle)->plaque);

      $info = Route::getIDRoute($vehicle, $conducteur, $agent->lspd_id);
      Flight::redirect("/delit-routier/$info->delit_id");
    }
  });

  Flight::route('/insert/voiture', function()
  {
    verif_connecter();
    /* Variable de POST */
    $model = $_POST['vehicule_model'];
    $color = ucfirst($_POST['vehicule_color']);
    $plaque = strtoupper($_POST['vehicule_plaque']); // On met la plaque en majuscule
    $proprio = $_POST['vehicule_owner'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    if ($agent->ajouter == 0) { // N'est pas autorisé à ajouter
      Flight::redirect("/ajouter/vehicule");
    }
    else {
      addVoiture($model, $color, $plaque, $proprio, $agent->lspd_id);
      addHistorique($agent->matricule, "4¤0¤5¤$plaque" . "¤" . $proprio);

      Flight::redirect("/vehicule/$plaque"); // Redirige vers la page
    }
  });

  Flight::route('/insert/weapon', function()
  {
    verif_connecter();
    /* Variable de POST */
    $model = $_POST['weapon_model'];
    $proprio = $_POST['weapon_owner'];
    /* Variable de POST */

    do {
      $serie = rand(1000, 9999);
    }
    while(listeArme::getNumSerie($serie));

    $agent = Agent::getInfoAgent();
    if ($agent->ajouter == 0) { // N'est pas autorisé à ajouter
      Flight::redirect("/ajouter/arme");
    }
    else {
      addWeapon($serie, $model, $proprio, $agent->lspd_id);
      addHistorique($agent->matricule, "4¤0¤6¤$model" . "¤" . $proprio); // HERE

      Flight::redirect("/arme/$serie"); // Redirige vers la page
    }
  });

  /* ========================================== */
  /*      Section des éditions dans la BDD      */
  /* ========================================== */

  Flight::route('/edit/ppa/@etat', function($etat)
  {
    verif_connecter();
    /* Variable de POST */
    $personne = $_POST['nom_civil'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    if ($agent->hab_1 != 2) { // N'est pas autorisé à faire la manipulation
      Flight::redirect("/");
    }
    else {
      editPPA($personne, $etat);
      switch ($etat) {
        case '0':
          $message = "1¤0¤0¤$personne";
          break;
        case '1':
          $message = "1¤0¤1¤$personne";
          break;
        default:
          $message = "1¤0¤0¤$personne";
          break;
      }
      addHistorique($agent->matricule, $message);

      Flight::redirect("/ppa"); // Redirige vers la page
    }
  });

  Flight::route('/edit/rehabilitaton', function()
  {
    verif_connecter();
    /* Variable de POST */
    $id_cop = $_POST['id_cop'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    if ($agent->hab_1 != 2) { // N'est pas autorisé à ajouter
      Flight::redirect("/recrutement");
    }
    else {
      editCop($id_cop, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, "");
      $old = Agent::getInfoAgentID($id_cop);
      addHistorique($agent->matricule, "1¤1¤2¤$old->matricule");
      Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$old->matricule");
    }
  });

  Flight::route('/edit/registre/@etat', function($etat)
  {
    verif_connecter();
    verif_admin();
    /* Variable de POST */
    $personne = $_POST['nom_civil'];
    /* Variable de POST */

    editPresence($personne, $etat);
    switch ($etat) {
      case '0':
        $message = "0¤2¤2¤$personne";
        break;
      case '1':
        $message = "0¤2¤3¤$personne";
        break;
      default:
        $message = "0¤2¤2¤$personne";
        break;
    }
    addHistorique(Agent::getInfoAgent()->matricule, $message);

    Flight::redirect("/administration/modification"); // Redirige vers la page
  });

  Flight::route('/edit/licenciement', function()
  {
    verif_connecter();
    /* Variable de POST */
    $id_cop = $_POST['id_cop_li'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    if ($agent->hab_1 != 2) {
      Flight::redirect("/");
    }
    else {
      editLicenciement($id_cop);
      addHistorique($agent->matricule, "1¤1¤1¤" . Agent::getInfoAgentID($id_cop)->matricule);

      Flight::redirect("/recrutement"); // Redirige vers la page
    }
  });

  Flight::route('/edit/mdp', function()
  {
    verif_connecter();
    /* Variable de POST */
    $ancien = $_POST['ancien_mdp'];
    $new = $_POST['new_mdp'];
    $new2 = $_POST['confirm_mdp'];
    /* Variable de POST */

    if (($new != $new2) or ($ancien == $new2)) {
      Flight::redirect("/nouveau-mot-de-passe");
      exit();
    }

    $agent = Agent::getInfoAgent();
    $policier = Policier_t::getInfoAgentMatricule($agent->matricule);

    $ancien_ashed = program_crypt($ancien, $policier->Sel_de_table);

    if ($policier->Passwd != $ancien_ashed) { // L'ancien mot de passe n'est pas identique au nouveau
      Flight::redirect("/nouveau-mot-de-passe");
      exit();
    }

    $salt = bin2hex(random_bytes(15)); // Génération  du sault
    $new_mdp_hashed = program_crypt($new, $salt); // On génère le mot de passe avec le nouveau salt
    mise_a_jour_mdp($agent->matricule, $new_mdp_hashed, $salt);
    addHistorique($agent->matricule, "2¤0¤0");

    Flight::redirect("/connexion"); // Redirige vers la page
  });

  Flight::route('/edit/mdp/admin', function()
  {
    verif_connecter();
    verif_admin();
    /* Variable de POST */
    $id_lspd = $_POST['id'];
    $new_mdp = $_POST['dscpt'];
    /* Variable de POST */

    $salt = bin2hex(random_bytes(15)); // On génère un nouveau salt
    $new_mdp_hashed = program_crypt($new_mdp, $salt);
    $agent = Agent::getInfoAgentID($id_lspd);
    mise_a_jour_mdp($agent->matricule, $new_mdp_hashed, $salt);
    addHistorique(Agent::getInfoAgent()->matricule, "0¤2¤0¤$agent->matricule");
  });


  /* ============================================= */
  /*      Section des suppression dans la BDD      */
  /* ============================================= */

  Flight::route('/delete/@adress_ip', function($adresse_ip)
  {
    verif_connecter();
    verif_admin();

    $adresse_ip = str_replace('-', '.', $adresse_ip);
    deleteIP($adresse_ip);
    addHistorique(Agent::getInfoAgent()->matricule, "0¤2¤1¤$adresse_ip");
    Flight::redirect("/administration/modification"); // Redirige vers la page
  });

  /* ============================================== */
  /*      Section d'identification dans la BDD      */
  /* ============================================== */

  Flight::route('/connect', function()
  {
    header("Access-Control-Allow-Origin: *");
    /* Variable de POST */
    $matricule = $_POST['user_matricule'];
    $mdp = $_POST['user_mdp'];
    $ip_user = $_SERVER['REMOTE_ADDR'];
    /* Variable de POST */

    // protocole_connexion($matricule, $mdp, $ip_user);
    $etat = protocole_connexion($matricule, $mdp, $ip_user);
    switch ($etat) {
      case 1:
        $message = "Tentative de connexion trop importante !";
        break;
      case 2:
        $message = "Le matricule saisi n'est pas valide";
        break;
      case 3:
        $message = "Le matricule saisi n'est plus en service";
        break;
      case 4:
        $message = "Le mot de passe entré n'est pas valide";
        break;
      default:
        $message = $etat;
        $etat = 0;
        break;
    }

    $data = [
      'etat' => $etat,
      'message' => $message
    ];

    Flight::json($data);
  });

  Flight::route('/impression/@type/@numero', function($type, $numero)
  {
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

  /* ================================================= */
  /*      Section sécurité des chemins sur le site     */
  /* ================================================= */

  Flight::map('notFound', function(){
    verif_connect();
    Flight::redirect("/"); // Redirige vers la page
  });

  Flight::start(); // Denrière ligne du fichier
?>
