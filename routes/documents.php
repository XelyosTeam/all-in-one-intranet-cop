<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

include "src/documents.php";

// Flight::route('POST|GET /documents/gettree', function() {
//   $agent = Agent::getInfoAgent();
//
//   /* Variable POST */
//   $id = 1; //$_POST['id'];
//   /* Variable POST */
//
//   $data = getTree($id, $agent);
//   Flight::json($data);
// });

Flight::route('POST /documents/post/move/@type', function($type) {
  header("Access-Control-Allow-Origin: *");
  $agent = Agent::getInfoAgent();
  $agent2 = Agent::getInfoAgent();

  /* Variable POST */
  $id = $_POST['id'];
  $from = $_POST['from'];
  $to = $_POST['to'];
  /* Variable POST */

  if ($from == $to) {
    return Flight::json(array(
      "send" => false
    ));
  }

  if ($type == 'folder' and (($id == $to) or ($from == $id))) {
    return Flight::json(array(
      "send" => false
    ));
  }

  if ($type == 'folder' and $id == 1) {
    return Flight::json(array(
      "send" => false
    ));
  }

  $agentFrom = defineAutorisations('folder', $from, $agent);
  $agentTo = defineAutorisations('folder', $to, $agent2);

  /* Vérification des permissions */
  switch ($type) {
    case 'document':
      $permission = array(2, 3, 4);
      $valueHistorique = 0;
      break;
    case 'folder':
      $permission = array(3, 4);
      $valueHistorique = 1;
      break;
  }

  if ((!in_array($agentFrom->docAccess, $permission)) or (!in_array($agentTo->docAccess, $permission))) {
    if ($agent->admin != 1) {
      Flight::json(array(
        "send" => false
      ));
    }
  }

  /* On déplace l'élement */
  moveElement($type, $id, $agent->lspd_id, $to);

  addHistorique($agent->matricule, "6¤3¤" . $valueHistorique . "¤" . $id . "¤" . $from . "¤" . $to);

  Flight::json(array(
    "send" => true
  ));
});

Flight::route('POST /documents/add/img', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  $agent = Agent::getInfoAgent();

  $title = $_POST['title'];
  $image = $_FILES['image'];

  $dossier = "assets/img/documents/content/";
  $extensions = array('png', 'jpg', 'jpeg', 'gif');
  $taille_maxi = 5000000;

  $upload = uploadFile($dossier, $taille_maxi, $image, $extensions);

  if (!$upload) { return Flight::json(['send' => "Failed"]); }

  $path = "/assets/img/documents/content/" . $upload['name'];
  addHistorique($agent->matricule, "6¤0¤2¤" . $upload['name'] . "¤" . $path);

  $data = [
    "filePath" => $path
  ];

  Flight::json($data);
});

Flight::route('POST /documents/add/folder/@parentID', function($parentID) {
  verif_connecter();
  $agent = Agent::getInfoAgent();
  $agent = defineAutorisations("folder", $parentID, $agent);

  /* Variable POST */
  $title = $_POST['subfolder_title'];
  $description = $_POST['subfolder_description'];

  $autorisations = setAutorisation('folder_hab_', 'folder_grade_');
  /* Variable POST */

  /* Recomposition de la route */
  $routes = recupParentPath($parentID);
  $routePath = recuproutePath($routes);

  $permission = array(3, 4);
  if (!in_array($agent->docAccess, $permission)) {
    if ($agent->admin == 0) {
      Flight::redirect($routePath);
    }
  }

  Folder::createSubfolder($parentID, $title, $description, $autorisations['habilitations'], $autorisations['grades'], $agent->lspd_id);

  addHistorique($agent->matricule, "6¤0¤0¤" . $title . "¤" . $routePath);

  Flight::redirect($routePath);
});

Flight::route('POST /documents/add/document/@parentID', function($parentID) {
  verif_connecter();
  $agent = Agent::getInfoAgent();
  $agent = defineAutorisations("folder", $parentID, $agent);

  /* Variable POST */
  $title = $_POST['subfolder_title'];
  $description = $_POST['subfolder_description'];

  $autorisations = setAutorisation('document_hab_', 'document_grade_');
  /* Variable POST */

  /* Recomposition de la route */
  $routes = recupParentPath($parentID);
  $routePath = recuproutePath($routes);

  $permission = array(2, 3, 4);
  if (!in_array($agent->docAccess, $permission)) {
    if ($agent->admin == 0) {
      Flight::redirect($routePath);
    }
  }

  $idDoc = Document::uploadFile($parentID, $title, $description, 'document', $description, $autorisations['habilitations'], $autorisations['grades'], $agent->lspd_id);
  $document = Document::getLastIdInRep($parentID, 'document');

  addHistorique($agent->matricule, "6¤0¤1¤" . $title . "¤" . $routePath);

  Flight::redirect("/documents/page/$document->id");
});

Flight::route('POST /documents/add/pdf/@folderID', function($folderID) {
  verif_connecter();
  $agent = Agent::getInfoAgent();
  $agent = defineAutorisations("folder", $folderID, $agent);

  $dossier = "assets/img/documents/content/";
  $taille_maxi = 5000000;
  $fichier = $_FILES['pdf'];

  /* Variable POST */
  $title = $_POST['import_pdf_name'];
  $description = $_POST['import_pdf_description'];
  /* Variable POST */

  /* Récupération de la route */
  $routes = recupParentPath($folderID);
  $routePath = recuproutePath($routes);

  $permission = array(2, 3, 4);
  if (!in_array($agent->docAccess, $permission)) {
    if ($agent->admin == 0) {
      Flight::redirect($routePath);
    }
  }

  $upload = uploadFile($dossier, $taille_maxi, $fichier, array('pdf'));

  if (!$upload) {
    return Flight::redirect($routePath);
  }

  $folder = Folder::getFoldersById($folderID);
  Document::uploadFile($folderID, $title, $description, 'pdf', $upload['name'], explode(',', $folder->habilitations), explode(',', $folder->grades), $agent->lspd_id);

  addHistorique($agent->matricule, "6¤0¤4¤" . $upload['name'] . "¤" . $routePath);

  Flight::redirect($routePath);
});

Flight::route('POST /documents/add/img/@folderID', function($folderID) {
  verif_connecter();
  $agent = Agent::getInfoAgent();
  $agent = defineAutorisations("folder", $folderID, $agent);

  $dossier = "assets/img/documents/content/";
  $taille_maxi = 5000000;
  $fichier = $_FILES['img'];

  /* Variable POST */
  $title = $_POST['import_img_name'];
  $description = $_POST['import_img_description'];
  /* Variable POST */

  /* Récupération de la route */
  $routes = recupParentPath($folderID);
  $routePath = recuproutePath($routes);

  $permission = array(2, 3, 4);
  if (!in_array($agent->docAccess, $permission)) {
    if ($agent->admin == 0) {
      Flight::redirect($routePath);
    }
  }

  $extensions = array('png', 'jpg', 'jpeg', 'gif');
  $upload = uploadFile($dossier, $taille_maxi, $fichier, $extensions);

  /* Upload Failed */
  if (!$upload) {
    return Flight::redirect($routePath);
  }

  $folder = Folder::getFoldersById($folderID);
  Document::uploadFile($folderID, $title, $description, 'image', $upload['name'], explode(',', $folder->habilitations), explode(',', $folder->grades), $agent->lspd_id);

  addHistorique($agent->matricule, "6¤0¤3¤" . $upload['name'] . "¤" . $routePath);

  Flight::redirect($routePath);
});


Flight::route('POST /documents/edit/folder/@folderID', function($folderID) {
  verif_connecter();
  $agent = Agent::getInfoAgent();
  $agent = defineAutorisations("folder", $folderID, $agent);

  /* Variable POST */
  $title = $_POST['subfolder_title'];
  $description = $_POST['subfolder_description'];

  $autorisations = setAutorisation('edit_folder_hab_', 'edit_folder_grade_');
  /* Variable POST */

  /* Récupération de la route */
  $routes = recupParentPath($folderID);
  $routePath = recuproutePath($routes);

  $permission = array(4);
  if (!in_array($agent->docAccess, $permission)) {
    if ($agent->admin == 0) {
      Flight::redirect($routePath);
    }
  }

  addHistorique($agent->matricule, "6¤1¤0¤" . $title . "¤" . $routePath);
  updateFoldersById($folderID, $title, $description, $autorisations['habilitations'], $autorisations['grades'], $agent->lspd_id);

  Flight::redirect($routePath);
});

Flight::route('GET /documents/page/@docID', function($docID) {
  verif_connecter();
  $agent = Agent::getInfoAgent();
  $tree = getTree(1, $agent);
  $agent = defineAutorisations("document", $docID, $agent);

  $document = Document::getDocument($docID);
  $document->content = renderHTMLFromMarkdown(urldecode($document->content));

  /* Récupération de la route */
  $routes = recupParentPath($document->parent);
  $routePath = recuproutePath($routes);

  $permission = array(1, 2, 3, 4);
  if (!in_array($agent->docAccess, $permission)) {
    if ($document->createdby != $agent->lspd_id) {
      if ($agent->admin == 0) {
        Flight::redirect($routePath);
      }
    }
  }

  if ($document->type != "document") {
    return Flight::redirect($routePath);
  }

  if ($document->isdeleted == 1) {
    $document->deletedAgent = Agent::getInfoAgentID($document->deletedby);
  }

  if ($document->updateby) {
    $document->updatedAgent = Agent::getInfoAgentID($document->updateby);
  }

  Flight::view()->display('documents/document.twig', array(
    "_agent" => $agent,
    "document" => $document,
    "parents" => $routes,
    "files" => $tree
  ));
});

Flight::route('GET /documents/page/@docID/edit', function($docID) {
  verif_connecter();
  $agent = Agent::getInfoAgent();
  $agent = defineAutorisations("document", $docID, $agent);

  $document = Document::getDocument($docID);

  /* Recomposition de la route */
  $routes = recupParentPath($document->parent);
  $routePath = recuproutePath($routes);

  $permission = array(2, 3, 4);
  if (!in_array($agent->docAccess, $permission)) {
    if ($document->createdby != $agent->lspd_id) {
      if ($agent->admin == 0) {
        Flight::redirect($routePath);
      }
    }
  }

  if ($document->type != "document") {
    return Flight::redirect($routePath);
  }

  /* Opération sur le texte */
  $document->content = urldecode($document->content);
  $document->contentPreview = renderHTMLFromMarkdown(urldecode($document->content));

  /* autorisation */
  $document->habilitations = explode(",", $document->habilitations);
  $document->grades = explode(",", $document->grades);

  Flight::view()->display('documents/edit-document.twig', array(
    "_agent" => $agent,
    "document" => $document,
    "grades" => Grade::getList(),
  ));
});

Flight::route('POST /documents/page/@docID/edit-param', function($docID) {
  verif_connecter();
  $agent = Agent::getInfoAgent();
  $agent = defineAutorisations("document", $docID, $agent);


  /* Variable POST */
  $title = $_POST['document_title'];
  $description = $_POST['document_description'];
  $autorisations = setAutorisation('edit_document_hab_', 'edit_document_grade_');
  /* Variable POST */

  $permission = array(2, 3, 4);
  $document = Document::getDocument($docID);
  if (!in_array($agent->docAccess, $permission)) {
    if ($document->createdby != $agent->lspd_id) {
      if ($agent->admin == 0) {
        Flight::redirect("/documents/page/$docID");
      }
    }
  }

  addHistorique($agent->matricule, "6¤1¤1¤" . $title . "¤" . "/documents/page/$docID");
  updateDocumentsById($docID, $title, $description, $autorisations['habilitations'], $autorisations['grades'], $agent->lspd_id);

  Flight::redirect("/documents/page/$docID/edit");
});

Flight::route('POST /documents/page/@docID/save', function($docID) {
  verif_connecter();
  $agent = Agent::getInfoAgent();
  $agent = defineAutorisations("document", $docID, $agent);

  $document = Document::getDocument($docID);

  /* Recomposition de la route */
  $routes = recupParentPath($document->parent);
  $routePath = recuproutePath($routes);

  $permission = array(2, 3);
  if (!in_array($agent->docAccess, $permission)) {
    if ($document->createdby != $agent->lspd_id) {
      if ($agent->admin == 0) {
        Flight::redirect($routePath);
      }
    }
  }

  $_POST['new-content'] = htmlspecialchars(strip_tags($_POST['new-content']));

  if ($_POST['new-content'] != urldecode($document->content)) {
    Historique_Document::logModif($document->id, urlencode($document->content), urlencode($_POST['new-content']), $agent);
    addHistorique($agent->matricule, "6¤1¤2¤" . $document->title . "¤" . "/documents/page/$docID");
    updateDocumentContent($docID, $agent->lspd_id, urlencode($_POST['new-content']));
  }


  Flight::redirect("/documents/page/$docID");
});

Flight::route('GET /documents/page/@docID/delete', function($docID) {
  verif_connecter();
  $agent = Agent::getInfoAgent();
  $agent = defineAutorisations("document", $docID, $agent);

  $document = Document::getDocument($docID);

  /* Recomposition de la route */
  $routes = recupParentPath($document->parent);
  $routePath = recuproutePath($routes);

  $permission = array(3);
  if (!in_array($agent->docAccess, $permission)) {
    if ($agent->admin == 0) {
      Flight::redirect($routePath);
    }
  }

  addHistorique($agent->matricule, "6¤2¤0¤" . $document->title . "¤" . "/documents/page/$document->id");
  deleteDocument($document->id, $agent->lspd_id);

  Flight::redirect($routePath);
});

Flight::route("/documents/redirect/folder/@docID", function($docID) {
  $agent = Agent::getInfoAgent();
  $agent = defineAutorisations("folder", $docID, $agent);

  if ($agent->docAccess < 1) {
    Flight::redirect("/documents");
  }

  $document = Folder::getFoldersById($docID);
  $routes = recupParentPath($document->parent);
  $routePath = recuproutePath($routes);

  Flight::redirect($routePath . "/$docID");
});

Flight::route('GET /documents/*', function() {
  verif_connecter();
  $agent = Agent::getInfoAgent();
  $tree = getTree(1, $agent);

  $url = Flight::request()->url;

  /* Racine des documents */
  if (($url == "/documents/1") || ($url == "/documents/1/") || ($url == "/documents/")) {
    return Flight::redirect("/documents");
  }

  if (($url != "/documents") && ($url != "/documents/")) {
    $urlDecoupe = explode('/', $url);
    $countTotal = count(explode("/", $url));
    $paths = array_reverse(array_slice($urlDecoupe, 1, $countTotal));

    /* Vérification de la route */
    for ($i=0; $i < $countTotal-2; $i++) {
      $path = Folder::getFoldersById($paths[$i]);

      if (!$path) {
        echo "La page que vous rechercher n'existe pas";
        exit();
      }

      $parent = Folder::getFoldersById($path->parent);

      if (!$parent) {
        echo "La page que vous rechercher n'existe pas";
        exit();
      }
    }

    $pathFolder = $paths[0];
    $back = array_slice($urlDecoupe, 0, $countTotal-1);
  }
  else {
    $pathFolder = 1;
    $urlDecoupe = '/documents';
    $back = Null;
  }

  /* Récupération des infos du dossier */
  $folder = Folder::getFoldersById($pathFolder);
  $folder->habilitations = explode(",", $folder->habilitations);
  $folder->grades = explode(",", $folder->grades);

  /* Récupération des sous dossiers et documents */
  $subfolders = Folder::getSubfolderById($folder->id);
  $document = Document::getDocuments($folder->id);
  $documentDeleted = Document::getDocumentsDeleted($folder->id);

  /* Récupération des autorisations de l'agent */
  $agent = defineAutorisations("folder", $folder->id, $agent);

  /* Chemin */
  $cheminPath = recupParentPath($pathFolder);

  Flight::view()->display('documents/main.twig', array(
    "_agent" => $agent,
    "folder" => $folder,
    "route" => $url,
    "back" => $back,
    "subfolders" => $subfolders,
    "parents" => $cheminPath,
    "grades" => Grade::getList(),
    "documents" => $document,
    "deleted" => $documentDeleted,
    'files' => $tree
  ));
});
?>
